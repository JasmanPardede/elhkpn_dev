<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Core_Ikhtisar
 *
 * @author nurfadillah
 *
 * @property bool $is_ever Menandakan kontroller yang dipanggil default TRUE, jika efill maka kontroller efill harus set value jadi FALSE
 */
class Core_Ikhtisar extends CI_Controller {

    protected $is_validation = FALSE;
    protected $field_id_lhkpn = 'ID_LHKPN';
    protected $table_lhkpn = 't_lhkpn';
    protected $is_ever = TRUE;

    private $sess_template_data = [
        "id_user" => NULL,
        "pesan" => NULL,
        "subject" => NULL,
        "word_location" => NULL,
        "is_trusted" => TRUE
    ];
    private $sess_template_kirim_lhkpn = [
        "data" => [], //see $this->sess_template_data
        "id_lhkpn" => NULL,
    ];

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->config->load('harta');

        if ($this->is_ever) {
            $this->load->model('mlhkpnkeluarga');
        }
    }

    protected function __clean_output($data_shown, $tag_name, $string_template = "%s") {
        if ($data_shown && $data_shown != '') {
            /**
             * @todo replace string template then return to template processor
             */
        }
        $this->lwphpword->deleteBlock($tag_name);
    }

    protected function __get_asal_usul_harta($au) {
        $asalusul = ['1' => 'HASIL SENDIRI', '2' => 'WARISAN', '3' => 'HIBAH DENGAN AKTA', '4' => 'HIBAH TANPA AKTA', '5' => 'HADIAH', '6' => 'LAINNYA'];
        $asal_usul = array();
        $asal_usul = explode(',', $au);
        if (count($asal_usul) > 0) {
            $banyak_asalusul = count($asal_usul);
            $i = 1;
            $asalusuls = '';
            foreach ($asal_usul as $row) {
                if (array_key_exists($row, $asalusul)) {
                    $pisah = ($banyak_asalusul === $i ? "" : ", ");
                    $asalusuls .= $asalusul[$row] . $pisah;
                    $i++;
                }
            }
        } else {
            echo "----";
        }
        return $asalusuls;
    }

    protected function __get_pemanfaatan_harta($pf) {
        $pemanfaatan = ['1' => 'TEMPAT TINGGAL', '2' => 'DISEWAKAN', '3' => 'PERTANIAN / PERKEBUNAN / PERIKANAN / PERTAMBANGAN', '4' => 'LAINNYA'];
        $manfaat = array();
        $manfaat = explode(',', $pf);
        if (count($manfaat) > 0) {
            $banyak = count($manfaat);
            $i = 1;
            $manfaats = '';
            foreach ($manfaat as $row) {
                if (array_key_exists($row, $pemanfaatan)) {
                    $pisah = ($banyak === $i ? "" : ", ");
                    $manfaats .= $pemanfaatan[$row] . $pisah;
                    $i++;
                }
            }
        } else {
            echo "----";
        }

        return $manfaats;
    }

    protected function __get_data_cetak($ID_LHKPN) {
//        $cek_id_user = $this->lhkpn($ID_LHKPN);
        $data = array();
        $data['LHKPN'] = $this->lhkpn($ID_LHKPN);
        $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
        $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
        $data['JABATAN'] = $this->jabatan($ID_LHKPN);
        $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
        $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);

        $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
        $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
        $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
        $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
        $data['HUTANG'] = $this->hutang($ID_LHKPN);

        $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
        $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
        $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);

        return $data;
    }

    protected function __get_data_cetak_imp_xl_lhkpn($id_imp_xl_lhkpn) {

        $data = array();
        $data['PRIBADI'] = $this->pribadi($id_imp_xl_lhkpn);
        $data['KELUARGA'] = $this->keluarga($id_imp_xl_lhkpn);
        $data['JABATAN'] = $this->jabatan($id_imp_xl_lhkpn);

        $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($id_imp_xl_lhkpn);
        $data['HARTA_BERGERAK'] = $this->harta_bergerak($id_imp_xl_lhkpn);

        $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($id_imp_xl_lhkpn);
        $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($id_imp_xl_lhkpn);
        $data['HARTA_LAINNYA'] = $this->harta_lainnya($id_imp_xl_lhkpn);
        $data['HARTA_KAS'] = $this->harta_kas($id_imp_xl_lhkpn);
        $data['HUTANG'] = $this->hutang($id_imp_xl_lhkpn);

        $data['PENERIMAAN'] = $this->penerimaan($id_imp_xl_lhkpn);
        $data['PENGELUARAN'] = $this->pengeluaran($id_imp_xl_lhkpn);
        $data['FASILITAS'] = $this->fasilitas($id_imp_xl_lhkpn);

        return $data;
    }

    protected function __set_data_cetak_jabatan($data_jabatan) {

        $BIDANG = "-";
        $LEMBAGA = "-";
        $JABATAN = "-";
        $SUK = "-";
        $UK = "-";
//echo count($data['JABATAN'], COUNT_RECURSIVE).'<br/><pre>';
//print_r($data['JABATAN'][0]->BDG_NAMA);
        $jb = $data_jabatan;
        if ($data_jabatan) {
            if (count($data_jabatan) == '1') {
                $BIDANG = $jb[0]->BDG_NAMA;
                $LEMBAGA = $jb[0]->INST_NAMA;
                $JABATAN = $jb[0]->NAMA_JABATAN;
                $SUK = $jb[0]->SUK_NAMA;
                $UK = $jb[0]->UK_NAMA;
            } else {
                foreach ($data_jabatan as $jb) {
                    if ($jb->IS_PRIMARY == "1") {
                        $BIDANG = $jb->BDG_NAMA;
                        $LEMBAGA = $jb->INST_NAMA;
                        $JABATAN = $jb->NAMA_JABATAN;
                        $SUK = $jb->SUK_NAMA;
                        $UK = $jb->UK_NAMA;
                        //break;
                    }
                }
            }
        }

        return array($BIDANG, $LEMBAGA, $JABATAN, $SUK, $UK);
    }

    protected function __get_cetak_hubungan_keluarga($index_hub_keluarga, $lhkpn_ver = FALSE) {
        $HUBUNGAN_KELUARGA = array();
        if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11' || $lhkpn_ver == '2.1') {
            $HUBUNGAN_KELUARGA[1] = '-';
            $HUBUNGAN_KELUARGA[3] = 'ISTRI';
            $HUBUNGAN_KELUARGA[2] = 'SUAMI';
            $HUBUNGAN_KELUARGA[4] = 'ANAK TANGGUNGAN';
            $HUBUNGAN_KELUARGA[5] = 'ANAK BUKAN TANGGUNGAN';
            $HUBUNGAN_KELUARGA[6] = 'LAINNYA';
            $HUBUNGAN_KELUARGA[''] = '-';
            $HUBUNGAN_KELUARGA[0] = '-';
        } else {
            $HUBUNGAN_KELUARGA[1] = 'ISTRI';
            $HUBUNGAN_KELUARGA[2] = 'SUAMI';
            $HUBUNGAN_KELUARGA[3] = 'ANAK TANGGUNGAN';
            $HUBUNGAN_KELUARGA[4] = 'ANAK BUKAN TANGGUNGAN';
            $HUBUNGAN_KELUARGA[5] = 'LAINNYA';
            $HUBUNGAN_KELUARGA[''] = '-';
            $HUBUNGAN_KELUARGA[0] = '-';
        }

        return $HUBUNGAN_KELUARGA[$index_hub_keluarga];
    }

    /**
     *
     * SEmentara ini hanya digunakan di Everifikasi saja, portal belum menggunakannya
     *
     * @param type $jenis_kelamin
     * @return string
     */
    protected function __get_cetak_jenis_kelamin($jenis_kelamin = FALSE) {
        if (map_jenis_kelamin_to_bin($jenis_kelamin, (is_numeric($jenis_kelamin) ? 'num' : 'txt')) == 1) {
            return "Laki-Laki";
        }
        if (map_jenis_kelamin_to_bin($jenis_kelamin, (is_numeric($jenis_kelamin) ? 'num' : 'txt')) == 0) {
            return "Perempuan";
        }
        return "";
    }

    protected function map_jenis_kelamin_to_bin($jenis_kelamin = FALSE, $type = 'bin') {
        $return_value = NULL;
        if ($jenis_kelamin) {
            switch ($type) {

                case 'num': {
                        /**
                         * 1 pria
                         * 2 wanita
                         */
                        $return_value = $jenis_kelamin == '2' || $jenis_kelamin == '1' ? '1' : '0';
                        break;
                    }
                case 'abbr': {
                        $return_value = preg_replace('/\s+/', '', strtolower($jenis_kelamin)) == 'pria' ? '1' : '0';
                        break;
                    }
                case 'txt': {
                        $return_value = preg_replace('/\s+/', '', strtolower($jenis_kelamin)) == 'laki-laki' ? '1' : '0';
                        break;
                    }
                case 'bin':
                default : {
                        $return_value = $jenis_kelamin == '1' || $jenis_kelamin == 1 ? '1' : '0';
                        break;
                    }
            }
        }
        return $return_value;
    }

    protected function __get_cetak_is_copy($is_copy) {
//        return $is_copy == '1' ? FALSE : TRUE;
        return $is_copy == '1' ? TRUE : FALSE;
    }

    protected function __get_cetak_status_harta($index_status_harta, $is_copy) {
        $STATUS_HARTA = array();
        $STATUS_HARTA[1] = 'TETAP';
        $STATUS_HARTA[2] = 'UBAH';
        $STATUS_HARTA[3] = '-';
        $STATUS_HARTA[4] = 'LAPOR';
        $STATUS_HARTA[''] = '-';

        if ($this->__get_cetak_is_copy($is_copy)) {
            $STATUS_HARTA[3] = 'BARU';
        }

        return $STATUS_HARTA[$index_status_harta];
    }

    protected function __get_cetak_atas_nama($index_atas_nama) {
        $ATAS_NAMA = array();
        $ATAS_NAMA[1] = 'PN YANG BERSANGKUTAN';
        $ATAS_NAMA[2] = 'PASANGAN / ANAK';
        $ATAS_NAMA[3] = 'LAINNYA';
        $ATAS_NAMA[''] = '';

        return $ATAS_NAMA[$index_atas_nama];
    }

    protected function __get_cetak_atas_nama_v2($index_atas_nama,$pasangan_anak = false,$ket_lainnya = false){
        $get_atas_nama = $index_atas_nama;
        $atas_nama = '';
        $get_atas_nama = check_atas_nama($get_atas_nama);
        if(strstr($get_atas_nama, "5")){
            $atas_nama = substr($get_atas_nama,2);
        }
        if(strstr($get_atas_nama, "1")){
            $atas_nama = 'PN YANG BERSANGKUTAN';
        }
        if(strstr($get_atas_nama, "2")){

            $pasangan_array = explode(',', $pasangan_anak);
            $get_list_pasangan = '';
            $loop_first_pasangan = 0;
            foreach($pasangan_array as $ps){
                $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                if($loop_first_pasangan==0){
                    $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                }else{
                    $get_list_pasangan = $get_list_pasangan.', '.$data_pasangan_anak[0]['NAMA'];
                }
                $loop_first_pasangan++;
            }
            $show_pasangan = $get_list_pasangan;
            if($atas_nama==''){
                $atas_nama = $atas_nama.'PASANGAN/ANAK ('.$show_pasangan.')';
            }else{
                $atas_nama = $atas_nama.', PASANGAN/ANAK ('.$show_pasangan.')';
            }
        }
        if(strstr($get_atas_nama, "3")){
            if($atas_nama==''){
                $atas_nama = $atas_nama.'LAINNYA ('.$ket_lainnya.')';
            }else{
                $atas_nama = $atas_nama.', LAINNYA ('.$ket_lainnya.')' ;
            }
        }
        return $atas_nama;
    }

    protected function __get_cetak_keterangan($is_pelepasan, $jenis_pelepasan, $status_harta, $is_copy) {
        if ($is_pelepasan == '1') {
            $output = $jenis_pelepasan ? $jenis_pelepasan : 'LEPAS';
            return $output;
        }
        return $this->__get_cetak_status_harta($status_harta, $is_copy);
    }

    protected function __is_cetak_var_not_blank($val, $default_value = "", $bool = FALSE) {
        $val = trim($val);
        if ($val != "" && $val != NULL && $val != FALSE) {
            return $bool ? TRUE : htmlspecialchars($val);
        }
        return $bool ? FALSE : $default_value;
    }

    protected function __get_cetak_nilai_lama($nilai_lama, $is_copy) {
        return $this->__get_cetak_is_copy($is_copy) ? "-" : "Rp. " . number_rupiah($nilai_lama);
    }

    protected function __arrange_cetak_data_keluarga($data_keluarga, $lhkpn_ver = FALSE) {

        $jumlah_data_keluarga = count($data_keluarga);
        if ($jumlah_data_keluarga < 0) {
            $this->lwphpword->clone_row('no_fam', 0);
        } else {
            $this->lwphpword->clone_row('no_fam', $jumlah_data_keluarga);

            foreach ($data_keluarga as $key => $fam) {
                $template_string_no_fam = 'no_fam#' . ($key + 1);
                $template_string_nama_fam = 'nama_fam#' . ($key + 1);
                $template_string_hubungan_fam = 'hubungan_fam#' . ($key + 1);
                $template_string_tmpt_tgl_lahir_jenis_kelamin_fam = 'tmpt_tgl_lahir_jenis_kelamin_fam#' . ($key + 1);
                $template_string_pekerjaan_fam = 'pekerjaan_fam#' . ($key + 1);
                $template_string_alamat_rumah_fam = 'alamat_rumah_fam#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_fam, ($key + 1));
                $this->lwphpword->set_value($template_string_nama_fam, $this->__is_cetak_var_not_blank($fam->NAMA, "-"));
                $this->lwphpword->set_value($template_string_hubungan_fam, $this->__get_cetak_hubungan_keluarga($fam->HUBUNGAN, $lhkpn_ver));

                $tgl_lahir = null;
                if ($fam->TANGGAL_LAHIR) {
                    $tgl_lahir = tgl_format($fam->TANGGAL_LAHIR);
                }

                $jenis_kelamin = $this->is_ever ? $this->__is_cetak_var_not_blank($this->__get_cetak_jenis_kelamin($fam->JENIS_KELAMIN), "-") : $this->__is_cetak_var_not_blank($fam->JENIS_KELAMIN, "-");
                $ttl_fam = $this->__is_cetak_var_not_blank($fam->TEMPAT_LAHIR, "-") . ' , ' . $tgl_lahir . ' / ' . $jenis_kelamin;

                $this->lwphpword->set_value($template_string_tmpt_tgl_lahir_jenis_kelamin_fam, $ttl_fam);
                $this->lwphpword->set_value($template_string_pekerjaan_fam, $this->__is_cetak_var_not_blank($fam->PEKERJAAN, "-"));
                $this->lwphpword->set_value($template_string_alamat_rumah_fam, $this->__is_cetak_var_not_blank($fam->ALAMAT_RUMAH, "-"));
            }
        }
    }

    protected function __arrange_cetak_data_jabatan($data) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_jab', 0);
        } else {
            $this->lwphpword->clone_row('no_jab', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_jab = 'no_jab#' . ($key + 1);
                $template_string_nama_jab = 'nama_jab#' . ($key + 1);
                $template_string_inst_nama_jab = 'inst_nama_jab#' . ($key + 1);
                $template_string_uk_nama_jab = 'uk_nama_jab#' . ($key + 1);
                $template_string_suk_nama_jab = 'suk_nama_jab#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_jab, ($key + 1));
                $this->lwphpword->set_value($template_string_nama_jab, $this->__is_cetak_var_not_blank($row->NAMA_JABATAN, "-"));
                $this->lwphpword->set_value($template_string_inst_nama_jab, $this->__is_cetak_var_not_blank($row->INST_NAMA, "-"));
                $this->lwphpword->set_value($template_string_uk_nama_jab, $this->__is_cetak_var_not_blank($row->UK_NAMA, "-"));
                $this->lwphpword->set_value($template_string_suk_nama_jab, $this->__is_cetak_var_not_blank($row->SUK_NAMA, "-"));
            }
        }
    }

    protected function __arrange_cetak_data_harta_tidak_bergerak($data, $is_copy) {
        $jumlah_data = count($data);

        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_htb', 0);
        } else {
            $this->lwphpword->clone_row('no_htb', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_htb = 'no_htb#' . ($key + 1);

                $template_string_jalan_htb = 'jalan_htb#' . ($key + 1);
                $template_string_kel_htb = 'kel_htb#' . ($key + 1);
                $template_string_kec_htb = 'kec_htb#' . ($key + 1);
                $template_string_kab_kot_htb = 'kab_kot_htb#' . ($key + 1);
                $template_string_prov_htb = 'prov_htb#' . ($key + 1);

                $template_string_luas_tanah_htb = 'luas_tanah_htb#' . ($key + 1);
                $template_string_luas_bangunan_htb = 'luas_bangunan_htb#' . ($key + 1);

                $template_string_jenis_bukti_htb = 'jenis_bukti_htb#' . ($key + 1);
                $template_string_nomor_bukti_htb = 'nomor_bukti_htb#' . ($key + 1);
                $template_string_atas_nama_htb = 'atas_nama_htb#' . ($key + 1);
                $template_string_asal_usul_htb = 'asal_usul_htb#' . ($key + 1);
                $template_string_pemanfaatan_htb = 'pemanfaatan_htb#' . ($key + 1);

                $template_string_nilai_lama_htb = 'nilai_lama_htb#' . ($key + 1);
                $template_string_nilai_pelaporan_htb = 'nilai_pelaporan_htb#' . ($key + 1);
                $template_string_keterangan_htb = 'keterangan_htb#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_htb, ($key + 1));
                $this->lwphpword->set_value($template_string_jalan_htb, $this->__is_cetak_var_not_blank($row->JALAN, "-"));
                $this->lwphpword->set_value($template_string_kel_htb, $this->__is_cetak_var_not_blank($row->KEL, "-"));
                $this->lwphpword->set_value($template_string_kec_htb, $this->__is_cetak_var_not_blank($row->KEC, "-"));
                $this->lwphpword->set_value($template_string_kab_kot_htb, $this->__is_cetak_var_not_blank($row->KAB_KOT, "-"));
                $this->lwphpword->set_value($template_string_prov_htb, $this->__is_cetak_var_not_blank($row->PROV, "-") . ' / ' . $this->__is_cetak_var_not_blank($row->NAMA_NEGARA, "-"));

                $this->lwphpword->set_value($template_string_luas_tanah_htb, $this->__is_cetak_var_not_blank($row->LUAS_TANAH, "-"));
                $this->lwphpword->set_value($template_string_luas_bangunan_htb, $this->__is_cetak_var_not_blank($row->LUAS_BANGUNAN, "-"));

                $this->lwphpword->set_value($template_string_jenis_bukti_htb, $this->__is_cetak_var_not_blank($row->JENIS_BUKTI_HARTA, "-"));
                $this->lwphpword->set_value($template_string_nomor_bukti_htb, $this->__is_cetak_var_not_blank($row->NOMOR_BUKTI, "-"));
                $this->lwphpword->set_value($template_string_atas_nama_htb, $this->__get_cetak_atas_nama($row->ATAS_NAMA));
                $this->lwphpword->set_value($template_string_asal_usul_htb, $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"));
                $this->lwphpword->set_value($template_string_pemanfaatan_htb, $this->__is_cetak_var_not_blank($this->__get_pemanfaatan_harta($row->PEMANFAATAN), "-"));

//                $this->lwphpword->set_value($template_string_nilai_lama_htb, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_lama_htb, 'Rp. ' . number_rupiah($row->NILAI_LAMA));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_htb, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_htb, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    protected function __arrange_cetak_data_harta_bergerak($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_hb', 0);
        } else {
            $this->lwphpword->clone_row('no_hb', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_hb = 'no_hb#' . ($key + 1);

                $template_string_jenis_harta_hb = 'jenis_harta_hb#' . ($key + 1);
                $template_string_merek_hb = 'merek_hb#' . ($key + 1);
                $template_string_model_hb = 'model_hb#' . ($key + 1);
                $template_string_tahun_pembuatan_hb = 'tahun_pembuatan_hb#' . ($key + 1);
                $template_string_nopol_registrasi_hb = 'nopol_registrasi_hb#' . ($key + 1);

                $template_string_jenis_bukti_hb = 'jenis_bukti_hb#' . ($key + 1);
                $template_string_asal_usul_harta_hb = 'asal_usul_harta_hb#' . ($key + 1);
                $template_string_atas_nama_hb = 'atas_nama_hb#' . ($key + 1);
                $template_string_pemanfaatan_harta_hb = 'pemanfaatan_harta_hb#' . ($key + 1);
                $template_string_ket_lainnya_hb = 'ket_lainnya_hb#' . ($key + 1);

                $template_string_nilai_lama_hb = 'nilai_lama_hb#' . ($key + 1);
                $template_string_nilai_pelaporan_hb = 'nilai_pelaporan_hb#' . ($key + 1);
                $template_string_keterangan_hb = 'keterangan_hb#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hb, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_harta_hb, $this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-"));
                $this->lwphpword->set_value($template_string_merek_hb, $this->__is_cetak_var_not_blank($row->MEREK, "-"));
                $this->lwphpword->set_value($template_string_model_hb, $this->__is_cetak_var_not_blank($row->MODEL, "-"));
                $this->lwphpword->set_value($template_string_tahun_pembuatan_hb, $this->__is_cetak_var_not_blank($row->TAHUN_PEMBUATAN, "-"));
                $this->lwphpword->set_value($template_string_nopol_registrasi_hb, $this->__is_cetak_var_not_blank($row->NOPOL_REGISTRASI, "-"));

                $this->lwphpword->set_value($template_string_jenis_bukti_hb, $this->__is_cetak_var_not_blank($row->N_JENIS_BUKTI, "-"));
                $this->lwphpword->set_value($template_string_asal_usul_harta_hb, $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"));
                $this->lwphpword->set_value($template_string_atas_nama_hb, $this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama($row->ATAS_NAMA), "-"));
                $this->lwphpword->set_value($template_string_pemanfaatan_harta_hb, $this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-"));
                $this->lwphpword->set_value($template_string_ket_lainnya_hb, $this->__is_cetak_var_not_blank($row->KET_LAINNYA, "-"));

//                $this->lwphpword->set_value($template_string_nilai_lama_hb, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_lama_hb, 'Rp. ' . number_rupiah($row->NILAI_LAMA));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_hb, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_hb, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    protected function __arrange_cetak_data_harta_bergerak_lain($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_hbl', 0);
        } else {
            $this->lwphpword->clone_row('no_hbl', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_hbl = 'no_hbl#' . ($key + 1);

                $template_string_jenis_harta_hbl = 'jenis_harta_hbl#' . ($key + 1);
                $template_string_jumlah_hbl = 'jumlah_hbl#' . ($key + 1);
                $template_string_satuan_hbl = 'satuan_hbl#' . ($key + 1);
                $template_string_keterangan_lainnya_hbl = 'keterangan_lainnya_hbl#' . ($key + 1);

                $template_string_asal_usul_harta_hbl = 'asal_usul_harta_hbl#' . ($key + 1);
                $template_string_nilai_lama_hbl = 'nilai_lama_hbl#' . ($key + 1);
                $template_string_nilai_pelaporan_hbl = 'nilai_pelaporan_hbl#' . ($key + 1);
                $template_string_keterangan_hbl = 'keterangan_hbl#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hbl, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_harta_hbl, $this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-"));
                $this->lwphpword->set_value($template_string_jumlah_hbl, $this->__is_cetak_var_not_blank($row->JUMLAH, "-"));
                $this->lwphpword->set_value($template_string_satuan_hbl, $this->__is_cetak_var_not_blank($row->SATUAN, "-"));
                $this->lwphpword->set_value($template_string_keterangan_lainnya_hbl, $this->__is_cetak_var_not_blank($row->KETERANGAN, "-"));

                $this->lwphpword->set_value($template_string_asal_usul_harta_hbl, $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"));
//                $this->lwphpword->set_value($template_string_nilai_lama_hbl, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_lama_hbl, 'Rp. ' . number_rupiah($row->NILAI_LAMA));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_hbl, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_hbl, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    protected function __arrange_cetak_data_surat_berharga($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_hsb', 0);
        } else {
            $this->lwphpword->clone_row('no_hsb', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_hsb = 'no_hsb#' . ($key + 1);

                $template_string_nama_sb = 'nama_sb#' . ($key + 1);
                $template_string_atas_nama_sb = 'atas_nama_sb#' . ($key + 1);
                $template_string_nama_penerbit_sb = 'nama_penerbit_sb#' . ($key + 1);
                $template_string_custodian_sb = 'custodian_sb#' . ($key + 1);

                $template_string_nomor_rekening_sb = 'nomor_rekening_sb#' . ($key + 1);
                $template_string_asal_usul_harta_sbb = 'asal_usul_harta_sb#' . ($key + 1);
                $template_string_nilai_lama_sb = 'nilai_lama_sb#' . ($key + 1);
                $template_string_nilai_pelaporan_sb = 'nilai_pelaporan_sb#' . ($key + 1);
                $template_string_keterangan_sb = 'keterangan_sb#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hsb, ($key + 1) == NULL ? '1' : ($key + 1));
                $this->lwphpword->set_value($template_string_nama_sb, $row->NAMA == NULL ? '-' : $row->NAMA);
                $this->lwphpword->set_value($template_string_atas_nama_sb, $this->__is_cetak_var_not_blank($row->ATAS_NAMA, "-"));
                $this->lwphpword->set_value($template_string_nama_penerbit_sb, $this->__is_cetak_var_not_blank($row->NAMA_PENERBIT, "-"));
                $this->lwphpword->set_value($template_string_custodian_sb, $this->__is_cetak_var_not_blank($row->CUSTODIAN, "-"));
                //echo $template_string_nomor_rekening_sb.", ".$this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-")."<br />";
                $this->lwphpword->set_value($template_string_nomor_rekening_sb, $this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-"));
                $this->lwphpword->set_value($template_string_asal_usul_harta_sbb, $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"));
//                $this->lwphpword->set_value($template_string_nilai_lama_sb, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_lama_sb, 'Rp. ' . number_rupiah($row->NILAI_LAMA));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_sb, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_sb, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
            //exit;
        }
    }

    protected function __arrange_cetak_data_kas_dan_setara_kas($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_hksk', 0);
        } else {
            $this->lwphpword->clone_row('no_hksk', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_hksk = 'no_hksk#' . ($key + 1);

                $template_string_nama_hkskb = 'nama_hksk#' . ($key + 1);
                $template_string_keterangan_uraian_hksk = 'keterangan_uraian_hksk#' . ($key + 1);
                $template_string_nama_bank_hksk = 'nama_bank_hksk#' . ($key + 1);

                $template_string_nomor_rekening_hksk = 'nomor_rekening_hksk#' . ($key + 1);
                $template_string_atas_nama_rekening_hksk = 'atas_nama_rekening_hksk#' . ($key + 1);
                $template_string_keterangan_rekekning_hksk = 'keterangan_rekekning_hksk#' . ($key + 1);

                $template_string_asal_usul_harta_hksk = 'asal_usul_harta_hksk#' . ($key + 1);
                $template_string_nilai_equivalen_hksk = 'nilai_equivalen_hksk#' . ($key + 1);
                $template_string_keterangan_hksk = 'keterangan_hksk#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hksk, ($key + 1));
                $this->lwphpword->set_value($template_string_nama_hkskb, $this->__is_cetak_var_not_blank($row->NAMA, "-"));
                $this->lwphpword->set_value($template_string_keterangan_uraian_hksk, $this->__is_cetak_var_not_blank(trim($row->KETERANGAN), "-"));
                $this->lwphpword->set_value($template_string_nama_bank_hksk, $this->__is_cetak_var_not_blank($row->NAMA_BANK, "-"));

                $this->lwphpword->set_value($template_string_nomor_rekening_hksk, $this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-"));
                $this->lwphpword->set_value($template_string_atas_nama_rekening_hksk, $this->__is_cetak_var_not_blank($row->ATAS_NAMA_REKENING, "-"));
                $this->lwphpword->set_value($template_string_keterangan_rekekning_hksk, $this->__is_cetak_var_not_blank($row->KETERANGAN, "-"));

                $this->lwphpword->set_value($template_string_asal_usul_harta_hksk, $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"));
                $this->lwphpword->set_value($template_string_nilai_equivalen_hksk, 'Rp. ' . number_rupiah($row->NILAI_EQUIVALEN));
                $this->lwphpword->set_value($template_string_keterangan_hksk, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    protected function __arrange_cetak_data_harta_lainnya($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_hl', 0);
        } else {
            $this->lwphpword->clone_row('no_hl', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_hl = 'no_hl#' . ($key + 1);

                $template_string_nama_jenis_hl = 'nama_jenis_hl#' . ($key + 1);
                $template_string_keterangan_uraian_hl = 'keterangan_uraian_hl#' . ($key + 1);

                $template_string_asal_usul_harta_hl = 'asal_usul_harta_hl#' . ($key + 1);
                $template_string_nilai_lama_hl = 'nilai_lama_hl#' . ($key + 1);
                $template_string_nilai_pelaporan_hl = 'nilai_pelaporan_hl#' . ($key + 1);
                $template_string_keterangan_hl = 'keterangan_hl#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hl, ($key + 1));
                $this->lwphpword->set_value($template_string_nama_jenis_hl, $this->__is_cetak_var_not_blank($row->NAMA_JENIS, "-"));
                $this->lwphpword->set_value($template_string_keterangan_uraian_hl, $this->__is_cetak_var_not_blank($row->KETERANGAN, "-"));

                $this->lwphpword->set_value($template_string_asal_usul_harta_hl, $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"));
//                $this->lwphpword->set_value($template_string_nilai_lama_hl, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_lama_hl, 'Rp. ' . number_rupiah($row->NILAI_LAMA));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_hl, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_hl, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    protected function __arrange_cetak_data_hutang($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_hh', 0);
        } else {
            $this->lwphpword->clone_row('no_hh', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_hh = 'no_hh#' . ($key + 1);

                $template_string_nama_hh = 'nama_hh#' . ($key + 1);
                $template_string_atas_nama_hh = 'atas_nama_hh#' . ($key + 1);
                $template_string_nama_kreditur_hh = 'nama_kreditur_hh#' . ($key + 1);
                $template_string_agunan_hh = 'agunan_hh#' . ($key + 1);
                $template_string_awal_hutang_hh = 'awal_hutang_hh#' . ($key + 1);
                $template_string_saldo_hutang_hh = 'saldo_hutang_hh#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hh, ($key + 1));
                $this->lwphpword->set_value($template_string_nama_hh, $this->__is_cetak_var_not_blank($row->NAMA, "-"));
                $this->lwphpword->set_value($template_string_atas_nama_hh, $this->__is_cetak_var_not_blank($row->ATAS_NAMA, "-"));
                $this->lwphpword->set_value($template_string_nama_kreditur_hh, $this->__is_cetak_var_not_blank($row->NAMA_KREDITUR, "-"));
                $this->lwphpword->set_value($template_string_agunan_hh, $this->__is_cetak_var_not_blank($row->AGUNAN, "-"));
                $this->lwphpword->set_value($template_string_awal_hutang_hh, 'Rp. ' . number_rupiah($row->AWAL_HUTANG));
                $this->lwphpword->set_value($template_string_saldo_hutang_hh, 'Rp. ' . number_rupiah($row->SALDO_HUTANG));
            }
        }
    }

    protected function __arrange_cetak_data_penerimaan($data, $is_copy) {
        $C_PENERIMAAN = array();
        $C_PENERIMAAN[0] = 0;
        $C_PENERIMAAN[1] = 0;
        $C_PENERIMAAN[2] = 0;
        $PN_A = array();
        $PN_B = array();
        $PN_ELSE = array();

        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'A') {
                $C_PENERIMAAN[0] += 1;
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                    "PASANGAN" => $PM->PASANGAN,
                ];
            } else if ($PM->GROUP_JENIS == 'B') {
                $C_PENERIMAAN[1] += 1;
                $PN_B[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                ];
            } else {
                $C_PENERIMAAN[2] += 1;
                $PN_ELSE[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                ];
            }
        }

        if ($C_PENERIMAAN[0] >= 1) {
            $this->lwphpword->clone_row('nopna', $C_PENERIMAAN[0]);
            foreach ($PN_A as $key => $row) {
                $template_string_nopna = 'nopna#' . ($key + 1);

                $template_string_jenis_penerimaan_a = 'jenis_penerimaan_a#' . ($key + 1);
                $template_string_pn_a = 'pn_a#' . ($key + 1);
                $template_string_pasangan_a = 'pasangan_a#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopna, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_penerimaan_a, $row["JENIS_PENERIMAAN"]);
                $this->lwphpword->set_value($template_string_pn_a, 'Rp. ' . number_rupiah($row["PN"]));
                $this->lwphpword->set_value($template_string_pasangan_a, 'Rp. ' . number_rupiah($row["PASANGAN"]));
            }
        } else {
            $this->lwphpword->clone_row('nopna', 0);
            //$this->lwphpword->deleteBlock('nopna', 0);
        }
        //exit;
        if ($C_PENERIMAAN[1] >= 1) {
            $this->lwphpword->clone_row('nopnb', $C_PENERIMAAN[1]);
            foreach ($PN_B as $key => $row) {
                $template_string_nopnb = 'nopnb#' . ($key + 1);
                $template_string_jenis_penerimaan_b = 'jenis_penerimaan_b#' . ($key + 1);
                $template_string_pn_b = 'pn_b#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopnb, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_penerimaan_b, $row["JENIS_PENERIMAAN"]);
                $this->lwphpword->set_value($template_string_pn_b, 'Rp. ' . number_rupiah($row["PN"]));
            }
        } else {
            $this->lwphpword->clone_row('nopnb', 0);
        }

        if ($C_PENERIMAAN[2] >= 1) {
            $this->lwphpword->clone_row('nopnc', $C_PENERIMAAN[2]);
            foreach ($PN_ELSE as $key => $row) {
                $template_string_nopnc = 'nopnc#' . ($key + 1);
                $template_string_jenis_penerimaan_c = 'jenis_penerimaan_c#' . ($key + 1);
                $template_string_pn_c = 'pn_c#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopnc, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_penerimaan_c, $row["JENIS_PENERIMAAN"]);
                $this->lwphpword->set_value($template_string_pn_c, 'Rp. ' . number_rupiah($row["PN"]));
            }
        } else {
            $this->lwphpword->remove_table_row('nopnc');
        }
    }

    protected function __arrange_cetak_data_pengeluaran($data, $is_copy) {
        $PENGELUARAN = $data;
        $C_PENGELUARAN = array();
        $C_PENGELUARAN[0] = 0;
        $C_PENGELUARAN[1] = 0;
        $C_PENGELUARAN[2] = 0;

        $CP_A = [];
        $CP_B = [];
        $CP_ELSE = [];
        foreach ($data as $PNG) {

            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }

            if ($PNG->GROUP_JENIS == 'A') {
                $C_PENGELUARAN[0] += 1;
                $CP_A[] = $PNG;
            } else if ($PNG->GROUP_JENIS == 'B') {
                $C_PENGELUARAN[1] += 1;
                $CP_B[] = $PNG;
            } else {
                $C_PENGELUARAN[2] += 1;
                $CP_ELSE[] = $PNG;
            }
        }

        if ($C_PENGELUARAN[0] >= 1) {
            $this->lwphpword->clone_row('nopppr', $C_PENGELUARAN[0]);
            foreach ($CP_A as $key => $row) {

                $template_string_nopppr = 'nopppr#' . ($key + 1);

                $template_string_pppr_jen = 'pppr_jen#' . ($key + 1);
                $template_string_pppr_jml = 'pppr_jml#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopppr, ($key + 1));
                $this->lwphpword->set_value($template_string_pppr_jen, $row->JENIS_PENGELUARAN);
                $this->lwphpword->set_value($template_string_pppr_jml, 'Rp. ' . number_rupiah($row->JML));
            }
        } else {
            $this->lwphpword->remove_table_row('nopppr');
        }

        if ($C_PENGELUARAN[1] >= 1) {
            $this->lwphpword->clone_row('noppph', $C_PENGELUARAN[1]);
            foreach ($CP_B as $key => $row) {
                $template_string_noppph = 'noppph#' . ($key + 1);

                $template_string_ppph_jen = 'ppph_jen#' . ($key + 1);
                $template_string_ppph_jml = 'ppph_jml#' . ($key + 1);

                $this->lwphpword->set_value($template_string_noppph, ($key + 1));
                $this->lwphpword->set_value($template_string_ppph_jen, $row->JENIS_PENGELUARAN);
                $this->lwphpword->set_value($template_string_ppph_jml, 'Rp. ' . number_rupiah($row->JML));
            }
        } else {
            $this->lwphpword->remove_table_row('noppph');
        }

        if ($C_PENGELUARAN[2] >= 1) {
            $this->lwphpword->clone_row('nopppl', $C_PENGELUARAN[2]);
            foreach ($CP_ELSE as $key => $row) {
                $template_string_nopppl = 'nopppl#' . ($key + 1);

                $template_string_pppl_jen = 'pppl_jen#' . ($key + 1);
                $template_string_pppl_jml = 'pppl_jml#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopppl, ($key + 1));
                $this->lwphpword->set_value($template_string_pppl_jen, $row->JENIS_PENGELUARAN);
                $this->lwphpword->set_value($template_string_pppl_jml, 'Rp. ' . number_rupiah($row->JML));
            }
        } else {
            $this->lwphpword->remove_table_row('nopppl');
        }
    }

    protected function __arrange_cetak_data_lampiran_fasilitas($data, $is_copy) {
        $jumlah_data = count($data);
        if ($jumlah_data < 0) {
            $this->lwphpword->clone_row('no_fls', 0);
        } else {
            $this->lwphpword->clone_row('no_fls', $jumlah_data);

            foreach ($data as $key => $row) {
                $template_string_no_fls = 'no_fls#' . ($key + 1);

                $template_string_jenis_fasilitas_fls = 'jenis_fasilitas_fls#' . ($key + 1);
                $template_string_keterangan_uraian_fls = 'keterangan_uraian_fls#' . ($key + 1);
                $template_string_pemberi_fasilitas_fls = 'pemberi_fasilitas_fls#' . ($key + 1);
                $template_string_keterangan_fls = 'keterangan_fls#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_fls, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_fasilitas_fls, $this->__is_cetak_var_not_blank($row->JENIS_FASILITAS, "-"));
                $this->lwphpword->set_value($template_string_keterangan_uraian_fls, $this->__is_cetak_var_not_blank($row->KETERANGAN, "-"));
                $this->lwphpword->set_value($template_string_pemberi_fasilitas_fls, $this->__is_cetak_var_not_blank($row->PEMBERI_FASILITAS, "-"));
                $this->lwphpword->set_value($template_string_keterangan_fls, $this->__is_cetak_var_not_blank($row->KETERANGAN, "-"));
            }
        }
    }

    function cetak_iktisar_validation($id_imp_xl_lhkpn = FALSE) {

        $data_imp_xl_lhkpn = $this->imp_xl_lhkpn($id_imp_xl_lhkpn);

        $this->is_validation = TRUE;
        $this->field_id_lhkpn = 'id_imp_xl_lhkpn';
        $this->table_lhkpn = 't_imp_xl_lhkpn';

        if ($id_imp_xl_lhkpn && $data_imp_xl_lhkpn) {
//            $data = $this->__get_data_cetak($id_imp_xl_lhkpn);
            $data = $this->__get_data_cetak_imp_xl_lhkpn($id_imp_xl_lhkpn);

            $data['LHKPN'] = $data_imp_xl_lhkpn;
        }
        return $data;
    }

    public function cetak_draft($id_lhkpn){
        $this->load->model('mglobal');
    
        $join = [
          ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_AUDIT.ID_LHKPN'],
          ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
          ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
          ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = M_JABATAN.INST_SATKERKD']
          ,
          ['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
          ['table' => 'T_LHKPN T_LHKPN2', 'on' => 'T_LHKPN2.ID_LHKPN_PREV = T_LHKPN_AUDIT.ID_LHKPN']
        ];
    
        $where = [
          'T_LHKPN.ID_LHKPN' => $id_lhkpn,
          'T_LHKPN_AUDIT.IS_ACTIVE' => '1',
        ];
    
        $data_lhkpn = $this->mglobal->get_data_all('T_LHKPN_AUDIT', $join, $where, 't_lhkpn.ID_LHKPN,  t_lhkpn2.ID_LHKPN_PREV,  t_lhkpn2.TGL_KLARIFIKASI AS TGL_KLARIF,t_pn.NAMA AS NAMA_LENGKAP, 
        m_jabatan.NAMA_JABATAN AS JABATAN, m_jabatan.INST_SATKERKD AS LEMBAGA, m_inst_satker.INST_NAMA, t_lhkpn_audit.tgl_selesai_periksa, t_lhkpn_audit.id_pemeriksa, t_lhkpn.TGL_KLARIFIKASI, t_lhkpn_audit.id_pic', NULL, ['ID_AUDIT DESC'], 0, NULL, 't_lhkpn.ID_LHKPN, t_lhkpn_audit.nomor_surat_tugas, id_pic');
        
        $now = tgl_format(date("Y-m-d"));
        $data_lhkpn = $data_lhkpn[0];
        $tgl_periksa = $data_lhkpn->tgl_selesai_periksa;
        $tgl_periksa_exp = explode("-",$tgl_periksa);
        $thn_periksa = $tgl_periksa_exp[0];
        
        $data_pic = $this->get_data_pic($data_lhkpn->id_pic);
        $nama_pic = $data_pic->NAMA;
        $no_hp_pic = $data_pic->HANDPHONE;
        $email_pic = $data_pic->EMAIL;
        $data_jabatan = $this->jabatan($id_lhkpn);
        $jabatan_nama = $data_jabatan[0]->NAMA_JABATAN;
        $suk_nama = $data_jabatan[0]->SUK_NAMA;
        $uk_nama = $data_jabatan[0]->UK_NAMA;
    
        $this->load->library('lwphpword/lwphpword', array(
          "base_path" => APPPATH . "../file/wrd_gen/",
          "base_url" => base_url() . "file/wrd_gen/",
          "base_root" => base_url(),
        ));
    
        $template_file = "../file/template/SuratPemberitahuanHasilKlarifikasi.docx";
    
        $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);
    
        if (!$load_template_success) {
            throw new Exception("Gagal Mencetak Data.");
            exit;
        }
    
        $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";
    
        if($load_template_success){
            $this->lwphpword->set_value("TANGGAL_NOW", $now);
            $this->lwphpword->set_value("BULAN", Date("m"));
            $this->lwphpword->set_value("TAHUN", Date("Y"));
            $this->lwphpword->set_value("NAMA_LENGKAP", $data_lhkpn->NAMA_LENGKAP);
            $this->lwphpword->set_value("JABATAN", $jabatan_nama);
            $this->lwphpword->set_value("SUK", isset($suk_nama)?$suk_nama:'-');
            $this->lwphpword->set_value("UK", $uk_nama);
            $this->lwphpword->set_value("INSTANSI", $data_lhkpn->INST_NAMA);
            $this->lwphpword->set_value("TAHUN_PERIKSA", $thn_periksa);
            $this->lwphpword->set_value("TGL_KLARIF", isset($data_lhkpn->TGL_KLARIF)?tgl_format($data_lhkpn->TGL_KLARIF): "-");
            $this->lwphpword->set_value("NAMA_PIC_RIKSA", isset($nama_pic)?$nama_pic: "-");
            $this->lwphpword->set_value("NO_HP_PIC_RIKSA", isset($no_hp_pic)?$no_hp_pic: "-");
            $this->lwphpword->set_value("EMAIL_PIC_RIKSA", isset($email_pic)?$email_pic: "-");
            
            $save_document_success = $this->lwphpword->save_document();
            if ($save_document_success) {
                $output_filename = "DraftSurat" . date('d-F-Y H:i:s') . $id_lhkpn;
                $this->lwphpword->download($save_document_success, $output_filename);
            }
            unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
        }
    }

    private function get_data_pic($id_pic) {
        $this->db->select('t_user.*');
        $this->db->from('t_user');
        $this->db->where('id_user =', $id_pic);
        $query = $this->db->get();
        $res = $query->result();
        if (!empty($res)) {
          return $res[0];
        } else {
          return '-';
        }    
    }

    function cetak($ID_LHKPN = FALSE, $is_validation = FALSE, $send_session = NULL) {
        $this->load->model('mlhkpnkeluarga');
        $cek_id_user = FALSE;
        $data = array();
        if ($is_validation) {
            $data = $this->cetak_iktisar_validation($ID_LHKPN);
            $cek_id_user = TRUE;
        } else {
            $cek_id_user = $this->lhkpn($ID_LHKPN);
        }

        $id_is_ok_and_ready_print = $this->is_ever ? (bool) $ID_LHKPN : ($ID_LHKPN && $cek_id_user);

//        if ($ID_LHKPN && $cek_id_user) {
        if ($id_is_ok_and_ready_print) {

            $lhkpn_ver = FALSE;
            if ($this->is_ever) {
                $lhkpn_ver = $this->mlhkpnkeluarga->get_lhkpn_version($ID_LHKPN);
            }

            if (!$is_validation) {
                $data = $this->__get_data_cetak($ID_LHKPN);
            }
            $is_copy = NULL;

            if ($data['LHKPN'] && property_exists($data['LHKPN'], 'IS_COPY')) {
                $is_copy = $data['LHKPN']->IS_COPY;
            }   

            list($BIDANG, $LEMBAGA, $JABATAN, $SUK, $UK) = $this->__set_data_cetak_jabatan($data['JABATAN']);

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/IkhtisarLHKPNTemplate.docx";

            /*
              $_temp_dir = FALSE, $_model_qr = FALSE, $_callable_model_function = FALSE
             */
            /*
             * penulisan qrcode

              $this->load->library('lws_qr', [
              "model_qr" => "Cqrcode",
              "callable_model_function" => "insert_cqrcode_with_filename"
              ]);
             */
            $this->_cnt_nilai_pelaporan_harta_bergerak($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_tidak_bergerak($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_bergerak_lain($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_surat_berharga($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_kas($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_lainnya($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_hutang($ID_LHKPN);
            $this->_cnt_penerimaan_gaji($ID_LHKPN);
            $this->_cnt_penerimaan_usaha($ID_LHKPN);
            $this->_cnt_penerimaan_lainnya($ID_LHKPN);
            $this->_cnt_pengeluaran_rutin($ID_LHKPN);
            $this->_cnt_pengeluaran_harta($ID_LHKPN);
            $this->_cnt_pengeluaran_lainnya($ID_LHKPN);


            $total_harta = $this->jumlah_htb + $this->jumlah_hb + $this->jumlah_hbl + $this->jumlah_sb +$this->jumlah_kas + $this->jumlah_hl;
            $total_hutang = $this->jumlah_shtg;
            $total_harta_kekayaan = $total_harta - $total_hutang;

            $catatan = "Rincian harta kekayaan dalam ikhtisar LHKPN merupakan dokumen yang dicetak secara otomatis dari elhkpn.kpk.go.id. Seluruh data dan informasi yang tercantum dalam Dokumen ini sesuai dengan LHKPN yang diisi dan dikirimkan sendiri oleh Penyelenggara Negara yang bersangkutan melalui elhkpn.kpk.go.id serta tidak dapat dijadikan dasar oleh Penyelenggara Negara atau siapapun juga untuk menyatakan bahwa harta yang bersangkutan tidak terkait tindak pidana. \n\nApabila dikemudian hari terdapat harta kekayaan milik Penyelenggara Negara dan/atau Keluarganya yang tidak dilaporkan dalam LHKPN, maka Penyelenggara Negara wajib untuk bertanggung jawab sesuai dengan peraturan perundang-undangan yang berlaku.";

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => "PHK-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
		"temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            $qr_content_data = json_encode((object) [
                "data" => [
                    (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $data['PRIBADI']->NAMA_LENGKAP],
                    (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data['PRIBADI']->NHK == NULL ? '-' : $data['PRIBADI']->NHK],
                    (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $BIDANG],
                    (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $JABATAN."-".$SUK."-".$UK],
                    (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $LEMBAGA],
                    (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($data['LHKPN']->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus') . " - " . show_jenis_laporan_khusus($data['LHKPN']->JENIS_LAPORAN, $data['LHKPN']->tgl_lapor, tgl_format($data['LHKPN']->tgl_lapor))],
                    (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($data['LHKPN']->tgl_lapor)],
                    (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-")],
                    (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-")],
                    (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-")],
                    (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-")],
                    (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-")],
                    (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-")],
                    (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_hutang), "-")],
                    (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta_kekayaan), "-")],
                    (object) ["tipe" => '1', "judul" => "Catatan", "isi" => $catatan],
                ],
                "encrypt_data" => $data['PRIBADI']->ID_LHKPN . "phk",
                "id_lhkpn" => $ID_LHKPN,
                "judul" => "Pengumuman/Ikhtisar Harta Kekayaan Penyelenggara Negara",
                "tgl_surat" => date('Y-m-d'),
            ]);

             $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $data['LHKPN']->ID_LHKPN . ".png");

            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));


//             $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            if (!$load_template_success) {
                throw new Exception("Gagal Mencetak Data.");
                exit;
            }
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {

                /**
                 * DATA PRIBADI
                 */
                if ($data['PRIBADI']->JENIS_KELAMIN != ''){
                    if ($data['PRIBADI']->JENIS_KELAMIN == '1') {
                        $jenkel = 'Laki Laki';
                    }
                    elseif ($data['PRIBADI']->JENIS_KELAMIN == '2') {
                        $jenkel = 'Perempuan';
                    }
                    else{
                        $jenkel = $data['PRIBADI']->JENIS_KELAMIN;
                    }
                }

                $alamat_rumah = $this->__is_cetak_var_not_blank($data['PRIBADI']->ALAMAT_RUMAH, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->KECAMATAN, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->KABKOT, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->PROVINSI, "-");
                $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NAMA_LENGKAP, "-"));
                $this->lwphpword->set_value("NHK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NHK, "-"));
                $this->lwphpword->set_value("NIK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NIK, "-"));
                $this->lwphpword->set_value("KK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NO_KK, "-"));
                $this->lwphpword->set_value("NPWP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NPWP, "-"));
                $this->lwphpword->set_value("JK", $this->__is_cetak_var_not_blank($jenkel, "-"));
                $this->lwphpword->set_value("TTL", $this->__is_cetak_var_not_blank($data['PRIBADI']->TEMPAT_LAHIR, "-").'/'.$this->__is_cetak_var_not_blank(tgl_format($data['PRIBADI']->TANGGAL_LAHIR), "-"));
                $this->lwphpword->set_value("STATUS", $this->__is_cetak_var_not_blank($data['PRIBADI']->STATUS_PERKAWINAN, "-"));
                $this->lwphpword->set_value("AGAMA", $this->__is_cetak_var_not_blank($data['PRIBADI']->AGAMA, "-"));
                $this->lwphpword->set_value("ALAMAT", $this->__is_cetak_var_not_blank($alamat_rumah, "-"));
                $this->lwphpword->set_value("NOHP", $this->__is_cetak_var_not_blank($data['PRIBADI']->HP, "-"));
                $this->lwphpword->set_value("EMAIL", $this->__is_cetak_var_not_blank($data['PRIBADI']->EMAIL_PRIBADI, "-"));
                $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($BIDANG, "-"));
                $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($LEMBAGA, "-"));
                $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($JABATAN, "-"));
                $this->lwphpword->set_value("SUK", $this->__is_cetak_var_not_blank($SUK, "-"));
                $this->lwphpword->set_value("UK", $this->__is_cetak_var_not_blank($UK, "-"));


                $this->lwphpword->set_value("TGL_LAPOR", $this->__is_cetak_var_not_blank(tgl_format($data['LHKPN']->tgl_lapor), "-"));
                $this->lwphpword->set_value("TGL_KIRIM", $this->__is_cetak_var_not_blank(tgl_format($data['LHKPN']->tgl_kirim_final), "-"));

                $tgl_cetak = date('d/m/Y');
                $thn_lapor = date_format(date_create($data['LHKPN']->tgl_lapor),'Y');
                if($data['LHKPN']->JENIS_LAPORAN==1){
                    $jenis_laporan = "Khusus (Calon PN)";
                }elseif($data['LHKPN']->JENIS_LAPORAN==2){
                    $jenis_laporan = "Khusus (Awal Menjabat)";
                }elseif($data['LHKPN']->JENIS_LAPORAN==3){
                    $jenis_laporan = "Khusus (Akhir Menjabat)";
                }elseif($data['LHKPN']->JENIS_LAPORAN==4){
                    $jenis_laporan = "Periodik";
                }else{
                    $jenis_laporan = "-";
                }

                if($data['LHKPN']->STATUS==0){
                    $status_lhkpn = "Draft";
                }elseif($data['LHKPN']->STATUS==1){
                    $status_lhkpn = "Proses Verifikasi";
                }elseif($data['LHKPN']->STATUS==2){
                    $status_lhkpn = "Perlu Perbaikan";
                }elseif($data['LHKPN']->STATUS==3){
                    $status_lhkpn = "Terverifikasi Lengkap";
                }elseif($data['LHKPN']->STATUS==4){
                    $status_lhkpn = "Diumumkan Lengkap";
                }elseif($data['LHKPN']->STATUS==5){
                    $status_lhkpn = "Terverifikasi Tidak Lengkap";
                }elseif($data['LHKPN']->STATUS==6){
                    $status_lhkpn = "Diumumkan Tidak Lengkap";
                }elseif($data['LHKPN']->STATUS==7){
                    $status_lhkpn = "Ditolak";
                }else{
                    $status_lhkpn = "-";
                }

                $this->lwphpword->set_value("STATUS_LHKPN", $status_lhkpn);
                $this->lwphpword->set_value("JENIS_LAPORAN", $jenis_laporan);
                $this->lwphpword->set_value("TAHUN_LAPOR", $this->__is_cetak_var_not_blank($thn_lapor, "-"));
                $this->lwphpword->set_value("HEADER_TAHUN_LAPOR", $this->__is_cetak_var_not_blank($thn_lapor, "-"));
                $this->lwphpword->set_value("TGL_CETAK", $tgl_cetak);


                ////////////////////////////////DATA LAMA///////////////////////////////////////

//                 /**
//                  * DATA KELUARGA
//                  */
//                 $this->__arrange_cetak_data_keluarga($data['KELUARGA'], $lhkpn_ver);
//                 /**
//                  * DATA JABATAN
//                  */
//                 $this->__arrange_cetak_data_jabatan($data['JABATAN']);
//                 /**
//                  * DATA HARTA TIDAK BERGERAK
//                  */
//                 $this->__arrange_cetak_data_harta_tidak_bergerak($data['HARTA_TDK_BEGERAK'], $is_copy);
//                 /**
//                  * DATA HARTA BERGERAK
//                  */
//                 $this->__arrange_cetak_data_harta_bergerak($data['HARTA_BERGERAK'], $is_copy);
//                 /**
//                  * DATA HARTA BERGERAK LAINNYA
//                  */
//                 $this->__arrange_cetak_data_harta_bergerak_lain($data['HARTA_BERGERAK_LAIN'], $is_copy);
//                 /**
//                  * DATA HARTA SURAT BERHARGA
//                  */
//                 $this->__arrange_cetak_data_surat_berharga($data['HARTA_SURAT_BERHARGA'], $is_copy);
//                 /**
//                  * DATA HARTA KAS DAN SETARA KAS
//                  */
//                 $this->__arrange_cetak_data_kas_dan_setara_kas($data['HARTA_KAS'], $is_copy);
//                 /**
//                  * DATA HARTA LAINNYA
//                  */
//                 $this->__arrange_cetak_data_harta_lainnya($data['HARTA_LAINNYA'], $is_copy);
//                 /**
//                  * DATA HUTANG
//                  */
//                 $this->__arrange_cetak_data_hutang($data['HUTANG'], $is_copy);
//                 /**
//                  * DATA PENERIMAAN
//                  */
//                 $this->__arrange_cetak_data_penerimaan($data['PENERIMAAN'], $is_copy);
//                 /**
//                  * DATA PENGELUARAN
//                  */
//                 $this->__arrange_cetak_data_pengeluaran($data['PENGELUARAN'], $is_copy);
//                 /**
//                  * DATA LAMPIRAN FASILITAS
//                  */
//                 $this->__arrange_cetak_data_lampiran_fasilitas($data['FASILITAS'], $is_copy);
//                 /**
//                  * JUMLAH HARTA BERGERAK
//                  */
//                 $this->lwphpword->set_value("totalHB", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-"));
//                 /**
//                  * JUMLAH HARTA TIDAK BERGERAK
//                  */
//                 $this->lwphpword->set_value("totalHTB", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-"));
//                 /**
//                  * JUMLAH HARTA BERGERAK LAIN
//                  */
//                 $this->lwphpword->set_value("totalHBL", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-"));
//                 /**
//                  * JUMLAH HARTA SURAT BERHARGA
//                  */
//                 $this->lwphpword->set_value("totalSB", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-"));
//                 /**
//                  * JUMLAH HARTA KAS
//                  */
//                 $this->lwphpword->set_value("totalKAS", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-"));
//                 /**
//                  * JUMLAH HARTA HARTA LAINNYA
//                  */
//                 $this->lwphpword->set_value("totalHL", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-"));
//                 /**
//                  * JUMLAH HARTA SALDO HUTANG
//                  */
//                 $this->lwphpword->set_value("totalSHTG", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_shtg), "-"));
//                 /**
//                  * JUMLAH HARTA AWAL HUTANG
//                  */
//                 $this->lwphpword->set_value("totalAHTG", $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ahtg), "-"));

                ////////////////////////////////DATA LAMA///////////////////////////////////////








                /**
                 * DATA KELUARGA
                 */
                $get_data_keluarga = $this->write_table_data_keluarga($data['KELUARGA'],$lhkpn_ver);
                $this->lwphpword->set_xml_value("table_data_keluarga", $get_data_keluarga);

                /**
                 * DATA JABATAN
                 */
                $get_data_jabatan = $this->write_table_data_jabatan($data['JABATAN']);
                $this->lwphpword->set_xml_value("table_data_jabatan", $get_data_jabatan);

                /**
                 * DATA HARTA TIDAK BERGERAK
                 */
                $get_data_htb = $this->write_table_data_htb($data['HARTA_TDK_BEGERAK'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-"));
                $this->lwphpword->set_xml_value("table_data_htb", $get_data_htb);

                /**
                 * DATA HARTA BERGERAK
                 */
                $get_data_hb = $this->write_table_data_hb($data['HARTA_BERGERAK'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-"));
                $this->lwphpword->set_xml_value("table_data_hb", $get_data_hb);

                /**
                 * DATA HARTA BERGERAK LAINNYA
                 */
                $get_data_hbl = $this->write_table_data_hbl($data['HARTA_BERGERAK_LAIN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-"));
                $this->lwphpword->set_xml_value("table_data_hbl", $get_data_hbl);

                /**
                 * DATA HARTA SURAT BERHARGA ------------------ (terdapat kendala di local)
                 */
                $get_data_sb = $this->write_table_data_sb($data['HARTA_SURAT_BERHARGA'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-"));
                $this->lwphpword->set_xml_value("table_data_sb", $get_data_sb);

                /**
                 * DATA HARTA KAS DAN SETARA KAS
                 */
                $get_data_kas = $this->write_table_data_kas($data['HARTA_KAS'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-"));
                $this->lwphpword->set_xml_value("table_data_kas", $get_data_kas);

                /**
                 * DATA HARTA LAINNYA
                 */
                $get_data_hl = $this->write_table_data_hl($data['HARTA_LAINNYA'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-"));
                $this->lwphpword->set_xml_value("table_data_hl", $get_data_hl);


                /**
                 * DATA HUTANG
                 */
                $get_data_htg = $this->write_table_data_htg($data['HUTANG'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ahtg), "-"),$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_shtg), "-"));
                $this->lwphpword->set_xml_value("table_data_htg", $get_data_htg);


                /**
                 * DATA PENERIMAAN GAJI
                 */
                $get_data_penerimaan_pekerjaan = $this->write_table_data_penerimaan_pekerjaan($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pg_pn), "-"),$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pg_ps), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_pekerjaan", $get_data_penerimaan_pekerjaan);

                /**
                 * DATA PENERIMAAN USAHA
                 */
                $get_data_penerimaan_usaha = $this->write_table_data_penerimaan_usaha($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pu_pn), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_usaha", $get_data_penerimaan_usaha);

                /**
                 * DATA PENERIMAAN LAINNYA
                 */
                $get_data_penerimaan_lainnya = $this->write_table_data_penerimaan_lainnya($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pl_pn), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_lainnya", $get_data_penerimaan_lainnya);

                /**
                 * DATA PENGELUARAN RUTIN
                 */
                $get_data_pengeluaran_rutin = $this->write_table_data_pengeluaran_rutin($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pr), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_rutin", $get_data_pengeluaran_rutin);

                /**
                 * DATA PENGELUARAN HARTA
                 */
                $get_data_pengeluaran_harta = $this->write_table_data_pengeluaran_harta($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ph), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_harta", $get_data_pengeluaran_harta);

                /**
                 * DATA PENGELUARAN LAINNYA
                 */
                $get_data_pengeluaran_lainnya = $this->write_table_data_pengeluaran_lainnya($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pl), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_lainnya", $get_data_pengeluaran_lainnya);


                /**
                 * DATA LAMPIRAN FASILITAS
                 */
                $get_data_lampiran_fasilitas = $this->write_table_data_lampiran_fasilitas($data['FASILITAS'],$is_copy);
                $this->lwphpword->set_xml_value("table_data_lampiran_fasilitas", $get_data_lampiran_fasilitas);

                /**
                 * DATA TOTAL HARTA KEKAYAAN
                 */
                $get_data_total_harta = $this->write_table_data_total_harta($total_harta,$total_hutang,$total_harta_kekayaan);
                $this->lwphpword->set_xml_value("table_data_total_harta", $get_data_total_harta);


                $this->lwphpword->set_value("catatan", $catatan);

                if(empty($send_session)) {
                    $save_document_success = $this->lwphpword->save_document();
                    if ($save_document_success) {
                        $output_filename = "IkhtisarHarta" . date('d-F-Y H:i:s') . $ID_LHKPN;
                        $this->lwphpword->download($save_document_success, $output_filename);
                    }
                    unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
                }
                else {
                    $download_filename = "IkhtisarHarta-" . date('dmYhi') . $ID_LHKPN. ".docx";
                    $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $download_filename);

                    if ($save_document_success) {
                        $subject = null;
                        $pesan = null;
                        // $pesan = $this->load->view('efill/lhkpn/lhkpn_ikhtisar_harta', [
                        //     "NAMA" => $data['PRIBADI']->NAMA_LENGKAP,
                        //     "NAMA_JABATAN" => $JABATAN,
                        //     "NAMA_BIDANG" => $BIDANG,
                        //     "INST_NAMA" => $LEMBAGA,
                        //     "TAHUN" => tgl_format($data['LHKPN']->tgl_kirim)
                        // ], TRUE);

                        $ikhtisar_harta = (object) $this->sess_template_data;
                        $ikhtisar_harta->id_user = $this->session->userdata('ID_USER');
                        $ikhtisar_harta->pesan = $pesan;
                        $ikhtisar_harta->subject = $subject;
                        $ikhtisar_harta->word_location = "../../../file/wrd_gen/" . $save_document_success->document_name;

                        $this->__send_session_kirim_lhkpn($ID_LHKPN, $ikhtisar_harta);
                        unset($ikhtisar_harta);
                    }
                }
            }
            $temp_dir = APPPATH."../images/qrcode/";
            $qr_image = "tes_qr2-" . $data['LHKPN']->ID_LHKPN . ".png";
            unlink($temp_dir.$qr_image);
        } else {
            redirect('portal/filing');
        }
    }

    private function __send_session_kirim_lhkpn($ID_LHKPN, $sess_template_data, $lampiran_3 = FALSE) {
        /**
         * check kemudian remove session terlebih dahulu
         * dengan maksud agar tidak tumpang tindih
         */
        if ($this->session->userdata('sess_kirim_lhkpn') && $lampiran_3) {
            $this->session->unset_userdata('sess_kirim_lhkpn');

            $this->session->set_userdata('sess_kirim_lhkpn', (object) $this->sess_template_kirim_lhkpn);
        }


        $sess_kirim_lhkpn = $this->session->userdata('sess_kirim_lhkpn');
        $sess_kirim_lhkpn->data[] = $sess_template_data;
        $sess_kirim_lhkpn->id_lhkpn = $ID_LHKPN;

        $this->session->set_userdata('sess_kirim_lhkpn', $sess_kirim_lhkpn);

        unset($sess_kirim_lhkpn);
    }




    /////////////////////coding evan/////////////////////////////////

    protected function write_table_data_keluarga($data_keluarga, $lhkpn_ver = FALSE){
        $put_data_row = "";
        $no = 1;
        foreach ($data_keluarga as $key => $fam) {

            $tgl_lahir = null;
            if ($fam->TANGGAL_LAHIR) {
                $tgl_lahir = tgl_format($fam->TANGGAL_LAHIR);
            }
            $jenis_kelamin = $this->is_ever ? $this->__is_cetak_var_not_blank($this->__get_cetak_jenis_kelamin($fam->JENIS_KELAMIN), "-") : $this->__is_cetak_var_not_blank($fam->JENIS_KELAMIN, "-");
            $ttl_fam = $this->__is_cetak_var_not_blank($fam->TEMPAT_LAHIR, "-") . ' , ' . $tgl_lahir . ' / ' . $jenis_kelamin;
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537" /> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="535" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> </w:pPr> <w:r> <w:t>'.$no++ .'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2692" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($fam->NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1959" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_hubungan_keluarga($fam->HUBUNGAN, $lhkpn_ver).'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3416" w:type="dxa" /> <w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00935947" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr><w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$ttl_fam.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2232" w:type="dxa" /><w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00926FED" w:rsidRDefault="00246808" w:rsidP="0038393B"><w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($fam->PEKERJAAN, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="4520" w:type="dxa" /> <w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00926FED" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr><w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($fam->ALAMAT_RUMAH, "-").'</w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>DATA KELUARGA</w:t></w:r> </w:p> <w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="0" w:type="auto"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders><w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="535"/> <w:gridCol w:w="2692"/> <w:gridCol w:w="1959"/> <w:gridCol w:w="3416"/> <w:gridCol w:w="2232"/> <w:gridCol w:w="4520"/> </w:tblGrid><w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr><w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="535" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2692" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders><w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr><w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA</w:t> </w:r> </w:p> </w:tc> <w:tc><w:tcPr> <w:tcW w:w="1959" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>HUBUNGAN DENGAN PN</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3416" w:type="dxa" /> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>TEMPAT DAN TANGGAL LAHIR / JENIS KELAMIN</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2232" w:type="dxa" /> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>PEKERJAAN</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="4520" w:type="dxa" /> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto" /> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40" /> <w:vAlign w:val="center" /> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:ind w:left="0" /> <w:jc w:val="center" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>ALAMAT RUMAH</w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.'</w:tbl>';
       return $data;
    }

    protected function write_table_data_jabatan($jabatan) {
        $put_data_row = "";
        $no = 1;
        foreach ($jabatan as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="534" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00397078" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'. $no++ .'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="4057" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00894B24" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_JABATAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3455" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00AC1554" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->INST_NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3686" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00174823" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->UK_NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3622" w:type="dxa"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DF2C40" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->SUK_NAMA, "-").'</w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data = '<w:p w:rsidR="00246808" w:rsidRPr="00FD1E82" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:spacing w:after="0" w:line="240" w:lineRule="auto" /> <w:ind w:left="360" /> <w:rPr> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> </w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr> <w:pStyle w:val="ListParagraph" /> <w:numPr> <w:ilvl w:val="0" /> <w:numId w:val="1" /> </w:numPr> <w:spacing w:after="0" w:line="240" w:lineRule="auto" /> <w:ind w:left="360" /> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> </w:pPr> <w:r w:rsidRPr="00FD1E82"> <w:rPr> <w:b /> <w:lang w:val="id-ID" /> </w:rPr> <w:t>JABATAN </w:t> </w:r> </w:p> <w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid" /> <w:tblW w:w="0" w:type="auto" /> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto" /> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto" /> </w:tblBorders> <w:tblLayout w:type="fixed" /> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1" /> </w:tblPr> <w:tblGrid> <w:gridCol w:w="534" /> <w:gridCol w:w="4057" /> <w:gridCol w:w="3455" /> <w:gridCol w:w="3686" /> <w:gridCol w:w="3622" /> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537" /> <w:tblHeader /> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="535" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO</w:t> </w:r> </w:p> </w:tc>  <w:tc> <w:tcPr> <w:tcW w:w="4057" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>J </w:t> </w:r> <w:r w:rsidRPr="008E115A"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ABATAN - DESKRIPSI JABATAN / </w:t> </w:r> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> </w:t> </w:r> <w:r w:rsidRPr="008E115A"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ESELON </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3455" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LEMBAGA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3686" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>UNIT KERJA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="3622" w:type="dxa"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>SUB UNIT KERJA </w:t> </w:r> </w:p> </w:tc> </w:tr> '.$put_data_row.' </w:tbl>';
        return $data;
    }

    protected function write_table_data_htb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>DATA HARTA</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>TANAH / BANGUNAN</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00266A7F" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00266A7F" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Jalan / No </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JALAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kel. / Desa </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KEL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kecamatan </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KEC, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kab. / Kota </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KAB_KOT, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Prov. / Negara </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PROV, "-") . ' / ' . $this->__is_cetak_var_not_blank($row->NAMA_NEGARA, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="600" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tanah: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(number_format($row->LUAS_TANAH, 0, ",", "."), "-").' m </w:t> </w:r> <w:r> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> <w:t>2 </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Bangunan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(number_format($row->LUAS_BANGUNAN, 0, ",", "."), "-").' m </w:t> </w:r> <w:r> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> <w:t>2 </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1061" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis Bukti: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_BUKTI_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nomor Bukti: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NOMOR_BUKTI, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Atas Nama: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Asal Usul Harta: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Pemanfaatan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_pemanfaatan_harta($row->PEMANFAATAN), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'. $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2989" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="501"/> <w:gridCol w:w="3511"/> <w:gridCol w:w="1813"/> <w:gridCol w:w="3206"/> <w:gridCol w:w="2372"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LOKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="600" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LUAS </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1061" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KEPEMILIKAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr> '.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_hb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>ALAT TRANSPORTASI / MESIN</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="339" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1430" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00346CCA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="000F5A47" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Merk : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->MEREK, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0014742E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Model : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->MODEL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0014742E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Pembuatan : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->TAHUN_PEMBUATAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00DB454A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">No. Pol. / Registrasi : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NOPOL_REGISTRASI, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1220" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001F7877" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis Bukti: </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->N_JENIS_BUKTI, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00C85B29" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Asal Usul Harta: '.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00AC21C6" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Atas Nama: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00A0008A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Pemanfaatan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="002D47FA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Lainnya: </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->KET_LAINNYA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001A5C3A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003913DA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D4569" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1024"/> <w:gridCol w:w="4321"/> <w:gridCol w:w="3689"/> <w:gridCol w:w="2369"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="339" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1430" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1220" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KEPEMILIKAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_hbl($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HARTA BERGERAK LAINNYA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="355" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1414" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D52F0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="001B323A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jumlah : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JUMLAH, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="004D3AD2" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Satuan : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->SATUAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="002706F6" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Ket. Lainnya : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1221" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00E11B19" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00AD40F9" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="008C7439" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="0076399D" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1072"/> <w:gridCol w:w="4273"/> <w:gridCol w:w="3689"/> <w:gridCol w:w="2369"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="355" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1414" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1221" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_sb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>SURAT BERHARGA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            if($row->NAMA){
                $jenis_surat = $row->NAMA;
            }else{
                $jenis_surat = "-";
            }
            if (strlen($row->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($row->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $row->NOMOR_REKENING;
            }
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00FA4860" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D52F0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$jenis_surat.' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="001B323A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Penerbit / Perusahaan </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_PENERBIT, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0086543E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Cutodian / Sekuritas : '.$this->__is_cetak_var_not_blank($row->CUSTODIAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="692" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB6885" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank($decrypt_norek, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="969" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001E31B4" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00A15F40" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00D46384" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="006A08B0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2989" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="501"/> <w:gridCol w:w="3511"/> <w:gridCol w:w="2091"/> <w:gridCol w:w="2928"/> <w:gridCol w:w="2372"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="692" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">NO. REKENING / </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO. NASABAH </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="969" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_kas($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>KAS / SETARA KAS</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            // with keterangan
            // $put_data = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00F5660E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="0078573A" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Keterangan : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(trim($row->KETERANGAN), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="006E4B65" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nama Bank / Lembaga : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_BANK, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Nomor : '.$this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA_REKENING,$row->PASANGAN_ANAK,$row->ATAS_NAMA_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="00505AAB" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00692894" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00C91A00" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_EQUIVALEN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009A0D8D" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            if (strlen($row->NAMA_BANK) >= 32){
                $decrypt_namabank = encrypt_username($row->NAMA_BANK,'d');
            } else {
                $decrypt_namabank = $row->NAMA_BANK;
            }
            if (strlen($row->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($row->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $row->NOMOR_REKENING;
            }
            //without keterangan
            $put_data = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00F5660E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="0078573A" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Keterangan : </w:t> </w:r> <w:r> <w:t>-</w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="006E4B65" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nama Bank / Lembaga : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($decrypt_namabank, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Nomor : '.$this->__is_cetak_var_not_blank($decrypt_norek, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA_REKENING,$row->PASANGAN_ANAK,$row->ATAS_NAMA_LAINNYA), "-").' </w:t> </w:r> </w:p></w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00692894" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00C91A00" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_EQUIVALEN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009A0D8D" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="3774" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1210"/> <w:gridCol w:w="4235"/> <w:gridCol w:w="3734"/> <w:gridCol w:w="2411"/> <w:gridCol w:w="2269"/> <w:gridCol w:w="1495"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>INFORMASI REKENING </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_hl($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HARTA LAINNYA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="317" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00165890" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1451" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->NAMA_JENIS, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="00D77743" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1222" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009D77F3" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="007C1ED1" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_LAMA).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="004E42E8" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="000A58A6" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="973"/> <w:gridCol w:w="4456"/> <w:gridCol w:w="3753"/> <w:gridCol w:w="2408"/> <w:gridCol w:w="2269"/> <w:gridCol w:w="1495"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="317" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1451" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1222" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SEBELUMNYA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN SAAT INI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_htg($data, $is_copy,$total,$saldo) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HUTANG</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B25150" w:rsidTr="009927CC"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="344" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00326241" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1246" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00B25150" w:rsidRPr="008F01DB" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1123" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00C70782" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_KREDITUR, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="800" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00E06AF6" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->AGUNAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00352172" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->AWAL_HUTANG).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="002F3325" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->SALDO_HUTANG).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="3513" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00636F13" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$saldo.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1056"/> <w:gridCol w:w="3826"/> <w:gridCol w:w="3449"/> <w:gridCol w:w="2457"/> <w:gridCol w:w="2318"/> <w:gridCol w:w="2248"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="344" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1246" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1123" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA KREDITUR </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="800" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>BENTUK AGUNAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI AWAL HUTANG </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI SALDO HUTANG </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_penerimaan_pekerjaan($data, $is_copy, $total_pn, $total_ps) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'A') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                    "PASANGAN" => $PM->PASANGAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN DARI PEKERJAAN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="337" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1968" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="000A5177" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PASANGAN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2305" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total_pn.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00636F13" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total_ps.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1036"/> <w:gridCol w:w="6044"/> <w:gridCol w:w="4082"/> <w:gridCol w:w="4192"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="337" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1968" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>PENYELENGGARA NEGARA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>PASANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_penerimaan_usaha($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'B') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN DARI USAHA DAN KEKAYAAN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1045"/> <w:gridCol w:w="6927"/> <w:gridCol w:w="7382"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL PENERIMAAN KAS </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_penerimaan_lainnya($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'C') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN LAINNYA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1045"/> <w:gridCol w:w="6927"/> <w:gridCol w:w="7382"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL PENERIMAAN KAS </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }


    protected function write_table_data_pengeluaran_rutin($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'A') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN RUTIN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_pengeluaran_harta($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'B') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN HARTA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_pengeluaran_lainnya($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'C') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN LAINNYA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_lampiran_fasilitas($data, $is_copy) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>LAMPIRAN FASILITAS</w:t></w:r> </w:p>';
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="006A0D67" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->JENIS_FASILITAS, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00B56D76" w:rsidRPr="0008004D" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00C83E31" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PEMBERI_FASILITAS, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="0049016F" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1011"/> <w:gridCol w:w="4599"/> <w:gridCol w:w="4975"/> <w:gridCol w:w="4769"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA PIHAK PEMBERI FASILITAS </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_data_total_harta($total_harta, $total_hutang,$total_harta_kekayaan) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>TOTAL HARTA KEKAYAAN</w:t></w:r> </w:p>';
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $put_data_row = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>1 </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="00C07D7C" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="00C07D7C" w:rsidP="00C07D7C"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_hutang), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="00C07D7C" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta_kekayaan), "-").' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1010"/> <w:gridCol w:w="4600"/> <w:gridCol w:w="4975"/> <w:gridCol w:w="4769"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> <w:t>TOTAL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> <w:t>TOTAL HUTANG </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="00987011" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00987011"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> </w:rPr> <w:t>TOTAL HARTA KEKAYAAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.' </w:tbl>';
        return $data_output;
    }






    /////////////////////coding evan/////////////////////////////////












    function preview($ID_LHKPN, $KETENTUAN = 0, $OPTION = 0) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = $this->__get_data_cetak($ID_LHKPN);
            $data['KETENTUAN'] = $KETENTUAN;
            $data['OPTION'] = $OPTION;
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), true);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('SURAT_PERNYATAAN' => $html, 'STATUS_SURAT_PERNYATAAN' => '' . $OPTION));
            //$this->db->update('t_lhkpn',array('SURAT_PERNYATAAN'=>$html,'STATUS_SURAT_PERNYATAAN'=>''));
            echo $html;
            /* include_once APPPATH.'/third_party/mpdf/mpdf.php';
              $pdf = new mPDF('A4-L');
              $pdf->WriteHTML($html);
              $pdf->Output(); */
        } else {
            redirect('portal/filing');
        }
    }

    function priview_cetak($ID_LHKPN, $KETENTUAN = 0, $OPTION = 0) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = $this->__get_data_cetak($ID_LHKPN);
            $data['KETENTUAN'] = $KETENTUAN;
            $data['OPTION'] = $OPTION;
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), true);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('SURAT_PERNYATAAN' => $html, 'STATUS_SURAT_PERNYATAAN' => '' . $OPTION));
            include_once APPPATH . '/third_party/mpdf/mpdf.php';
            $pdf = new mPDF('A4-L');
            $pdf->WriteHTML($html);
            $pdf->Output();
        } else {
            redirect('portal/filing');
        }
    }

    function cetakAnnoun($ID_LHKPN) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = array();
            $data['LHKPN'] = $cek_id_user;
            $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
            $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
            $data['JABATAN'] = $this->jabatan($ID_LHKPN);
            $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
            $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
            $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
            $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
            $data['HUTANG'] = $this->hutang($ID_LHKPN);
            $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
            $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
            $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);
            /* print_r($data['HARTA_TDK_BEGERAK']); */
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), TRUE);
            include_once APPPATH . '/third_party/mpdf/mpdf.php';
            $pdf = new mPDF('c', 'A4-L');
            $pdf->WriteHTML($html);
            $pdf->Output();
        } else {
            redirect('portal/filing');
        }
    }

    function imp_xl_lhkpn($imp_xl_lhkpn) {
        $this->db->where('t_imp_xl_lhkpn.id_imp_xl_lhkpn', $imp_xl_lhkpn);
        $data = $this->db->get('t_imp_xl_lhkpn')->row();
        return $data;
    }

    function lhkpn($ID_LHKPN) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);

        /**
         * jika bukan everifikasi (dalam hal ini adalah efill),
         * maka harus dibatasi berdasarkan ID_PN yang sedang Login
         */
        if (!$this->is_ever) {
            $this->db->where('t_lhkpn.ID_PN', $this->session->userdata('ID_PN'));
        }
        $data = $this->db->get('t_lhkpn')->row();
        return $data;
    }

    protected function _q_pribadi($ID_LHKPN, $table_name, $primary_key) {
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->group_by($table_name . '.' . $primary_key);
        $this->db->order_by($table_name . '.' . $primary_key, 'DESC');
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $this->db->join('m_negara', 'm_negara.KODE_ISO3 = ' . $table_name . '.KD_ISO3_NEGARA', 'LEFT');
        $this->db->join('m_area_prov', 'm_area_prov.NAME = ' . $table_name . '.PROVINSI', 'LEFT');
        $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = ' . $table_name . '.KABKOT', 'LEFT');
        $this->db->join($this->table_lhkpn, $this->table_lhkpn . '.' . $this->field_id_lhkpn . ' = ' . $table_name . '.' . $this->field_id_lhkpn);
        $this->db->join('t_pn', 't_pn.ID_PN = ' . $this->table_lhkpn . '.ID_PN');
    }

    public function _cnt_pribadi($ID_LHKPN, $table_name, $primary_key) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_pribadi();
        $this->db->select('count(' . $primary_key . ') as jumlah_data');
        $this->_q_pribadi($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    private function __conf_pribadi() {
        $table_name = 't_lhkpn_data_pribadi';
        $primary_key = 'ID';

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_data_pribadi';
            $primary_key = 'id_imp_xl_lhkpn_data_pribadi';
        }
        return array($table_name, $primary_key);
    }

    function pribadi($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key) = $this->__conf_pribadi();
        $this->db->select($table_name . '.*,m_negara.KODE_ISO3,.m_negara.NAMA_NEGARA,m_area_prov.*,m_area_kab.*,t_pn.NHK');
        $this->_q_pribadi($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();
        return $data;
    }

    public function _cnt_keluarga($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_keluarga();
        $this->db->select('count(' . $primary_key . ') as jumlah_data');
        $this->_q_keluarga($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_keluarga($ID_LHKPN, $table_name, $primary_key) {
        $this->db->where($this->field_id_lhkpn, $ID_LHKPN);
    }

    private function __conf_keluarga() {
        $table_name = 't_lhkpn_keluarga';
        $primary_key = 'ID_KELUARGA';

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_keluarga';
            $primary_key = 'id_imp_xl_lhkpn_keluarga';
        }
        return array($table_name, $primary_key);
    }

    function keluarga($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key) = $this->__conf_keluarga();

        $this->_q_keluarga($ID_LHKPN, $table_name, $primary_key);
        $this->db->limit($limit, $offset);
        $data = $this->db->get($table_name)->result();
        return $data;
    }

    public function _cnt_jabatan($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_jabatan();
        $this->db->select('count(' . $primary_key . ') as jumlah_data');
        $this->_q_jabatan($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_jabatan($ID_LHKPN, $table_name, $primary_key) {
        $this->db->where($this->field_id_lhkpn, $ID_LHKPN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = ' . $table_name . '.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = m_jabatan.INST_SATKERKD', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = m_jabatan.UK_ID', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = m_jabatan.SUK_ID', 'left');
        $this->db->join('m_bidang', ',m_bidang.BDG_ID = m_inst_satker.INST_BDG_ID', 'left');
        $this->db->group_by($table_name . '.' . $primary_key);
    }

    private function __conf_jabatan() {
        $table_name = 't_lhkpn_jabatan';
        $primary_key = 'ID';

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_jabatan';
            $primary_key = 'id_imp_xl_lhkpn_jabatan';
        }
        return array($table_name, $primary_key);
    }

    function jabatan($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key) = $this->__conf_jabatan();

        $this->_q_jabatan($ID_LHKPN, $table_name, $primary_key);
        $this->db->limit($limit, $offset);
        $data = $this->db->get($table_name)->result();
        return $data;
    }

    public function _cnt_harta_tidak_bergerak($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_harta_tidak_bergerak();
        $this->db->select('count('.$table_name.'.' . $primary_key . ') as jumlah_data');
        $this->_q_harta_tidak_bergerak($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_harta_tidak_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix) {
        $PK = $primary_key;
        $TABLE = 't_' . $prefix . 'lhkpn_harta_tidak_bergerak';
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = t_' . $prefix . 'lhkpn_harta_tidak_bergerak.ID_NEGARA', 'left');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
    }

    private function __conf_harta_tidak_bergerak() {
        $table_name = 't_lhkpn_harta_tidak_bergerak';
        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $table_name = 't_imp_xl_lhkpn_harta_tidak_bergerak';
            $primary_key = 'id_imp_xl_lhkpn_harta_tidak_bergerak';
            $prefix = "imp_xl_";
        }
        return array($table_name, $primary_key, $prefix);
    }

    function harta_tidak_bergerak($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key, $prefix) = $this->__conf_harta_tidak_bergerak();

        $this->db->select('
            m_jenis_bukti.*,
            m_mata_uang.*,
            m_negara.NAMA_NEGARA,
            ' . $table_name . '.*,
            ' . $table_name . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $table_name . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $table_name . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            (SELECT NILAI_PELAPORAN FROM ' . $table_name . ' p WHERE p.ID = ' . $table_name . '.Previous_ID) AS NILAI_LAMA,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $table_name . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (' . $table_name . '.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
            m_jenis_bukti.JENIS_BUKTI AS JENIS_BUKTI_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_' . $prefix . 'lhkpn_pelepasan_harta_tidak_bergerak WHERE t_' . $prefix . 'lhkpn_pelepasan_harta_tidak_bergerak.ID_HARTA = ' . $table_name . '.' . $primary_key . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');

        $this->_q_harta_tidak_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix);
        $this->db->limit($limit, $offset);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_harta_tidak_bergerak')->result();
        return $data;
    }


    public function _cnt_harta_bergerak($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_harta_bergerak();
        $this->db->select('count('.$table_name.'.' . $primary_key . ') as jumlah_data');
        $this->_q_harta_bergerak($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    public function _cnt_nilai_pelaporan_harta_bergerak($ID_LHKPN) {
        $this->jumlah_hb = 0;
        $table_name = 't_lhkpn_harta_bergerak';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_hb');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_hb = $data->jumlah_hb;
        }
        return $data->jumlah_hb;
    }

    public function _cnt_nilai_pelaporan_harta_tidak_bergerak($ID_LHKPN) {
        $this->jumlah_htb = 0;
        $table_name = 't_lhkpn_harta_tidak_bergerak';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_htb');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_htb = $data->jumlah_htb;
        }
        return $data->jumlah_htb;
    }

    public function _cnt_nilai_pelaporan_harta_bergerak_lain($ID_LHKPN) {
        $this->jumlah_hbl = 0;
        $table_name = 't_lhkpn_harta_bergerak_lain';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_hbl');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_hbl = $data->jumlah_hbl;
        }
        return $data->jumlah_hbl;
    }

    public function _cnt_nilai_pelaporan_harta_surat_berharga($ID_LHKPN) {
        $this->jumlah_sb = 0;
        $table_name = 't_lhkpn_harta_surat_berharga';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_sb');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_sb = $data->jumlah_sb;
        }
        return $data->jumlah_sb;
    }

    public function _cnt_nilai_pelaporan_harta_kas($ID_LHKPN, $is_klarif = 0) {
        $this->jumlah_kas = 0;
        $table_name = 't_lhkpn_harta_kas';
        $primary_key_1 = 'NILAI_EQUIVALEN';

        if($is_klarif){
            $this->db->select('SUM('.$table_name.'.' . $primary_key_1 . ') as jumlah_kas');
            $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
            $this->db->where($table_name . '.IS_ACTIVE', '1');
            $this->db->where($table_name . '.IS_PELEPASAN <>', '1');
            $data = $this->db->get($table_name)->row()->jumlah_kas;
        }else{
            $this->db->select('SUM('.$table_name.'.' . $primary_key_1 . ') as jumlah_kas');
            $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
            $this->db->where($table_name . '.IS_ACTIVE', '1');
            $data = $this->db->get($table_name)->row()->jumlah_kas;
        }

        return $this->jumlah_kas = $data;
    }

    public function _cnt_nilai_pelaporan_harta_lainnya($ID_LHKPN) {
        $this->jumlah_hl = 0;
        $table_name = 't_lhkpn_harta_lainnya';
        $primary_key = 'NILAI_PELAPORAN';
        $this->db->select('SUM('.$table_name.'.' . $primary_key . ') as jumlah_hl');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_hl = $data->jumlah_hl;
        }
        return $data->jumlah_hl;
    }

    public function _cnt_nilai_pelaporan_harta_hutang($ID_LHKPN) {
        $this->jumlah_shtg = 0;
        $this->jumlah_ahtg = 0;
        $table_name = 't_lhkpn_hutang';
        $saldo_hutang = 'SALDO_HUTANG';
        $awal_hutang = 'AWAL_HUTANG';
        $this->db->select('SUM('.$table_name.'.' . $saldo_hutang . ') as jumlah_shtg, SUM('.$table_name.'.' . $awal_hutang . ') as jumlah_ahtg');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.IS_ACTIVE', '1');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_shtg = $data->jumlah_shtg;
            $this->jumlah_ahtg = $data->jumlah_ahtg;
        }
        return $data;
    }

    public function _cnt_penerimaan_gaji($ID_LHKPN) {
        $null = 'NULL';
        $this->jumlah_pg_pn = 0;
        $this->jumlah_pg_ps = 0;
        $table_name = 't_lhkpn_penerimaan_kas2';
        $pn = 'PN';
        $ps = 'PASANGAN';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.'.$pn.') AS jumlah_pg_pn, SUM('.$table_name.'.'.$ps.') AS jumlah_pg_ps',false);
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'A');
        $data = $this->db->get($table_name)->row();
        if ($data) {
            $this->jumlah_pg_pn = $data->jumlah_pg_pn;
            $this->jumlah_pg_ps = $data->jumlah_pg_ps;
        }
        return $data;
    }

    public function _cnt_penerimaan_usaha($ID_LHKPN) {
        $this->jumlah_pu_pn = 0;
        $table_name = 't_lhkpn_penerimaan_kas2';
        $pn = 'PN';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pu_pn');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'B');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pu_pn = $data->jumlah_pu_pn;
        }
        return $data;
    }

    public function _cnt_penerimaan_lainnya($ID_LHKPN) {
        $this->jumlah_pl_pn = 0;
        $table_name = 't_lhkpn_penerimaan_kas2';
        $pn = 'PN';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pl_pn');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'C');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pl_pn = $data->jumlah_pl_pn;
        }
        return $data;
    }

    public function _cnt_pengeluaran_rutin($ID_LHKPN) {
        $this->jumlah_pr = 0;
        $table_name = 't_lhkpn_pengeluaran_kas2';
        $pn = 'JML';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pr');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'A');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pr = $data->jumlah_pr;
        }
        return $data;
    }

    public function _cnt_pengeluaran_harta($ID_LHKPN) {
        $this->jumlah_ph = 0;
        $table_name = 't_lhkpn_pengeluaran_kas2';
        $pn = 'JML';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_ph');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'B');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_ph = $data->jumlah_ph;
        }
        return $data;
    }

    public function _cnt_pengeluaran_lainnya($ID_LHKPN) {
        $this->jumlah_pl = 0;
        $table_name = 't_lhkpn_pengeluaran_kas2';
        $pn = 'JML';
        $this->db->select('GROUP_JENIS, SUM('.$table_name.'.' . $pn . ') AS jumlah_pl');
        $this->db->group_by('GROUP_JENIS');
        $this->db->where($table_name . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($table_name . '.GROUP_JENIS', 'C');
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_pl = $data->jumlah_pl;
        }
        return $data;
    }

    protected function _q_harta_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix) {
        $PK = $primary_key;
        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak';
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
    }

    private function __conf_harta_bergerak() {
        $primary_key = 'ID';
        $prefix = "";
        $PK = 'ID';

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_bergerak';
            $prefix = "imp_xl_";
        }
        return array($table_name, $primary_key, $prefix);
    }

    function harta_bergerak($ID_LHKPN, $offset = 0, $limit = 0) {

        list($table_name, $primary_key, $prefix) = $this->__conf_harta_bergerak();

        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak';

        $this->db->select('
            m_jenis_harta.*,
            m_jenis_bukti.*,
            m_jenis_bukti.JENIS_BUKTI AS N_JENIS_BUKTI,
            ' . $TABLE . '.*,
            m_jenis_harta.NAMA AS JENIS_HARTA,
            ' . $TABLE . '.STATUS as STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (' . $TABLE . '.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_' . $prefix . 'lhkpn_pelepasan_harta_bergerak WHERE t_' . $prefix . 'lhkpn_pelepasan_harta_bergerak.ID_HARTA = ' . $TABLE . '.' . $primary_key . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');
       $this->_q_harta_bergerak($ID_LHKPN, $table_name, $primary_key, $prefix);
       $this->db->limit($limit, $offset);
        $data = $this->db->get($TABLE)->result();
//        echo $this->db->last_query();exit;
        return $data;
    }

    public function _cnt_harta_bergerak_lain($ID_LHKPN) {
        $this->jumlah_data = 0;
        list($table_name, $primary_key) = $this->__conf_harta_bergerak_lain();
        $this->db->select('count('.$table_name.'.' . $primary_key . ') as jumlah_data');
        $this->_q_harta_bergerak_lain($ID_LHKPN, $table_name, $primary_key);
        $data = $this->db->get($table_name)->row();

        if ($data) {
            $this->jumlah_data = $data->jumlah_data;
        }
        return $data->jumlah_data;
    }

    protected function _q_harta_bergerak_lain($ID_LHKPN, $table_name, $primary_key, $prefix) {
        $PK = $primary_key;
        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak';
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
    }

    private function __conf_harta_bergerak_lain() {
        $primary_key = 'ID';
        $prefix = "";
        $PK = 'ID';

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_bergerak';
            $prefix = "imp_xl_";
        }
        return array($table_name, $primary_key, $prefix);
    }

    function harta_bergerak_lain($ID_LHKPN) {
        $primary_key = 'ID';
        $prefix = "";
        $PK = 'ID';

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_bergerak_lain';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_bergerak_lain';
        $this->db->select('
            m_jenis_harta.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            m_jenis_harta.NAMA as JENIS_HARTA,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (' . $TABLE . '.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_bergerak_lain WHERE t_lhkpn_pelepasan_harta_bergerak_lain.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function harta_surat_berharga($ID_LHKPN) {

        $PK = 'ID';

        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_surat_berharga';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_surat_berharga';

        $this->db->select('
            m_jenis_harta.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_surat_berharga WHERE t_lhkpn_pelepasan_harta_surat_berharga.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function harta_lainnya($ID_LHKPN) {

        $PK = 'ID';

        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_lainnya';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_lainnya';

        $this->db->select('
            m_jenis_harta.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
            m_jenis_harta.NAMA AS NAMA_JENIS,
            (SELECT NILAI_PELAPORAN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_lainnya WHERE t_lhkpn_pelepasan_harta_lainnya.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN
        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function harta_kas($ID_LHKPN) {


        $PK = 'ID';

        $primary_key = 'ID';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_harta_kas';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_harta_kas';

        $this->db->select('
            m_jenis_harta.*,
            m_mata_uang.*,
            ' . $TABLE . '.*,
            ' . $TABLE . '.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `' . $TABLE . '`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `' . $TABLE . '`.`NILAI_SALDO`
                END `NILAI_SALDO`,
            (SELECT NILAI_EQUIVALEN FROM ' . $TABLE . ' p WHERE p.ID = ' . $TABLE . '.Previous_ID) AS NILAI_LAMA,,
            (SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (' . $TABLE . '.ASAL_USUL) ) AS ASAL_USUL_HARTA,
            (SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_kas WHERE t_lhkpn_pelepasan_harta_kas.ID_HARTA = ' . $TABLE . '.' . $PK . ' LIMIT 1 ) AS ID_JENIS_PELEPASAN,
            (SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function hutang($ID_LHKPN) {

        $PK = 'ID_HUTANG';

        $primary_key = 'ID_HUTANG';
        $prefix = "";

        if ($this->is_validation) {
            $PK = $primary_key = 'id_imp_xl_lhkpn_hutang';
            $prefix = "imp_xl_";
        }

        $TABLE = 't_' . $prefix . 'lhkpn_hutang';

        $this->db->where($TABLE . '.' . $this->field_id_lhkpn, $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = ' . $TABLE . '.KODE_JENIS');
        $this->db->join($this->table_lhkpn, $this->table_lhkpn . '.' . $this->field_id_lhkpn . ' = ' . $TABLE . '.' . $this->field_id_lhkpn);
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($TABLE . '.' . $PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        return $data;
    }

    function penerimaan($ID_LHKPN) {
        $prefix = "";

        if ($this->is_validation) {

            $this->load->model('mglobal');

            $data_detail = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn_penerimaan_kas", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn_penerimaan_kas", make_secure_text($ID_LHKPN));

            $pn = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PN) ? $data_detail->NILAI_PENERIMAAN_KAS_PN : "{}");
            $pa = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PASANGAN) ? $data_detail->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");

            $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
            $golongan_penerimaan_kas_pn = $this->config->item('golongan_penerimaan_kas_pn', 'harta');

            $label = array('A', 'B', 'C');

            $data_arr = array();

            $k = 0;
            for ($i = 0; $i < count($jenis_penerimaan_kas_pn); $i++) {
                for ($j = 0; $j < count($jenis_penerimaan_kas_pn[$i]); $j++) {
                    $PA_val = 'PA' . $j;
                    $code = $label[$i] . $j;

                    $data_arr[$k] = array(
                        "ID_LHKPN" => $id_lhkpn,
                        "GROUP_JENIS" => $label[$i],
                        "KODE_JENIS" => $code,
                        "JENIS_PENERIMAAN" => $jenis_penerimaan_kas_pn[$i][$j],
                        "PN" => 0,
                        "PASANGAN" => 0,
                    );
                    if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                        $data_arr[$k]["PN"] = $pn->{$label[$i]}[$j]->$code;
                    }

                    if ($i == 0) {
                        if (is_array($pa) && !empty($pa) && property_exists($pa[$j], $PA_val)) {
                            $data_arr[$k]["PASANGAN"] = $pa[$j]->{$PA_val};
                        }
                    }

                    $k++;
                }
            }
            unset($pn, $pa, $jenis_penerimaan_kas_pn);
            return $data_arr;

            $prefix = "imp_xl_";
        }

        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_' . $prefix . 'lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        // $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_' . $prefix . 'lhkpn_penerimaan_kas2.' . $this->field_id_lhkpn, $ID_LHKPN);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_penerimaan_kas2')->result();
        return $data;
    }

    function pengeluaran($ID_LHKPN) {

        $prefix = "";

        if ($this->is_validation) {

            $this->load->model('mglobal');

            $data_detail = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn_pengeluaran_kas", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn_pengeluaran_kas", make_secure_text($ID_LHKPN));

            $pn = json_decode(!is_null($data_detail->NILAI_PENGELUARAN_KAS) ? $data_detail->NILAI_PENGELUARAN_KAS : "{}");

            $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
            $label = array('A', 'B', 'C');

            $data_arr = array();

            $k = 0;
            for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
                for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                    $code = $label[$i] . $j;

                    $data_arr[$k] = array(
                        "ID_LHKPN" => $ID_LHKPN,
                        "GROUP_JENIS" => $label[$i],
                        "KODE_JENIS" => $code,
                        "JENIS_PENGELUARAN" => $jenis_pengeluaran_kas_pn[$i][$j],
                        "JML" => 0,
                    );

                    if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
                        $data_arr[$k]["JML"] = $pn->{$label[$i]}[$j]->$code;
                    }
                    $k++;
                }
            }
//            unset($pn, $jenis_pengeluaran_kas_pn);
            return $data_arr;

            $prefix = "imp_xl_";
        }

        $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_' . $prefix . 'lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
        $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
        $this->db->where('t_' . $prefix . 'lhkpn_pengeluaran_kas2.' . $this->field_id_lhkpn, $ID_LHKPN);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_pengeluaran_kas2')->result();
        return $data;
    }

    function fasilitas($ID_LHKPN) {

        $prefix = "";

        if ($this->is_validation) {
            $prefix = "imp_xl_";
        }

        $this->db->where($this->field_id_lhkpn, $ID_LHKPN);
        $data = $this->db->get('t_' . $prefix . 'lhkpn_fasilitas')->result();
        return $data;
    }

    /** ========================================================================================================== */
    public $limit = 0;
    public $jumlah_data = 0;
    private $__data_lhkpn = FALSE;

    private function __check_is_validation($ID_LHKPN = FALSE, $is_validation = FALSE) {
        if ($is_validation) {
            $this->is_validation = TRUE;
            $this->field_id_lhkpn = 'id_imp_xl_lhkpn';
            $this->table_lhkpn = 't_imp_xl_lhkpn';
            $this->__data_lhkpn = $this->imp_xl_lhkpn($ID_LHKPN);
        } else {
            $this->__data_lhkpn = $this->lhkpn($ID_LHKPN);
            $is_validation = (bool) $this->__data_lhkpn;
        }
        return $is_validation;
    }

    protected function __get_exec_time() {
        //dimana 5 adalah limit
        $exec_times = ceil($this->jumlah_data / $this->limit);

        if ($this->__execution_time == 'min') {
            $exec_times = 1;
        }
        return $exec_times;
    }

    protected function __arrange_cetak_data_keluarga_v2($ID_LHKPN, $lhkpn_ver = FALSE) {

        $this->_cnt_keluarga($ID_LHKPN);
        if ($this->jumlah_data && $this->jumlah_data > 0) {
            $current_execution = 1;

            //dimana 5 adalah limit
            $exec_times = $this->__get_exec_time();
            $str_tr = "";
            $current_no = 0;

            while ($current_execution <= $exec_times) {
                $offset = calculate_offset($current_execution);
                $data_keluarga = $this->keluarga($ID_LHKPN, $offset, 5);

                foreach ($data_keluarga as $key => $row) {

                    $current_no++;

                    $tgl_lahir = null;
                    if ($row->TANGGAL_LAHIR) {
                        $tgl_lahir = tgl_format($row->TANGGAL_LAHIR);
                    }

                    $jenis_kelamin = $this->is_ever ? $this->__is_cetak_var_not_blank($this->__get_cetak_jenis_kelamin($row->JENIS_KELAMIN), "-") : $this->__is_cetak_var_not_blank($row->JENIS_KELAMIN, "-");
                    $ttl_fam = $this->__is_cetak_var_not_blank($row->TEMPAT_LAHIR, "-") . ' , ' . $tgl_lahir . ' / ' . $jenis_kelamin;

                    $str_tr .= $this->load->view('ikhtisar/tr_keluarga', [
                        "no_fam" => $current_no,
                        "nama_fam" => $this->__is_cetak_var_not_blank($row->NAMA, "-"),
                        "hubungan_fam" => $this->__get_cetak_hubungan_keluarga($row->HUBUNGAN, $lhkpn_ver),
                        "tmpt_tgl_lahir_jenis_kelamin_fam" => $ttl_fam,
                        "pekerjaan_fam" => $this->__is_cetak_var_not_blank(tgl_format($row->PEKERJAAN), "-"),
                        "alamat_rumah_fam" => $this->__is_cetak_var_not_blank(tgl_format($row->ALAMAT_RUMAH), "-")
                            ], TRUE);
                }
                unset($data_keluarga);
                $current_execution++;
            }
            $table_string = $this->load->view('ikhtisar/tbl_data_keluarga', [
                "rows" => $str_tr
                    ], TRUE);

            return $table_string;
        }
        return "";
    }

    protected function __arrange_cetak_data_jabatan_v2($ID_LHKPN) {
        $this->_cnt_jabatan($ID_LHKPN);
        if ($this->jumlah_data && $this->jumlah_data > 0) {
            $current_execution = 1;

            //dimana 5 adalah limit
            $exec_times = $this->__get_exec_time();
            $str_tr = "";
            $current_no = 0;

            while ($current_execution <= $exec_times) {
                $offset = calculate_offset($current_execution);
                $record = $this->keluarga($ID_LHKPN, $offset, 5);

                foreach ($record as $key => $row) {

                    $current_no++;

                    $str_tr .= $this->load->view('ikhtisar/tr_jabatan', [
                        "no_jab" => $current_no,
                        "nama_jab" => $this->__is_cetak_var_not_blank($row->NAMA_JABATAN, "-"),
                        "inst_nama_jab" => $this->__is_cetak_var_not_blank($row->INST_NAMA, "-"),
                        "uk_nama_jab" => $this->__is_cetak_var_not_blank($row->UK_NAMA, "-"),
                        "suk_nama_jab" => $this->__is_cetak_var_not_blank($row->SUK_NAMA, "-")
                            ], TRUE);
                }
                unset($record);
                $current_execution++;
            }
            $table_string = $this->load->view('ikhtisar/tbl_data_jabatan', [
                "rows" => $str_tr
                    ], TRUE);

            return $table_string;
        }
        return "";
    }

    protected function __arrange_cetak_data_harta_tidak_bergerak_v2($ID_LHKPN, $is_copy) {
        $this->_cnt_harta_tidak_bergerak($ID_LHKPN);
        if ($this->jumlah_data && $this->jumlah_data > 0) {
            $current_execution = 1;

            //dimana 5 adalah limit
            $exec_times = $this->__get_exec_time();

            $str_tr = "";
            $current_no = 0;

            while ($current_execution <= $exec_times) {
                $offset = calculate_offset($current_execution);
                $record = $this->harta_tidak_bergerak($ID_LHKPN, $offset, 5);

                foreach ($record as $key => $row) {

                    $current_no++;

                    $str_tr .= $this->load->view('ikhtisar/tr_harta_tidak_bergerak', [
                        "no_htb" => $current_no,
                        "jalan_htb" => $this->__is_cetak_var_not_blank($row->JALAN, "-"),
                        "kel_htb" => $this->__is_cetak_var_not_blank($row->KEL, "-"),
                        "kec_htb" => $this->__is_cetak_var_not_blank($row->KEC, "-"),
                        "kab_kot_htb" => $this->__is_cetak_var_not_blank($row->KAB_KOT, "-"),
                        "prov_htb" => $this->__is_cetak_var_not_blank($row->PROV, "-"),
                        "luas_tanah_htb" => $this->__is_cetak_var_not_blank($row->LUAS_TANAH, "-"),
                        "luas_bangunan_htb" => $this->__is_cetak_var_not_blank($row->LUAS_BANGUNAN, "-"),
                        "jenis_bukti_htb" => $this->__is_cetak_var_not_blank($row->JENIS_BUKTI_HARTA, "-"),
                        "nomor_bukti_htb" => $this->__is_cetak_var_not_blank($row->NOMOR_BUKTI, "-"),
                        "atas_nama_htb" => $this->__get_cetak_atas_nama($row->ATAS_NAMA, "-"),
                        "asal_usul_htb" => $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"),
                        "pemanfaatan_htb" => $this->__is_cetak_var_not_blank($this->__get_pemanfaatan_harta($row->PEMANFAATAN), "-"),
                        "nilai_lama_htb" => 'Rp. ' . number_rupiah($row->NILAI_LAMA),
                        "nilai_pelaporan_htb" => 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN),
                        "keterangan_htb" => $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy),
                            ], TRUE);
                }
                unset($record);
                $current_execution++;
            }
            $table_string = $this->load->view('ikhtisar/tbl_data_harta_tidak_bergerak', [
                "rows" => $str_tr
                    ], TRUE);

            return $table_string;
        }
        return "";
    }

    protected function __arrange_cetak_data_harta_bergerak_v2($ID_LHKPN, $is_copy) {
        $this->_cnt_harta_bergerak($ID_LHKPN);
        if ($this->jumlah_data && $this->jumlah_data > 0) {
            $current_execution = 1;

            //dimana 5 adalah limit
            $exec_times = $this->__get_exec_time();

            $str_tr = "";
            $current_no = 0;

            while ($current_execution <= $exec_times) {
                $offset = calculate_offset($current_execution);

                $record = $this->harta_bergerak($ID_LHKPN, $offset, 5);

                foreach ($record as $key => $row) {

                    $current_no++;

                    $str_tr .= $this->load->view('ikhtisar/tr_harta_bergerak', [
                        "no_hb" => $current_no,
                        "jenis_harta_hb" => $this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-"),
                        "merek_hb" => $this->__is_cetak_var_not_blank($row->MEREK, "-"),
                        "model_hb" => $this->__is_cetak_var_not_blank($row->MODEL, "-"),
                        "tahun_pembuatan_hb" => $this->__is_cetak_var_not_blank($row->TAHUN_PEMBUATAN, "-"),
                        "nopol_registrasi_hb" => $this->__is_cetak_var_not_blank($row->NOPOL_REGISTRASI, "-"),
                        "jenis_bukti_hb" => $this->__is_cetak_var_not_blank($row->N_JENIS_BUKTI, "-"),
                        "asal_usul_harta_hb" => $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"),
                        "atas_nama_hb" => $this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama($row->ATAS_NAMA), "-"),
                        "pemanfaatan_harta_hb" => $this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-"),
                        "ket_lainnya_hb" => $this->__get_cetak_atas_nama($row->KET_LAINNYA, "-"),
                        "nilai_lama_hb" => 'Rp. ' . number_rupiah($row->NILAI_LAMA),
                        "nilai_pelaporan_hb" => 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN),
                        "keterangan_hb" => $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy),
                            ], TRUE);
                }
                unset($record);
                $current_execution++;
            }
            $table_string = $this->load->view('ikhtisar/tbl_data_harta_bergerak', [
                "rows" => $str_tr
                    ], TRUE);

            return $table_string;
        }
        return "";
    }

    protected function __arrange_cetak_data_harta_bergerak_lain_v2($data, $is_copy) {
        $this->_cnt_harta_bergerak_lain($ID_LHKPN);
        if ($this->jumlah_data && $this->jumlah_data > 0) {
            $current_execution = 1;

            //dimana 5 adalah limit
            $exec_times = $this->__get_exec_time();

            $str_tr = "";
            $current_no = 0;

            while ($current_execution <= $exec_times) {
                $offset = calculate_offset($current_execution);

                $record = $this->harta_bergerak($ID_LHKPN, $offset, 5);

                foreach ($record as $key => $row) {

                    $current_no++;

                    $str_tr .= $this->load->view('ikhtisar/tr_harta_bergerak', [
                        "no_hb" => $current_no,
                        "jenis_harta_hb" => $this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-"),
                        "merek_hb" => $this->__is_cetak_var_not_blank($row->MEREK, "-"),
                        "model_hb" => $this->__is_cetak_var_not_blank($row->MODEL, "-"),
                        "tahun_pembuatan_hb" => $this->__is_cetak_var_not_blank($row->TAHUN_PEMBUATAN, "-"),
                        "nopol_registrasi_hb" => $this->__is_cetak_var_not_blank($row->NOPOL_REGISTRASI, "-"),
                        "jenis_bukti_hb" => $this->__is_cetak_var_not_blank($row->N_JENIS_BUKTI, "-"),
                        "asal_usul_harta_hb" => $this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-"),
                        "atas_nama_hb" => $this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama($row->ATAS_NAMA), "-"),
                        "pemanfaatan_harta_hb" => $this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-"),
                        "ket_lainnya_hb" => $this->__get_cetak_atas_nama($row->KET_LAINNYA, "-"),
                        "nilai_lama_hb" => 'Rp. ' . number_rupiah($row->NILAI_LAMA),
                        "nilai_pelaporan_hb" => 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN),
                        "keterangan_hb" => $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy),
                            ], TRUE);
                }
                unset($record);
                $current_execution++;
            }
            $table_string = $this->load->view('ikhtisar/tbl_data_harta_bergerak', [
                "rows" => $str_tr
                    ], TRUE);

            return $table_string;
        }
        return "";
    }

    protected function __get_data_cetak_v2($ID_LHKPN, $lhkpn_ver = FALSE) {
        $data = array();

        $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);

        $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
        $data['KELUARGA'] = $this->__arrange_cetak_data_keluarga_v2($ID_LHKPN, $lhkpn_ver);
        $data['JABATAN'] = $this->__arrange_cetak_data_jabatan_v2($ID_LHKPN);
        $data['HARTA_TDK_BEGERAK'] = $this->__arrange_cetak_data_harta_tidak_bergerak_v2($ID_LHKPN);
        $data['HARTA_BERGERAK'] = $this->__arrange_cetak_data_harta_bergerak_v2($ID_LHKPN);




        $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
        $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
        $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
        $data['HUTANG'] = $this->hutang($ID_LHKPN);

        $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
        $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
        $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);

        return $data;
    }

    function cetak_v2($ID_LHKPN = FALSE, $is_validation = FALSE) {
        $this->load->model('mlhkpnkeluarga');
//        $cek_id_user = FALSE;
        $data = array();
//        if ($is_validation) {
//            $cek_id_user = TRUE;
//        } else {
//            $cek_id_user = $this->lhkpn($ID_LHKPN);
//        }

        $cek_id_user = $this->__check_is_validation($ID_LHKPN, $is_validation);

        $id_is_ok_and_ready_print = $this->is_ever ? (bool) $ID_LHKPN : ($ID_LHKPN && $cek_id_user);

//        if ($ID_LHKPN && $cek_id_user) {
        if ($id_is_ok_and_ready_print) {

            $lhkpn_ver = FALSE;
            if ($this->is_ever) {
                $lhkpn_ver = $this->mlhkpnkeluarga->get_lhkpn_version($ID_LHKPN);
            }

//            if (!$is_validation) {
//                $data = $this->__get_data_cetak($ID_LHKPN);
//            } else {
//                $data = $this->cetak_iktisar_validation($ID_LHKPN);
//            }

            $data = $this->__get_data_cetak_v2($ID_LHKPN, $lhkpn_ver);
            $is_copy = NULL;

            if ($data['LHKPN'] && property_exists($data['LHKPN'], 'IS_COPY')) {
                $is_copy = $data['LHKPN']->IS_COPY;
            }

            list($BIDANG, $LEMBAGA, $JABATAN, $SUK, $UK) = $this->__set_data_cetak_jabatan($data['JABATAN']);

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/IkhtisarLHKPNTemplate.docx";

            /*
              $_temp_dir = FALSE, $_model_qr = FALSE, $_callable_model_function = FALSE
             */
            /*
             * penulisan qrcode

              $this->load->library('lws_qr', [
              "model_qr" => "Cqrcode",
              "callable_model_function" => "insert_cqrcode_with_filename"
              ]);
             */


            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            if (!$load_template_success) {
                throw new Exception("Gagal Mencetak Data.");
                exit;
            }
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {

                /**
                 * DATA PRIBADI
                 */
                $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NAMA_LENGKAP, "-"));
                $this->lwphpword->set_value("NHK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NHK, "-"));
                $this->lwphpword->set_value("NIK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NIK, "-"));
                $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($BIDANG, "-"));
                $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($LEMBAGA, "-"));
                $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($JABATAN, "-"));

                $this->lwphpword->set_value("TGL_LAPOR", tgl_format($data['LHKPN']->tgl_lapor));

                /**
                 * DATA KELUARGA
                 */
                $this->__arrange_cetak_data_keluarga($data['KELUARGA'], $lhkpn_ver);

                /**
                 * DATA JABATAN
                 */
                $this->__arrange_cetak_data_jabatan($data['JABATAN']);

                /**
                 * DATA HARTA TIDAK BERGERAK
                 */
                $this->__arrange_cetak_data_harta_tidak_bergerak($data['HARTA_TDK_BEGERAK'], $is_copy);

                /**
                 * DATA HARTA BERGERAK
                 */
                $this->__arrange_cetak_data_harta_bergerak($data['HARTA_BERGERAK'], $is_copy);

                /**
                 * DATA HARTA BERGERAK LAINNYA
                 */
                $this->__arrange_cetak_data_harta_bergerak_lain($data['HARTA_BERGERAK_LAIN'], $is_copy);

                /**
                 * DATA HARTA SURAT BERHARGA
                 */
                $this->__arrange_cetak_data_surat_berharga($data['HARTA_SURAT_BERHARGA'], $is_copy);

                /**
                 * DATA HARTA KAS DAN SETARA KAS
                 */
                $this->__arrange_cetak_data_kas_dan_setara_kas($data['HARTA_KAS'], $is_copy);

                /**
                 * DATA HARTA LAINNYA
                 */
                $this->__arrange_cetak_data_harta_lainnya($data['HARTA_LAINNYA'], $is_copy);

                /**
                 * DATA HUTANG
                 */
                $this->__arrange_cetak_data_hutang($data['HUTANG'], $is_copy);

                /**
                 * DATA PENERIMAAN
                 */
                $this->__arrange_cetak_data_penerimaan($data['PENERIMAAN'], $is_copy);

                /**
                 * DATA PENGELUARAN
                 */
                $this->__arrange_cetak_data_pengeluaran($data['PENGELUARAN'], $is_copy);

                /**
                 * DATA LAMPIRAN FASILITAS
                 */
                $this->__arrange_cetak_data_lampiran_fasilitas($data['FASILITAS'], $is_copy);

                $save_document_success = $this->lwphpword->save_document();

                if ($save_document_success) {

                    $output_filename = "IkhtisarHarta" . date('d-F-Y') . $ID_LHKPN;
                    $this->lwphpword->download($save_document_success, $output_filename);
                }
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        } else {
            redirect('portal/filing');
        }
    }













    /////////////////////////////////////CETAK_IKHTISAR_KLARIFIKASI////////////////////////////////////////
    function cetak_klarifikasi($ID_LHKPN = FALSE, $is_validation = FALSE, $send_session = NULL) {
        $this->load->model('mlhkpnkeluarga');
        $cek_id_user = FALSE;
        $data = array();
        if ($is_validation) {
            $data = $this->cetak_iktisar_validation($ID_LHKPN);
            $cek_id_user = TRUE;
        } else {
            $cek_id_user = $this->lhkpn($ID_LHKPN);
        }

        $id_is_ok_and_ready_print = $this->is_ever ? (bool) $ID_LHKPN : ($ID_LHKPN && $cek_id_user);

//        if ($ID_LHKPN && $cek_id_user) {
        if ($id_is_ok_and_ready_print) {

            $lhkpn_ver = FALSE;
            if ($this->is_ever) {
                $lhkpn_ver = $this->mlhkpnkeluarga->get_lhkpn_version($ID_LHKPN);
            }

            if (!$is_validation) {
                $data = $this->__get_data_cetak($ID_LHKPN);
            }
            $is_copy = NULL;
            if ($data['LHKPN'] && property_exists($data['LHKPN'], 'IS_COPY')) {
                $is_copy = $data['LHKPN']->IS_COPY;
            }

            list($BIDANG, $LEMBAGA, $JABATAN, $SUK, $UK) = $this->__set_data_cetak_jabatan($data['JABATAN']);

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/IkhtisarLHKPNTemplate.docx";

            /*
              $_temp_dir = FALSE, $_model_qr = FALSE, $_callable_model_function = FALSE
             */
            /*
             * penulisan qrcode

              $this->load->library('lws_qr', [
              "model_qr" => "Cqrcode",
              "callable_model_function" => "insert_cqrcode_with_filename"
              ]);
             */
            $this->_cnt_nilai_pelaporan_harta_bergerak($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_tidak_bergerak($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_bergerak_lain($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_surat_berharga($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_kas($ID_LHKPN, 1);
            $this->_cnt_nilai_pelaporan_harta_lainnya($ID_LHKPN);
            $this->_cnt_nilai_pelaporan_harta_hutang($ID_LHKPN);
            $this->_cnt_penerimaan_gaji($ID_LHKPN);
            $this->_cnt_penerimaan_usaha($ID_LHKPN);
            $this->_cnt_penerimaan_lainnya($ID_LHKPN);
            $this->_cnt_pengeluaran_rutin($ID_LHKPN);
            $this->_cnt_pengeluaran_harta($ID_LHKPN);
            $this->_cnt_pengeluaran_lainnya($ID_LHKPN);
            
            $total_harta = $this->jumlah_htb + $this->jumlah_hb + $this->jumlah_hbl + $this->jumlah_sb +$this->jumlah_kas + $this->jumlah_hl;
            $total_hutang = $this->jumlah_shtg;
            $total_harta_kekayaan = $total_harta - $total_hutang;

            $catatan = "Rincian harta kekayaan dalam ikhtisar LHKPN merupakan dokumen yang dicetak secara otomatis dari elhkpn.kpk.go.id. Seluruh data dan informasi yang tercantum dalam Dokumen ini sesuai dengan LHKPN yang diisi dan dikirimkan sendiri oleh Penyelenggara Negara yang bersangkutan melalui elhkpn.kpk.go.id serta tidak dapat dijadikan dasar oleh Penyelenggara Negara atau siapapun juga untuk menyatakan bahwa harta yang bersangkutan tidak terkait tindak pidana. \n\nApabila dikemudian hari terdapat harta kekayaan milik Penyelenggara Negara dan/atau Keluarganya yang tidak dilaporkan dalam LHKPN, maka Penyelenggara Negara wajib untuk bertanggung jawab sesuai dengan peraturan perundang-undangan yang berlaku.";

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => "PHK-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            $qr_content_data = json_encode((object) [
                "data" => [
                    (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $data['PRIBADI']->NAMA_LENGKAP],
                    (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data['PRIBADI']->NHK == NULL ? '-' : $data['PRIBADI']->NHK],
                    (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $BIDANG],
                    (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $JABATAN],
                    (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $LEMBAGA],
                    (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => "Klarifikasi - " . show_jenis_laporan_khusus($data['LHKPN']->JENIS_LAPORAN, $data['LHKPN']->tgl_lapor, tgl_format($data['LHKPN']->tgl_lapor))],
                    (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($data['LHKPN']->tgl_lapor)],
                    (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-")],
                    (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-")],
                    (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-")],
                    (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-")],
                    (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-")],
                    (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-")],
                    (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_hutang), "-")],
                    (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => $this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($total_harta_kekayaan), "-")],
                    (object) ["tipe" => '1', "judul" => "Catatan", "isi" => $catatan],
                ],
                "encrypt_data" => $data['PRIBADI']->ID_LHKPN . "phk",
                "id_lhkpn" => $ID_LHKPN,
                "judul" => "Pengumuman/Ikhtisar Harta Kekayaan Penyelenggara Negara",
                "tgl_surat" => date('Y-m-d'),
            ]);

           

            $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $data['LHKPN']->ID_LHKPN . ".png");
 
            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.jpeg" => $qr_image_location));


//             $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            if (!$load_template_success) {
                throw new Exception("Gagal Mencetak Data.");
                exit;
            }
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {

                /**
                 * DATA PRIBADI
                 */
                if ($data['PRIBADI']->JENIS_KELAMIN != ''){
                    if ($data['PRIBADI']->JENIS_KELAMIN == '1') {
                        $jenkel = 'Laki Laki';
                    }
                    elseif ($data['PRIBADI']->JENIS_KELAMIN == '2') {
                        $jenkel = 'Perempuan';
                    }
                    else{
                        $jenkel = $data['PRIBADI']->JENIS_KELAMIN;
                    }
                }

                $alamat_rumah = $this->__is_cetak_var_not_blank($data['PRIBADI']->ALAMAT_RUMAH, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->KECAMATAN, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->KABKOT, "-").", ".$this->__is_cetak_var_not_blank($data['PRIBADI']->PROVINSI, "-");

                $this->lwphpword->set_value("NAMA_LENGKAP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NAMA_LENGKAP, "-"));
                $this->lwphpword->set_value("NHK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NHK, "-"));
                $this->lwphpword->set_value("NIK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NIK, "-"));
                $this->lwphpword->set_value("KK", $this->__is_cetak_var_not_blank($data['PRIBADI']->NO_KK, "-"));
                $this->lwphpword->set_value("NPWP", $this->__is_cetak_var_not_blank($data['PRIBADI']->NPWP, "-"));
                $this->lwphpword->set_value("JK", $this->__is_cetak_var_not_blank($jenkel, "-"));
                $this->lwphpword->set_value("TTL", $this->__is_cetak_var_not_blank($data['PRIBADI']->TEMPAT_LAHIR, "-").'/'.$this->__is_cetak_var_not_blank(tgl_format($data['PRIBADI']->TANGGAL_LAHIR), "-"));
                $this->lwphpword->set_value("STATUS", $this->__is_cetak_var_not_blank($data['PRIBADI']->STATUS_PERKAWINAN, "-"));
                $this->lwphpword->set_value("AGAMA", $this->__is_cetak_var_not_blank($data['PRIBADI']->AGAMA, "-"));
                $this->lwphpword->set_value("ALAMAT", $this->__is_cetak_var_not_blank($alamat_rumah, "-"));
                $this->lwphpword->set_value("NOHP", $this->__is_cetak_var_not_blank($data['PRIBADI']->HP, "-"));
                $this->lwphpword->set_value("EMAIL", $this->__is_cetak_var_not_blank($data['PRIBADI']->EMAIL_PRIBADI, "-"));
                $this->lwphpword->set_value("BIDANG", $this->__is_cetak_var_not_blank($BIDANG, "-"));
                $this->lwphpword->set_value("LEMBAGA", $this->__is_cetak_var_not_blank($LEMBAGA, "-"));
                $this->lwphpword->set_value("JABATAN", $this->__is_cetak_var_not_blank($JABATAN, "-"));
                $this->lwphpword->set_value("SUK", $this->__is_cetak_var_not_blank($SUK, "-"));
                $this->lwphpword->set_value("UK", $this->__is_cetak_var_not_blank($UK, "-"));


                $this->lwphpword->set_value("TGL_LAPOR", $this->__is_cetak_var_not_blank(tgl_format($data['LHKPN']->tgl_lapor), "-"));
                $this->lwphpword->set_value("TGL_KIRIM", $this->__is_cetak_var_not_blank(tgl_format($data['LHKPN']->tgl_kirim_final), "-"));

                $tgl_cetak = date('d/m/Y');
                $thn_lapor = date_format(date_create($data['LHKPN']->tgl_lapor),'Y');


                $jenis_laporan = "Klarifikasi";

                if($data['LHKPN']->STATUS==0){
                    $status_lhkpn = "Draft";
                }elseif($data['LHKPN']->STATUS==1){
                    $status_lhkpn = "Proses Verifikasi";
                }elseif($data['LHKPN']->STATUS==2){
                    $status_lhkpn = "Perlu Perbaikan";
                }elseif($data['LHKPN']->STATUS==3){
                    $status_lhkpn = "Terverifikasi Lengkap";
                }elseif($data['LHKPN']->STATUS==4){
                    $status_lhkpn = "Diumumkan Lengkap";
                }elseif($data['LHKPN']->STATUS==5){
                    $status_lhkpn = "Terverifikasi Tidak Lengkap";
                }elseif($data['LHKPN']->STATUS==6){
                    $status_lhkpn = "Diumumkan Tidak Lengkap";
                }elseif($data['LHKPN']->STATUS==7){
                    $status_lhkpn = "Ditolak";
                }else{
                    $status_lhkpn = "-";
                }

                $this->lwphpword->set_value("STATUS_LHKPN", $status_lhkpn);
                $this->lwphpword->set_value("JENIS_LAPORAN", $jenis_laporan);
                $this->lwphpword->set_value("TAHUN_LAPOR", $this->__is_cetak_var_not_blank($thn_lapor, "-"));
                $this->lwphpword->set_value("HEADER_TAHUN_LAPOR", $this->__is_cetak_var_not_blank('P-'.$thn_lapor, "-"));
                $this->lwphpword->set_value("TGL_CETAK", $tgl_cetak);



                /**
                 * DATA KELUARGA
                 */
                $get_data_keluarga = $this->write_table_data_keluarga($data['KELUARGA'],$lhkpn_ver);
                $this->lwphpword->set_xml_value("table_data_keluarga", $get_data_keluarga);

                /**
                 * DATA JABATAN
                 */
                $get_data_jabatan = $this->write_table_data_jabatan($data['JABATAN']);
                $this->lwphpword->set_xml_value("table_data_jabatan", $get_data_jabatan);

                /**
                 * DATA HARTA TIDAK BERGERAK
                 */
                $get_data_htb = $this->write_table_klarifikasi_data_htb($data['HARTA_TDK_BEGERAK'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_htb), "-"));
                $this->lwphpword->set_xml_value("table_data_htb", $get_data_htb);

                /**
                 * DATA HARTA BERGERAK
                 */
                $get_data_hb = $this->write_table_klarifikasi_data_hb($data['HARTA_BERGERAK'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hb), "-"));
                $this->lwphpword->set_xml_value("table_data_hb", $get_data_hb);

                /**
                 * DATA HARTA BERGERAK LAINNYA
                 */
                $get_data_hbl = $this->write_table_klarifikasi_data_hbl($data['HARTA_BERGERAK_LAIN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hbl), "-"));
                $this->lwphpword->set_xml_value("table_data_hbl", $get_data_hbl);

                /**
                 * DATA HARTA SURAT BERHARGA ------------------ (terdapat kendala di local)
                 */
                $get_data_sb = $this->write_table_klarifikasi_data_sb($data['HARTA_SURAT_BERHARGA'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_sb), "-"));
                $this->lwphpword->set_xml_value("table_data_sb", $get_data_sb);

                /**
                 * DATA HARTA KAS DAN SETARA KAS
                 */
                $get_data_kas = $this->write_table_klarifikasi_data_kas($data['HARTA_KAS'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_kas), "-"));
                $this->lwphpword->set_xml_value("table_data_kas", $get_data_kas);

                /**
                 * DATA HARTA LAINNYA
                 */
                $get_data_hl = $this->write_table_klarifikasi_data_hl($data['HARTA_LAINNYA'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_hl), "-"));
                $this->lwphpword->set_xml_value("table_data_hl", $get_data_hl);


                /**
                 * DATA HUTANG
                 */
                $get_data_htg = $this->write_table_klarifikasi_data_htg($data['HUTANG'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ahtg), "-"),$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_shtg), "-"));
                $this->lwphpword->set_xml_value("table_data_htg", $get_data_htg);


                /**
                 * DATA PENERIMAAN GAJI
                 */
                $get_data_penerimaan_pekerjaan = $this->write_table_klarifikasi_data_penerimaan_pekerjaan($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pg_pn), "-"),$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pg_ps), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_pekerjaan", $get_data_penerimaan_pekerjaan);

                /**
                 * DATA PENERIMAAN USAHA
                 */
                $get_data_penerimaan_usaha = $this->write_table_klarifikasi_data_penerimaan_usaha($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pu_pn), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_usaha", $get_data_penerimaan_usaha);

                /**
                 * DATA PENERIMAAN LAINNYA
                 */
                $get_data_penerimaan_lainnya = $this->write_table_klarifikasi_data_penerimaan_lainnya($data['PENERIMAAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pl_pn), "-"));
                $this->lwphpword->set_xml_value("table_data_penerimaan_lainnya", $get_data_penerimaan_lainnya);

                /**
                 * DATA PENGELUARAN RUTIN
                 */
                $get_data_pengeluaran_rutin = $this->write_table_klarifikasi_data_pengeluaran_rutin($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pr), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_rutin", $get_data_pengeluaran_rutin);

                /**
                 * DATA PENGELUARAN HARTA
                 */
                $get_data_pengeluaran_harta = $this->write_table_klarifikasi_data_pengeluaran_harta($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_ph), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_harta", $get_data_pengeluaran_harta);

                /**
                 * DATA PENGELUARAN LAINNYA
                 */
                $get_data_pengeluaran_lainnya = $this->write_table_klarifikasi_data_pengeluaran_lainnya($data['PENGELUARAN'],$is_copy,$this->__is_cetak_var_not_blank('Rp. ' . number_rupiah($this->jumlah_pl), "-"));
                $this->lwphpword->set_xml_value("table_data_pengeluaran_lainnya", $get_data_pengeluaran_lainnya);


                /**
                 * DATA LAMPIRAN FASILITAS
                 */
                $get_data_lampiran_fasilitas = $this->write_table_klarifikasi_data_lampiran_fasilitas($data['FASILITAS'],$is_copy);
                $this->lwphpword->set_xml_value("table_data_lampiran_fasilitas", $get_data_lampiran_fasilitas);

                /**
                 * DATA TOTAL HARTA KEKAYAAN
                 */
                $get_data_total_harta = $this->write_table_data_total_harta($total_harta,$total_hutang,$total_harta_kekayaan);
                $this->lwphpword->set_xml_value("table_data_total_harta", $get_data_total_harta);


                $this->lwphpword->set_value("catatan", $catatan);

                if(empty($send_session)) {
                    $save_document_success = $this->lwphpword->save_document();
                    if ($save_document_success) {
                        $output_filename = "IkhtisarHarta-Klarifikasi" . date('d-F-Y H:i:s') . $ID_LHKPN;
                        $this->lwphpword->download($save_document_success, $output_filename);
                    }
                    unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
                }
                else {
                    $download_filename = "IkhtisarHarta-Klarifikasi" . date('d-F-Y') . $ID_LHKPN. ".docx";
                    $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $download_filename);
                    if ($save_document_success) {
                        $subject = null;
                        $pesan = null;
                        // $pesan = $this->load->view('efill/lhkpn/lhkpn_ikhtisar_harta', [
                        //     "NAMA" => $data['PRIBADI']->NAMA_LENGKAP,
                        //     "NAMA_JABATAN" => $JABATAN,
                        //     "NAMA_BIDANG" => $BIDANG,
                        //     "INST_NAMA" => $LEMBAGA,
                        //     "TAHUN" => tgl_format($data['LHKPN']->tgl_kirim)
                        // ], TRUE);

                        $ikhtisar_harta = (object) $this->sess_template_data;
                        $ikhtisar_harta->id_user = $this->session->userdata('ID_USER');
                        $ikhtisar_harta->pesan = $pesan;
                        $ikhtisar_harta->subject = $subject;
                        $ikhtisar_harta->word_location = "../../../file/wrd_gen/" . $save_document_success->document_name;

                        $this->__send_session_kirim_lhkpn($ID_LHKPN, $ikhtisar_harta);
                        unset($ikhtisar_harta);
                        echo '1';
                    }
                }
            }
            $temp_dir = APPPATH."../images/qrcode/";
            $qr_image = "tes_qr2-" . $data['LHKPN']->ID_LHKPN . ".png";
            unlink($temp_dir.$qr_image);
        } else {
            redirect('portal/filing');
        }
    }


    /////////////////////coding evan/////////////////////////////////

    protected function write_table_klarifikasi_data_htb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>DATA HARTA</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>TANAH / BANGUNAN</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00266A7F" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00266A7F" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Jalan / No </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JALAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kel. / Desa </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KEL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kecamatan </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KEC, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Kab. / Kota </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KAB_KOT, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Prov. / Negara </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PROV, "-") . ' / ' . $this->__is_cetak_var_not_blank($row->NAMA_NEGARA, "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="600" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tanah: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(number_format($row->LUAS_TANAH, 0, ",", "."), "-").' m </w:t> </w:r> <w:r> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> <w:t>2 </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Bangunan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(number_format($row->LUAS_BANGUNAN, 0, ",", "."), "-").' m </w:t> </w:r> <w:r> <w:rPr> <w:vertAlign w:val="superscript"/> </w:rPr> <w:t>2 </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1061" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis Bukti: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_BUKTI_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nomor Bukti: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NOMOR_BUKTI, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Atas Nama: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Asal Usul Harta: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Pemanfaatan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_pemanfaatan_harta($row->PEMANFAATAN), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN_OLD).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'. $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2989" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="501"/> <w:gridCol w:w="3511"/> <w:gridCol w:w="1813"/> <w:gridCol w:w="3206"/> <w:gridCol w:w="2372"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LOKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="600" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>LUAS </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1061" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KEPEMILIKAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI KLARIFIKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr> '.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_hb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>ALAT TRANSPORTASI / MESIN</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="339" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1430" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00346CCA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="000F5A47" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Merk : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->MEREK, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0014742E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Model : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->MODEL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0014742E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Pembuatan : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->TAHUN_PEMBUATAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00DB454A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">No. Pol. / Registrasi : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NOPOL_REGISTRASI, "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1220" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001F7877" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis Bukti: </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->N_JENIS_BUKTI, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00C85B29" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Asal Usul Harta: '.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00AC21C6" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Atas Nama: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00A0008A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Pemanfaatan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="002D47FA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Lainnya: </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->KET_LAINNYA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001A5C3A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN_OLD).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="003913DA" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D4569" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1024"/> <w:gridCol w:w="4321"/> <w:gridCol w:w="3689"/> <w:gridCol w:w="2369"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="339" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1430" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1220" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KEPEMILIKAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI KLARIFIKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_hbl($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HARTA BERGERAK LAINNYA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="355" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1414" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D52F0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="001B323A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jumlah : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->JUMLAH, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="004D3AD2" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Satuan : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->SATUAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="002706F6" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Ket. Lainnya : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1221" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00E11B19" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00AD40F9" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN_OLD).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="008C7439" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="0076399D" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB618C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1072"/> <w:gridCol w:w="4273"/> <w:gridCol w:w="3689"/> <w:gridCol w:w="2369"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="00246808" w:rsidRPr="00D223C7" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="355" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="00145941"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1414" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1221" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI KLARIFIKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_sb($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>SURAT BERHARGA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            if($row->NAMA){
                $jenis_surat = $row->NAMA;
            }else{
                $jenis_surat = "-";
            }
            if (strlen($row->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($row->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $row->NOMOR_REKENING;
            }
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00FA4860" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++ .' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="004D52F0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'.$jenis_surat.' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="001B323A" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Penerbit / Perusahaan </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve"> : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_PENERBIT, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="0086543E" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Cutodian / Sekuritas : '.$this->__is_cetak_var_not_blank($row->CUSTODIAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p></w:tc> <w:tc> <w:tcPr> <w:tcW w:w="692" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00DB6885" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$this->__is_cetak_var_not_blank($decrypt_norek, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="969" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="001E31B4" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00A15F40" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN_OLD).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00D46384" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="006A08B0" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="00246808" w:rsidTr="0038393B"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2989" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="005E7E13" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.'</w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="501"/> <w:gridCol w:w="3511"/> <w:gridCol w:w="2091"/> <w:gridCol w:w="2928"/> <w:gridCol w:w="2372"/> <w:gridCol w:w="2233"/> <w:gridCol w:w="1472"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="166" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1162" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="692" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">NO. REKENING / </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO. NASABAH </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="969" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI KLARIFIKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="3B3838" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="00246808" w:rsidRPr="00145941" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_kas($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>KAS / SETARA KAS</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            // with keterangan
            // $put_data = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00F5660E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="0078573A" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Keterangan : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank(trim($row->KETERANGAN), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="006E4B65" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nama Bank / Lembaga : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_BANK, "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Nomor : '.$this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA_REKENING,$row->PASANGAN_ANAK,$row->ATAS_NAMA_LAINNYA), "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="00505AAB" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00692894" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00C91A00" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_EQUIVALEN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009A0D8D" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            if (strlen($row->NAMA_BANK) >= 32){
                $decrypt_namabank = encrypt_username($row->NAMA_BANK,'d');
            } else {
                $decrypt_namabank = $row->NAMA_BANK;
            }
            if (strlen($row->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($row->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $row->NOMOR_REKENING;
            }

            $nilai_klarif = $row->NILAI_EQUIVALEN;

            //without keterangan
            $put_data = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00F5660E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Jenis : </w:t> </w:r> <w:r> <w:t>'. $this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="0078573A" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Keterangan : </w:t> </w:r> <w:r> <w:t>-</w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="006E4B65" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Nama Bank / Lembaga : </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($decrypt_namabank, "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Nomor : '.$this->__is_cetak_var_not_blank($decrypt_norek, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA_REKENING,$row->PASANGAN_ANAK,$row->ATAS_NAMA_LAINNYA), "-").' </w:t> </w:r> </w:p></w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00692894" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00C91A00" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($nilai_klarif).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009A0D8D" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }

        //membuat footer
        $put_footer = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="3774" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1210"/> <w:gridCol w:w="4235"/> <w:gridCol w:w="3734"/> <w:gridCol w:w="2411"/> <w:gridCol w:w="2269"/> <w:gridCol w:w="1495"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="394" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1379" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1216" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>INFORMASI REKENING </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="785" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI KLARIFIKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_hl($data, $is_copy,$total) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HARTA LAINNYA</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = ' <w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="317" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00165890" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1451" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->NAMA_JENIS, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="0096121E" w:rsidRPr="00D77743" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00246808" w:rsidRPr="003D156C" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t xml:space="preserve">Tahun Perolehan: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->TAHUN_PEROLEHAN_AWAL, "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1222" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009D77F3" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($this->__get_asal_usul_harta($row->ASAL_USUL), "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="007C1ED1" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN_OLD).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="004E42E8" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->NILAI_PELAPORAN).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="000A58A6" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2990" w:type="pct"/> <w:gridSpan w:val="3"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> </w:rPr> </w:pPr> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLayout w:type="fixed"/> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="973"/> <w:gridCol w:w="4456"/> <w:gridCol w:w="3753"/> <w:gridCol w:w="2408"/> <w:gridCol w:w="2269"/> <w:gridCol w:w="1495"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="317" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1451" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1222" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>ASAL USUL HARTA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="784" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI PELAPORAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="739" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI KLARIFIKASI </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="487" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_htg($data, $is_copy,$total,$saldo) {
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>HUTANG</w:t></w:r></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B25150" w:rsidTr="009927CC"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="344" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00326241" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1246" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->NAMA, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00B25150" w:rsidRPr="008F01DB" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Atas Nama : '.$this->__is_cetak_var_not_blank($this->__get_cetak_atas_nama_v2($row->ATAS_NAMA,$row->PASANGAN_ANAK,$row->KET_LAINNYA), "-").' </w:t> </w:r> </w:p><w:p w:rsidR="00246808" w:rsidRPr="00D8368B" w:rsidRDefault="00246808" w:rsidP="0038393B"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Keterangan Klarifikasi  </w:t> </w:r> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:tab/> <w:t xml:space="preserve">: </w:t> </w:r> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1123" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00C70782" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->NAMA_KREDITUR, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="800" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00E06AF6" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->AGUNAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="00352172" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->AWAL_HUTANG).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B25150" w:rsidRPr="002F3325" w:rsidRDefault="00B25150" w:rsidP="00EE1272"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->SALDO_HUTANG).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="3513" w:type="pct"/> <w:gridSpan w:val="4"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00636F13" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$saldo.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1056"/> <w:gridCol w:w="3826"/> <w:gridCol w:w="3449"/> <w:gridCol w:w="2457"/> <w:gridCol w:w="2318"/> <w:gridCol w:w="2248"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="344" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1246" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1123" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA KREDITUR </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="800" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>BENTUK AGUNAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="755" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI AWAL HUTANG </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="732" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NILAI SALDO HUTANG </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_penerimaan_pekerjaan($data, $is_copy, $total_pn, $total_ps) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'A') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                    "PASANGAN" => $PM->PASANGAN,
                    "KET_PEMERIKSAAN" => $PM->KET_PEMERIKSAAN,
                ];
            }
        }

        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN DARI PEKERJAAN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {

            //masukan data ke baris
            // $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="337" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1968" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="000A5177" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PASANGAN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr> <w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="2305" w:type="pct"/><w:gridSpan w:val="2"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2694" w:type="pct"/><w:gridSpan w:val="2"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.isExist($row["KET_PEMERIKSAAN"]).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="337" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders><w:vMerge w:val="restart"/></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t>'.$no++.' </w:t></w:r></w:p><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t></w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1968" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="000A5177" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/></w:pPr><w:r><w:t>'.$row["JENIS_PENERIMAAN"].' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1329" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1365" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="005609C7" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row["PASANGAN"]).' </w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="337" w:type="pct"/><w:vMerge/></w:tcPr><w:p/></w:tc><w:tc><w:tcPr><w:tcW w:w="1968" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2694" w:type="pct"/><w:gridSpan w:val="2"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.convertCharXml(isExist($row["KET_PEMERIKSAAN"])).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2305" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total_pn.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="00636F13" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total_ps.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1036"/> <w:gridCol w:w="6044"/> <w:gridCol w:w="4082"/> <w:gridCol w:w="4192"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="337" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1968" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>PENYELENGGARA NEGARA </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>PASANGAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_penerimaan_usaha($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'B') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                    "KET_PEMERIKSAAN" => $PM->KET_PEMERIKSAAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN DARI USAHA DAN KEKAYAAN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            // $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="340" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders><w:vMerge w:val="restart"/></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t>'.$no++.' </w:t></w:r></w:p><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t></w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2256" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:t>'.$row["JENIS_PENERIMAAN"].' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2404" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="right"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="340" w:type="pct"/><w:vMerge/></w:tcPr><w:p/></w:tc><w:tc><w:tcPr><w:tcW w:w="2256" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2404" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.convertCharXml(isExist($row["KET_PEMERIKSAAN"])).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1045"/> <w:gridCol w:w="6927"/> <w:gridCol w:w="7382"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL PENERIMAAN KAS </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_penerimaan_lainnya($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PM) {
            if (is_array($PM)) {
                $PM = (object) $PM;
            }
            if ($PM->GROUP_JENIS == 'C') {
                $PN_A[] = [
                    "PN" => $PM->PN,
                    "JENIS_PENERIMAAN" => $PM->JENIS_PENERIMAAN,
                    "KET_PEMERIKSAAN" => $PM->KET_PEMERIKSAAN,
                ];
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENERIMAAN LAINNYA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            // $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:t>'.$row["JENIS_PENERIMAAN"].' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="340" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders><w:vMerge w:val="restart"/></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t>'.$no++.' </w:t></w:r></w:p><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t></w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2256" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:t>'.$row["JENIS_PENERIMAAN"].' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2404" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00391C63" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="right"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row["PN"]).' </w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="340" w:type="pct"/><w:vMerge/></w:tcPr><w:p/></w:tc><w:tc><w:tcPr><w:tcW w:w="2256" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2404" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.convertCharXml(isExist($row["KET_PEMERIKSAAN"])).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1045"/> <w:gridCol w:w="6927"/> <w:gridCol w:w="7382"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="340" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2256" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENERIMAAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL PENERIMAAN KAS </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_pengeluaran_rutin($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'A') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN</w:t></w:r> </w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN RUTIN</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            // $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="365" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders><w:vMerge w:val="restart"/></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t>'.$no++.' </w:t></w:r></w:p><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t></w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2243" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/></w:pPr><w:r><w:t>'.$row->JENIS_PENGELUARAN.' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2392" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="right"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row->JML).' </w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="365" w:type="pct"/><w:vMerge/></w:tcPr><w:p/></w:tc><w:tc><w:tcPr><w:tcW w:w="2243" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2392" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.convertCharXml(isExist($row->KET_PEMERIKSAAN)).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_pengeluaran_harta($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'B') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN HARTA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            // $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="365" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders><w:vMerge w:val="restart"/></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t>'.$no++.' </w:t></w:r></w:p><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t></w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2243" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/></w:pPr><w:r><w:t>'.$row->JENIS_PENGELUARAN.' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2392" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="right"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row->JML).' </w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="365" w:type="pct"/><w:vMerge/></w:tcPr><w:p/></w:tc><w:tc><w:tcPr><w:tcW w:w="2243" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2392" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.convertCharXml(isExist($row->KET_PEMERIKSAAN)).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_pengeluaran_lainnya($data, $is_copy, $total) {
        $PN_A = array();
        foreach ($data as $PNG) {
            if (is_array($PNG)) {
                $PNG = (object) $PNG;
            }
            if ($PNG->GROUP_JENIS == 'C') {
                $PN_A[] = $PNG;
            }
        }
        $put_data_row = "";
        $no = 1;
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        $sub = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="1"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="720"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>PENGELUARAN LAINNYA</w:t></w:r></w:p>';
        foreach ($PN_A as $key => $row) {
            //masukan data ke baris
            // $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$row->JENIS_PENGELUARAN.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> </w:pPr> <w:r> <w:t>Rp. ' . number_rupiah($row->JML).' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="00B56D76"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="365" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders><w:vMerge w:val="restart"/></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t>'.$no++.' </w:t></w:r></w:p><w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="center"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val="id-ID"/></w:rPr><w:t></w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2243" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00235AB3" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/></w:pPr><w:r><w:t>'.$row->JENIS_PENGELUARAN.' </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2392" w:type="pct"/><w:tcBorders><w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00B56D76" w:rsidRPr="00BC7032" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:jc w:val="right"/></w:pPr><w:r><w:t>Rp. ' . number_rupiah($row->JML).' </w:t></w:r></w:p></w:tc></w:tr><w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"><w:trPr><w:trHeight w:val="537"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="365" w:type="pct"/><w:vMerge/></w:tcPr><w:p/></w:tc><w:tc><w:tcPr><w:tcW w:w="2243" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:contextualSpacing/><w:jc w:val="right"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>Catatan Pemeriksaan </w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2392" w:type="pct"/></w:tcPr><w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"><w:pPr><w:pStyle w:val="ListParagraph"/><w:ind w:left="0"/><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr></w:pPr><w:r w:rsidRPr="0088253E"><w:rPr><w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/><w:lang w:val="id-ID"/></w:rPr><w:t>'.convertCharXml(isExist($row->KET_PEMERIKSAAN)).' </w:t></w:r></w:p></w:tc></w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat footer
        $put_footer = '<w:tr w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="2596" w:type="pct"/> <w:gridSpan w:val="2"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="0088253E" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="right"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>Sub Total </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2404" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="0096121E" w:rsidRPr="009927CC" w:rsidRDefault="0096121E" w:rsidP="0096121E"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="right"/> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r> <w:rPr> <w:lang w:val="id-ID"/> </w:rPr> <w:t>'.$total.' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
        //membuat header dan masukan data yg telah dibuat
        $data_output = $spasi.$sub.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1121"/> <w:gridCol w:w="6888"/> <w:gridCol w:w="7345"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="00B56D76"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="365" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:lastRenderedPageBreak/> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2243" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>JENIS PENGELUARAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="2392" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>TOTAL NILAI PENGELUARAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.$put_footer.' </w:tbl>';
        return $data_output;
    }

    protected function write_table_klarifikasi_data_lampiran_fasilitas($data, $is_copy) {
        $put_data_row = "";
        $no = 1;
        $bab = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/> </w:rPr></w:pPr></w:p> <w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"> <w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr></w:pPr> <w:r><w:rPr><w:b/><w:lang w:val="id-ID"/></w:rPr><w:t>LAMPIRAN FASILITAS</w:t></w:r> </w:p>';
        $spasi = '<w:p w:rsidR="00246808" w:rsidRDefault="00246808" w:rsidP="00246808"><w:pPr><w:pStyle w:val="ListParagraph"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:ind w:left="360"/><w:rPr><w:lang w:val="id-ID"/></w:rPr></w:pPr></w:p>';
        foreach ($data as $key => $row) {
            //masukan data ke baris
            $put_data = '<w:tr w:rsidR="00B56D76" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="006A0D67" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> <w:jc w:val="center"/> </w:pPr> <w:r> <w:t>'.$no++.' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Jenis : '.$this->__is_cetak_var_not_blank($row->JENIS_FASILITAS, "-").' </w:t> </w:r> </w:p> <w:p w:rsidR="00B56D76" w:rsidRPr="0008004D" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>Keterangan : '.$this->__is_cetak_var_not_blank($row->KETERANGAN, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="00C83E31" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->PEMBERI_FASILITAS, "-").' </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> </w:tcPr> <w:p w:rsidR="00B56D76" w:rsidRPr="0049016F" w:rsidRDefault="00B56D76" w:rsidP="00B56D76"> <w:pPr> <w:pStyle w:val="ListParagraph"/> <w:ind w:left="0"/> </w:pPr> <w:r> <w:t>'.$this->__is_cetak_var_not_blank($row->KET_PEMERIKSAAN, "-").' </w:t> </w:r> </w:p> </w:tc> </w:tr>';
            $put_data_row = $put_data_row . $put_data;
        }
        //membuat header dan masukan data yg telah dibuat
        $data_output = $bab.'<w:tbl> <w:tblPr> <w:tblStyle w:val="TableGrid"/> <w:tblW w:w="5000" w:type="pct"/> <w:tblBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:left w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:right w:val="single" w:sz="12" w:space="0" w:color="auto"/> </w:tblBorders> <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/> </w:tblPr> <w:tblGrid> <w:gridCol w:w="1011"/> <w:gridCol w:w="4599"/> <w:gridCol w:w="4975"/> <w:gridCol w:w="4769"/> </w:tblGrid> <w:tr w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidTr="0088253E"> <w:trPr> <w:trHeight w:val="537"/> <w:tblHeader/> </w:trPr> <w:tc> <w:tcPr> <w:tcW w:w="329" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NO </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1498" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>URAIAN </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1620" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>NAMA PIHAK PEMBERI FASILITAS </w:t> </w:r> </w:p> </w:tc> <w:tc> <w:tcPr> <w:tcW w:w="1553" w:type="pct"/> <w:tcBorders> <w:top w:val="single" w:sz="12" w:space="0" w:color="auto"/> <w:bottom w:val="double" w:sz="12" w:space="0" w:color="auto"/> </w:tcBorders> <w:shd w:val="clear" w:color="auto" w:fill="4A442A" w:themeFill="background2" w:themeFillShade="40"/> <w:vAlign w:val="center"/> </w:tcPr> <w:p w:rsidR="0088253E" w:rsidRPr="0088253E" w:rsidRDefault="0088253E" w:rsidP="0088253E"> <w:pPr> <w:contextualSpacing/> <w:jc w:val="center"/> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> </w:pPr> <w:r w:rsidRPr="0088253E"> <w:rPr> <w:rFonts w:ascii="Calibri" w:eastAsia="Calibri" w:hAnsi="Calibri" w:cs="Times New Roman"/> <w:b/> <w:lang w:val="id-ID"/> </w:rPr> <w:t>KETERANGAN PEMERIKSAAN </w:t> </w:r> </w:p> </w:tc> </w:tr>'.$put_data_row.' </w:tbl>';
        return $data_output;
    }

}
