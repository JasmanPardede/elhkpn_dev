<?php

/**
 * @author Lahir Wisada <lahirwisada@gmail.com>
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . '/modules/eano/controllers/Announ.php';
require APPPATH.'/third_party/phpspreadsheet/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnnounCetak extends Announ {
    // CONST for color
    // var $color_green = '00008080';


    private $jumlah_data_bidangpn = 0;
    private $nomor_bap = FALSE;

    public function __construct() {
        parent::__construct(TRUE);
        // $this->load->model('ever/Verification_model');
    }

    private function __set_data_pn($bidangpn, $obj) {
        $array_message = array_filter((array) $bidangpn);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('no', 0);
        } else {
            $obj->clone_row('no', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {

                $template_string_no = 'no#' . ($key + 1);

                $template_string_nhk = 'NHK#' . ($key + 1);
                $template_string_nama = 'nama_jabatan#' . ($key + 1);
                $template_string_tgl_lapor = 'tgl_lapor#' . ($key + 1);
                $template_string_t_htb = 't_htb#' . ($key + 1);
                $template_string_t_ht = 't_ht#' . ($key + 1);
                $template_string_t_hbl = 't_hbl#' . ($key + 1);
                $template_string_t_sb = 't_sb#' . ($key + 1);
                $template_string_t_kas = 't_kas#' . ($key + 1);
                $template_string_t_lainnya = 't_lainnya#' . ($key + 1);
                $template_string_t_harta = 't_harta#' . ($key + 1);
                $template_string_t_hutang = 'htg#' . ($key + 1);
                $template_string_t_nhk = 't_nhk#' . ($key + 1);

                $obj->set_value($template_string_no, ($key + 1));

                $obj->set_value($template_string_nhk, $this->__is_cetak_var_not_blank($row->NHK, "-"));
                $obj->set_value($template_string_nama, $this->__is_cetak_var_not_blank($row->NAMA . ' / ' . $row->NAMA_JABATAN . ' - ' . $row->UK_NAMA . ' - ' . $row->INST_NAMA, "-"));
                $obj->set_value($template_string_tgl_lapor, $this->__is_cetak_var_not_blank(tgl_format($row->TGL_LAPOR), "-"));
                $obj->set_value($template_string_t_htb, $this->__is_cetak_var_not_blank(number_rupiah($row->T1), "-"));
                $obj->set_value($template_string_t_ht, $this->__is_cetak_var_not_blank(number_rupiah($row->T6), "-"));
                $obj->set_value($template_string_t_hbl, $this->__is_cetak_var_not_blank(number_rupiah($row->T5), "-"));
                $obj->set_value($template_string_t_sb, $this->__is_cetak_var_not_blank(number_rupiah($row->T2), "-"));
                $obj->set_value($template_string_t_kas, $this->__is_cetak_var_not_blank(number_rupiah($row->T4), "-"));
                $obj->set_value($template_string_t_lainnya, $this->__is_cetak_var_not_blank(number_rupiah($row->T3), "-"));
                $obj->set_value($template_string_t_harta, $this->__is_cetak_var_not_blank(number_rupiah($row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6), "-"));
                $obj->set_value($template_string_t_hutang, $this->__is_cetak_var_not_blank(number_rupiah($row->jumhut), "-"));
                $obj->set_value($template_string_t_nhk, $this->__is_cetak_var_not_blank(number_rupiah(($row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6) - $row->jumhut), "-"));
                $i++;
            }
        }
        return FALSE;
    }

    private function __get_r_ba_pengumuman($ID_BAP) {
        return $this->mglobal->get_data_all('R_BA_PENGUMUMAN', [
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN']
                        ], NULL, 'jbt.DESKRIPSI_JABATAN, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, bdg.BDG_KODE as kode_bidang', "R_BA_PENGUMUMAN.ID_BAP = '$ID_BAP'", ["T_LHKPN.TGL_LAPOR", "ASC"]);
    }

    private function __get_r_ba($ID_BAP) {
        return $this->mglobal->get_data_all('R_BA_PENGUMUMAN', [
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN']
                        ], NULL, 'jbt.DESKRIPSI_JABATAN, T_PN.NHK,T_PN.NAMA, T_LHKPN.TGL_LAPOR, bdg.BDG_KODE as kode_bidang', "R_BA_PENGUMUMAN.ID_BAP = '$ID_BAP'", ["T_LHKPN.TGL_LAPOR", "ASC"]);
    }

    private function __get_bidang_legislatif($id) {
        $c_bidang_legislatif = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
            //                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1' AND bdg.BDG_KODE = 'L'"
        );

        if ($c_bidang_legislatif && is_array($c_bidang_legislatif) && array_key_exists(0, $c_bidang_legislatif)) {
            return $c_bidang_legislatif[0];
        }
        return FALSE;
    }
    
    private function __get_bidang_eksekutif($id) {
        $c_bidang_eksekutif = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                //                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1' AND bdg.BDG_KODE = 'E'"
        );

        if ($c_bidang_eksekutif && is_array($c_bidang_eksekutif) && array_key_exists(0, $c_bidang_eksekutif)) {
            return $c_bidang_eksekutif[0];
        }
        return FALSE;
    }
    
    private function __get_bidang_yudikatif($id) {
        $c_bidang_yudikatif = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                //                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1' AND bdg.BDG_KODE = 'Y'"
        );

        if ($c_bidang_yudikatif && is_array($c_bidang_yudikatif) && array_key_exists(0, $c_bidang_yudikatif)) {
            return $c_bidang_yudikatif[0];
        }
        return FALSE;
    }
    
    private function __get_bidang_bumd($id) {
        $c_bidang_bumd = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
            //                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1' AND bdg.BDG_KODE = 'B'"
        );

        if ($c_bidang_bumd && is_array($c_bidang_bumd) && array_key_exists(0, $c_bidang_bumd)) {
            return $c_bidang_bumd[0];
        }
        return FALSE;
    }

    private function __get_ba_pengumuman($id, $offset = 0, $limit = 5) {
        $bidangpn = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                //            ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, "T_PN.ID_PN, mj.NAMA_JABATAN,inst.INST_NAMA, T_PN.NHK,T_PN.NAMA, (SELECT SUM(T_LHKPN_HUTANG.SALDO_HUTANG) FROM T_LHKPN_HUTANG WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_BAP, ba.NOMOR_PNRI, 
                (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
            (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6 ", "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1'", ['T_LHKPN.ID_PN', 'ASC'], NULL, NULL, NULL
        );
        ///////perubahan dari evan, $offset dan $limit dijadikan null agar bisa di panggil semua data dan diurutkan lansung
        return $bidangpn;
    }

    private function __count_ba_pengumuman($id) {
        $r_jumlah_data = $this->mglobal->get_data_all(
                'R_BA_PENGUMUMAN', [
            ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
            ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
            ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
            ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
            //            ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                ], NULL, "count(T_PN.ID_PN) as jumlah_data ", "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1'", ['inst.INST_NAMA', 'ASC'], 0, NULL, NULL
        );

        if ($r_jumlah_data && is_array($r_jumlah_data) && array_key_exists(0, $r_jumlah_data) && property_exists($r_jumlah_data[0], "jumlah_data")) {
            return $r_jumlah_data[0]->jumlah_data;
        }
        return 0;
                //        var_dump($r_jumlah_data);exit;
    }

    private function write_bidangpn($id) {
        
        if ($this->jumlah_data_bidangpn && $this->jumlah_data_bidangpn > 0) {
            $current_execution = 1;

            //dimana 5 adalah limit
            $exec_times = ceil($this->jumlah_data_bidangpn / 5);
 
            if ($this->__execution_time == 'min') {
                $exec_times = 1;
            }
            $str_tr = "";
            $current_no = 0;

            ////////tambahan evan, agar data tidak diulang////////
            $exec_times = 1;
            ////////tambahan evan////////
            
            while ($current_execution <= $exec_times) {
                $offset = calculate_offset($current_execution);
                $bidangpn = $this->__get_ba_pengumuman($id, $offset, 5);
               
               ////////tambahan evan, untuk mengurutkan data by total harta////////
                foreach ($bidangpn as $key => $row) {
                    $bidangpn[$key]->SORT_TOTAL_HARTA = $row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6;
                    array_push($bidangpn[$key],['data_urut'=>1]);
                }
                
                usort($bidangpn,function($first,$second){
                    return $first->SORT_TOTAL_HARTA < $second->SORT_TOTAL_HARTA;
                });
                ////////tambahan evan////////
                
                

                foreach ($bidangpn as $key => $row) {
                
                    if (!$this->nomor_bap) {
                        $this->nomor_bap = $row->NOMOR_BAP . '/LHK.03/' . $row->NOMOR_PNRI . '/' . date('m') . '/' . date('Y');
                    }

                    $current_no++;
                    $str_tr .= $this->load->view('announ/lembar_persetujuan/row_data_pn', [
                        "no" => $current_no,
                        "NHK" => $this->__is_cetak_var_not_blank($row->NHK, "-"),
                        "nama_jabatan" => $this->__is_cetak_var_not_blank($row->NAMA . ' / ' . $row->NAMA_JABATAN . ' - ' . $row->UK_NAMA . ' - ' . $row->INST_NAMA, "-"),
                        "tgl_lapor" => $this->__is_cetak_var_not_blank(tgl_format($row->TGL_LAPOR), "-"),
                        "t_htb" => $this->__is_cetak_var_not_blank(number_rupiah($row->T1), "-"),
                        "t_ht" => $this->__is_cetak_var_not_blank(number_rupiah($row->T6), "-"),
                        "t_hbl" => $this->__is_cetak_var_not_blank(number_rupiah($row->T5), "-"),
                        "t_sb" => $this->__is_cetak_var_not_blank(number_rupiah($row->T2), "-"),
                        "t_kas" => $this->__is_cetak_var_not_blank(number_rupiah($row->T4), "-"),
                        "t_lainnya" => $this->__is_cetak_var_not_blank(number_rupiah($row->T3), "-"),
                        "t_harta" => $this->__is_cetak_var_not_blank(number_rupiah($row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6), "-"),
                        "htg" => $this->__is_cetak_var_not_blank(number_rupiah($row->jumhut), "-"),
                        "t_nhk" => $this->__is_cetak_var_not_blank(number_rupiah(($row->T1 + $row->T2 + $row->T3 + $row->T4 + $row->T5 + $row->T6) - $row->jumhut), "-")
                            ], TRUE);
                }
                
                unset($bidangpn);
                $current_execution++;
            }
            $table_string = $this->load->view('announ/lembar_persetujuan/data_pn', [
                "rows_pegawai" => $str_tr
                    ], TRUE);

            return $table_string;
        }
        return "";
    }

    public function index($ID_BAP = FALSE) {

        if ($ID_BAP === FALSE) {
            return FALSE;
        }

        $head = $this->mglobal->get_data_all('T_BA_PENGUMUMAN', NULL, NULL, '*', "T_BA_PENGUMUMAN.ID_BAP = '$ID_BAP'")[0];
        $id = $head->ID_BAP;

        $items = $this->__get_r_ba_pengumuman($ID_BAP);

        $join = [
            ['table' => 'T_PN', 'on' => 'VALUE=ID_PN']
        ];

        /**
          $c_bidang = $this->mglobal->get_data_all(
          'R_BA_PENGUMUMAN', [
          ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
          ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
          ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
          ['table' => 'M_JABATAN mj', 'on' => 'mj.ID_JABATAN   =  jbt.ID_JABATAN'],
          ['table' => 'M_INST_SATKER inst', 'on' => 'mj.INST_SATKERKD   =  inst.INST_SATKERKD'],
          ['table' => 'M_UNIT_KERJA unke', 'on' => 'mj.UK_ID   =  unke.UK_ID'],
          ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
          //                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
          ], NULL, 'bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA,COUNT(bdg.BDG_NAMA) AS jum', "R_BA_PENGUMUMAN.ID_BAP = '$id' AND jbt.IS_PRIMARY = '1'"
          )[0];
         * */
        $c_bidang_eksekutif = $this->__get_bidang_eksekutif($id);
        $c_bidang_legislatif = $this->__get_bidang_legislatif($id);
        $c_bidang_yudikatif = $this->__get_bidang_yudikatif($id);
        $c_bidang_bumd = $this->__get_bidang_bumd($id);

        $this->jumlah_data_bidangpn = $this->__count_ba_pengumuman($id);
        //        echo $this->jumlah_data_bidangpn;exit;

        $string_table = $this->write_bidangpn($id);

        //        $bidangpn = $this->__get_ba_pengumuman($id, 0, 5);
        //        echo $this->db->last_query();exit;


        $nomor_bap = str_replace('/', '-', $head->NOMOR_BAP);


        $this->load->library('lwphpword/lwphpword', array(
            "base_path" => APPPATH . "../uploads/lembar_persetujuan/",
            "base_url" => base_url() . "../uploads/lembar_persetujuan/",
            "base_root" => base_url(),
        ));

        $output_filename = "Lembar_Persetujuan_" . $nomor_bap . ".docx";
        $template_file = "../file/template/DraftLembarPersetujuan_v3.docx";



        $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

        $this->lwphpword->save_path = APPPATH . "../uploads/lembar_persetujuan/";

        //        foreach ($r_bid as $bid) {
        //            $this->lwphpword->set_value("bidang", $bid);
        //            foreach ($r_ins as $ins) {
        //                $this->lwphpword->set_value("inst_nama", $ins);
        //                foreach ($bidangpn as $data) {
        //                    if ($bid == $data->BDG_NAMA) {
        //                        if ($ins == $data->INST_NAMA) {
        $this->lwphpword->set_value("jml_eksekutif", $c_bidang_eksekutif->jum != 0 ? $c_bidang_eksekutif->jum : "-");
        $this->lwphpword->set_value("jml_legislatif", $c_bidang_legislatif->jum != 0 ? $c_bidang_legislatif->jum : "-");
        $this->lwphpword->set_value("jml_yudikatif", $c_bidang_yudikatif->jum != 0 ? $c_bidang_yudikatif->jum : "-");
        $this->lwphpword->set_value("jml_bumnd", $c_bidang_bumd->jum != 0 ? $c_bidang_bumd->jum : "-");
        $this->lwphpword->set_value("jumlah_pn", $this->jumlah_data_bidangpn);
        $this->lwphpword->set_value("date_now", tgl_format(date('d-F-Y')));
        /**
         * formatnya => Nomor Berita Acara/LHK.03/Nomor Pengumuman/Bulan Berjalan/Tahun Berjalan (01/LHK.03/111/10/2017
         */
        //        $nomor = $bidangpn[0]->NOMOR_BAP . '/LHK.03/' . $bidangpn[0]->NOMOR_PNRI . '/' . date('m') . '/' . date('Y');
        $this->lwphpword->set_value("nomor", $this->nomor_bap);
        
        $this->lwphpword->set_xml_value("table_bidangpn", $string_table);

        //        $this->__set_data_pn($bidangpn, $this->lwphpword);
        //                        }
        //                    }
        //                }
        //            }
        //        }

        $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE);
        //        $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
        $this->lwphpword->download($save_document_success->document_path, $output_filename);
        unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
    }

    /**
     * di akhir function harus memberikan return untuk mengatasi file excel yang corrupt
     * + 0 untuk mengatasi formated as text (number to text)
     */
    public function cetak_ba($ID_BAP){

        $id = $ID_BAP;

        $join = [
            ['table' => 'T_BA_PENGUMUMAN', 'on' => 'R_BA_PENGUMUMAN.ID_BAP = T_BA_PENGUMUMAN.ID_BAP'],
            ['table' => 'T_LHKPN', 'on' => 'R_BA_PENGUMUMAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'join' => 'RIGHT'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
            ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN AND T_LHKPN_JABATAN.IS_PRIMARY = "1"'],
            ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN_DATA_PRIBADI.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
            ['table' => 'M_BIDANG bdg', 'on' => 'M_INST_SATKER.INST_BDG_ID =  bdg.BDG_ID'],
            ['table' => 'M_ESELON', 'on' => 'M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON'],
            ['table' => 'T_STATUS_AKHIR_JABAT', 'on' => 'T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT'],
                
        ];
        $select = 'T_LHKPN.ID_LHKPN AS ID_LHKPN_DIJABATAN, M_JABATAN.NAMA_JABATAN,t_lhkpn.STATUS,R_BA_PENGUMUMAN.ID, bdg.BDG_KODE as KODE_BIDANG, T_PN.NAMA as NAMA_PN, T_PN.NIK, M_INST_SATKER.INST_NAMA as INSTANSI, T_LHKPN.TGL_LAPOR, T_PN.NHK, T_BA_PENGUMUMAN.NOMOR_PNRI, T_BA_PENGUMUMAN.TGL_PNRI, M_UNIT_KERJA.UK_NAMA,
            (SELECT SUM(T_LHKPN_HUTANG.SALDO_HUTANG) FROM T_LHKPN_HUTANG WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = 1) AS jumhut,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = 0 AND IS_ACTIVE = 1) T1,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN =  0 AND IS_ACTIVE = 1) T2,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN =  0 AND IS_ACTIVE = 1) T3,
            (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN =  0 AND IS_ACTIVE = 1) T4,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN =  0 AND IS_ACTIVE = 1) T5,
            (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN =  0 AND IS_ACTIVE = 1) T6  ';
            
        $where = 'R_BA_PENGUMUMAN.ID_BAP ='.$id;

        $data = $this->mglobal->get_data_all('R_BA_PENGUMUMAN', $join, $where, $select);

        $spreadsheet = new Spreadsheet();

        $styleArray = array(
        'font'  => array(
            'size'  => 10,
            'name'  => 'Calibri'
        ));
        $spreadsheet->getDefaultStyle()
        ->applyFromArray($styleArray);


        $spreadsheet->setActiveSheetIndex(0);

        $this->cetak_baa($data, $spreadsheet);
        
        //setup file meta
        ob_end_clean();
        $filename = 'BA_Pengumuman '.date('dmyGi').'.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        ob_end_clean();

        // ------ save and export to folder download

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        return "Excel is generated";
    }

    public function cetak_baa($data, $spreadsheet){
        $spreadsheet->getActiveSheet()->setTitle('BA Pengumuman');
        $bidang = [
            'L' => 'LEGISLATIF',
            'E' => 'EKSEKUTIF',
            'Y' => 'YUDIKATIF',
            'B' => 'BUMN/D',
        ];
        if($data){
            $startRow = 2;
            $no = 1;
            foreach($data as $d){
                $harta = $d->T1+$d->T2+$d->T3+$d->T4+$d->T5+$d->T6;
                $spreadsheet->getActiveSheet()->setCellValue('A'.$startRow, $no);
                $spreadsheet->getActiveSheet()->setCellValue('B'.$startRow, $d->NOMOR_PNRI);
                $spreadsheet->getActiveSheet()->setCellValue('C'.$startRow, tgl_format($d->TGL_PNRI));
                $spreadsheet->getActiveSheet()->setCellValue('D'.$startRow, $bidang[$d->KODE_BIDANG]);
                $spreadsheet->getActiveSheet()->setCellValue('E'.$startRow, $d->NHK);
                $spreadsheet->getActiveSheet()->setCellValue('F'.$startRow, "'".$d->NIK);
                $spreadsheet->getActiveSheet()->setCellValue('G'.$startRow, $d->NAMA_PN);
                $spreadsheet->getActiveSheet()->setCellValue('H'.$startRow, $d->INSTANSI);
                $spreadsheet->getActiveSheet()->setCellValue('I'.$startRow, $d->UK_NAMA);
                $spreadsheet->getActiveSheet()->setCellValue('J'.$startRow, $d->NAMA_JABATAN);
                $spreadsheet->getActiveSheet()->setCellValue('K'.$startRow, tgl_format($d->TGL_LAPOR));
                $spreadsheet->getActiveSheet()->setCellValue('L'.$startRow, ($d->T1 == '') ? 0 : $d->T1 + 0);
                $spreadsheet->getActiveSheet()->setCellValue('M'.$startRow, ($d->T6 == '') ? 0 : $d->T6 + 0);
                $spreadsheet->getActiveSheet()->setCellValue('N'.$startRow, ($d->T5 == '') ? 0 : $d->T5 + 0);
                $spreadsheet->getActiveSheet()->setCellValue('O'.$startRow, ($d->T2 == '') ? 0 : $d->T2 + 0);
                $spreadsheet->getActiveSheet()->setCellValue('P'.$startRow, ($d->T4 == '') ? 0 : $d->T4 + 0);
                $spreadsheet->getActiveSheet()->setCellValue('Q'.$startRow, ($d->T3 == '') ? 0 : $d->T3 + 0);
                $spreadsheet->getActiveSheet()->setCellValue('R'.$startRow, ($harta == '') ? 0 : $harta);
                $spreadsheet->getActiveSheet()->setCellValue('S'.$startRow, ($d->jumhut == '') ? 0 : $d->jumhut + 0);
                $spreadsheet->getActiveSheet()->setCellValue('T'.$startRow, ($harta - $d->jumhut == '') ? 0 : $harta - $d->jumhut + 0);
                $spreadsheet->getActiveSheet()->getStyle('L'.$startRow.':T'.$startRow)->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $spreadsheet->getActiveSheet()->getStyle('L'.$startRow.':T'.$startRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $startRow++;
                $no++;
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('A1', 'No.');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'BN');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'TANGGAL TERBIT');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'BIDANG');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'NHK');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'NIK');
        $spreadsheet->getActiveSheet()->setCellValue('G1', 'NAMA');
        $spreadsheet->getActiveSheet()->setCellValue('H1', 'INSTANSI');
        $spreadsheet->getActiveSheet()->setCellValue('I1', 'UNIT KERJA');
        $spreadsheet->getActiveSheet()->setCellValue('J1', 'JABATAN');
        $spreadsheet->getActiveSheet()->setCellValue('K1', 'TANGGAL PELAPORAN');
        $spreadsheet->getActiveSheet()->setCellValue('L1', 'Harta Tidak Bergerak');
        $spreadsheet->getActiveSheet()->setCellValue('M1', 'Harta Bergerak');
        $spreadsheet->getActiveSheet()->setCellValue('N1', 'Harta Bergerak Lainnya');
        $spreadsheet->getActiveSheet()->setCellValue('O1', 'Surat Berharga');
        $spreadsheet->getActiveSheet()->setCellValue('P1', 'Kas/Setara Kas');
        $spreadsheet->getActiveSheet()->setCellValue('Q1', 'Harta Lainnya');
        $spreadsheet->getActiveSheet()->setCellValue('R1', 'TOTAL NILAI HARTA');
        $spreadsheet->getActiveSheet()->setCellValue('S1', 'HUTANG');
        $spreadsheet->getActiveSheet()->setCellValue('T1', 'TOTAL HARTA KEKAYAAN');
        $spreadsheet->getActiveSheet()->setCellValue('U1', 'KETERANGAN');

        $this->style_table_header('A1:U1', '00008080', $spreadsheet);
        
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(7);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(40);
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
    }
    
    private function __cek_eksekutif($id){

    }
       // --------- private functions
    private function style_table_header($startEndCell, $bgcolor, $spreadsheet, $text_align = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, $fontcolor = \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE, $isBold = true, $isWrapText = true)
    {
        # code...
        if ($bgcolor != '') {
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFill()->getStartColor()->setARGB($bgcolor);
        }

        $styleArray4 = [
        'borders' => [
            'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => $this->color_grayborder],
            ],
        ],
        ];

        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFont()->getColor()->setARGB($fontcolor);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getAlignment()->setHorizontal($text_align);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)
        ->getFont()->setBold($isBold);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)->getAlignment()->setWrapText($isWrapText);
        $spreadsheet->getActiveSheet()->getStyle($startEndCell)->applyFromArray($styleArray4);

    }
}
