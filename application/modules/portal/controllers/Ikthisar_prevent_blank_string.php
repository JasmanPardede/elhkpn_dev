<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ikthisar extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
    }

	private function __clean_output($data_shown, $tag_name, $string_template = "%s"){
		if($data_shown && $data_shown != ''){
			/**
			 * @todo replace string template then return to template processor
			 */
		}
		$this->lwphpword->deleteBlock($tag_name);
	}

    private function __get_data_cetak($ID_LHKPN) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
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

        return $data;
    }

    private function __set_data_cetak_jabatan($data_jabatan) {
        $BIDANG = "-";
        $LEMBAGA = "-";
        $JABATAN = "-";
//echo count($data['JABATAN'], COUNT_RECURSIVE).'<br/><pre>';
//print_r($data['JABATAN'][0]->BDG_NAMA);
        $jb = $data_jabatan;
        if ($data_jabatan) {
            if (count($data_jabatan) == '1') {
                $BIDANG = $jb[0]->BDG_NAMA;
                $LEMBAGA = $jb[0]->INST_NAMA;
                $JABATAN = $jb[0]->DESKRIPSI_JABATAN;
            } else {
                foreach ($data_jabatan as $jb) {
                    if ($jb->IS_PRIMARY == "1") {
                        $BIDANG = $jb->BDG_NAMA;
                        $LEMBAGA = $jb->INST_NAMA;
                        $JABATAN = $jb->DESKRIPSI_JABATAN;
                        //break;
                    }
                }
            }
        }

        return array($BIDANG, $LEMBAGA, $JABATAN);
    }

    private function __get_cetak_hubungan_keluarga($index_hub_keluarga) {
        $HUBUNGAN_KELUARGA = array();
        $HUBUNGAN_KELUARGA[1] = 'ISTRI';
        $HUBUNGAN_KELUARGA[2] = 'SUAMI';
        $HUBUNGAN_KELUARGA[3] = 'ANAK TANGGUNGAN';
        $HUBUNGAN_KELUARGA[4] = 'ANAK BUKAN TANGGUNGAN';
        $HUBUNGAN_KELUARGA[5] = 'LAINNYA';
        $HUBUNGAN_KELUARGA[''] = '-';

        return $HUBUNGAN_KELUARGA[$index_hub_keluarga];
    }

    private function __get_cetak_is_copy($is_copy) {
        return $is_copy == '1' ? FALSE : TRUE;
    }

    private function __get_cetak_status_harta($index_status_harta, $is_copy) {
        $STATUS_HARTA = array();
        $STATUS_HARTA[1] = 'TETAP';
        $STATUS_HARTA[2] = 'LAMA';
        $STATUS_HARTA[3] = '-';
        $STATUS_HARTA[4] = 'LAPOR';
        $STATUS_HARTA[''] = '-';

        if ($this->__get_cetak_is_copy($is_copy)) {
            $STATUS_HARTA[3] = 'BARU';
        }

        return $STATUS_HARTA[$index_status_harta];
    }

    private function __get_cetak_atas_nama($index_atas_nama) {
        $ATAS_NAMA = array();
        $ATAS_NAMA[1] = 'PN YANG BERSANGKUTAN';
        $ATAS_NAMA[2] = 'PASANGAN / ANAK';
        $ATAS_NAMA[3] = 'LAINNYA';
        $ATAS_NAMA[''] = '';

        return $ATAS_NAMA[$index_atas_nama];
    }

    private function __get_cetak_keterangan($is_pelepasan, $jenis_pelepasan, $status_harta, $is_copy) {
        if ($is_pelepasan == '1') {
            return $jenis_pelepasan;
        }
        return $this->__get_cetak_status_harta($status_harta, $is_copy);
    }

	private function __is_cetak_var_not_blank($val, $default_value = "", $bool = FALSE){
		if($val != "" && $val != NULL && $val != FALSE){
			return $bool ? TRUE : $val;
		}
		return $bool ? FALSE : $default_value;
	}

    private function __get_cetak_nilai_lama($nilai_lama, $is_copy) {
        return $this->__get_cetak_is_copy($is_copy) ? "-" : "Rp. " . number_rupiah($nilai_lama);
    }

    private function __arrange_cetak_data_keluarga($data_keluarga) {

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
                $this->lwphpword->set_value($template_string_hubungan_fam, $this->__get_cetak_hubungan_keluarga($fam->HUBUNGAN));

                $tgl_lahir = null;
                if ($fam->TANGGAL_LAHIR) {
                    $tgl_lahir = tgl_format($fam->TANGGAL_LAHIR);
                }
                $ttl_fam = $this->__is_cetak_var_not_blank($fam->TEMPAT_LAHIR, "-") . ' , ' . $tgl_lahir . ' / ' . $this->__is_cetak_var_not_blank($fam->JENIS_KELAMIN, "-");

                $this->lwphpword->set_value($template_string_tmpt_tgl_lahir_jenis_kelamin_fam, $ttl_fam);
                $this->lwphpword->set_value($template_string_pekerjaan_fam, $this->__is_cetak_var_not_blank($fam->PEKERJAAN, "-"));
                $this->lwphpword->set_value($template_string_alamat_rumah_fam, $this->__is_cetak_var_not_blank($fam->ALAMAT_RUMAH, "-"));
            }
        }
    }

    private function __arrange_cetak_data_jabatan($data) {
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

    private function __arrange_cetak_data_harta_tidak_bergerak($data, $is_copy) {
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
                $this->lwphpword->set_value($template_string_prov_htb, $this->__is_cetak_var_not_blank($row->PROV,"-") . ' / ' . $this->__is_cetak_var_not_blank($row->NAMA_NEGARA, "-"));

                $this->lwphpword->set_value($template_string_luas_tanah_htb, $this->__is_cetak_var_not_blank($row->LUAS_TANAH, "-"));
                $this->lwphpword->set_value($template_string_luas_bangunan_htb, $this->__is_cetak_var_not_blank($row->LUAS_BANGUNAN, "-"));

                $this->lwphpword->set_value($template_string_jenis_bukti_htb, $this->__is_cetak_var_not_blank($row->JENIS_BUKTI_HARTA, "-"));
                $this->lwphpword->set_value($template_string_nomor_bukti_htb, $this->__is_cetak_var_not_blank($row->NOMOR_BUKTI, "-"));
                $this->lwphpword->set_value($template_string_atas_nama_htb, $this->__get_cetak_atas_nama($row->ATAS_NAMA));
                $this->lwphpword->set_value($template_string_asal_usul_htb, $this->__is_cetak_var_not_blank($row->ASAL_USUL_HARTA, "-"));
                $this->lwphpword->set_value($template_string_pemanfaatan_htb, $this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-"));

                $this->lwphpword->set_value($template_string_nilai_lama_htb, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_htb, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_htb, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    private function __arrange_cetak_data_harta_bergerak($data, $is_copy) {
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

                $this->lwphpword->set_value($template_string_jenis_bukti_hb, $this->__is_cetak_var_not_blank($row->JENIS_BUKTI, "-"));
                $this->lwphpword->set_value($template_string_asal_usul_harta_hb, $this->__is_cetak_var_not_blank($row->ASAL_USUL_HARTA, "-"));
                $this->lwphpword->set_value($template_string_atas_nama_hb, $this->__get_cetak_atas_nama($row->ATAS_NAMA));
                $this->lwphpword->set_value($template_string_pemanfaatan_harta_hb, $this->__is_cetak_var_not_blank($row->PEMANFAATAN_HARTA, "-"));
                $this->lwphpword->set_value($template_string_ket_lainnya_hb, $this->__is_cetak_var_not_blank($row->KET_LAINNYA, "-"));

                $this->lwphpword->set_value($template_string_nilai_lama_hb, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_hb, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_hb, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    private function __arrange_cetak_data_harta_bergerak_lain($data, $is_copy) {
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
                $template_string_keterangan_hbl = 'keterangan_hbl#' . ($key + 1);

                $template_string_asal_usul_harta_hbl = 'asal_usul_harta_hbl#' . ($key + 1);
                $template_string_nilai_lama_hbl = 'nilai_lama_hbl#' . ($key + 1);
                $template_string_nilai_pelaporan_hbl = 'nilai_pelaporan_hbl#' . ($key + 1);
                $template_string_keterangan_hbl = 'keterangan_hbl#' . ($key + 1);

                $this->lwphpword->set_value($template_string_no_hbl, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_harta_hbl, $this->__is_cetak_var_not_blank($row->JENIS_HARTA, "-"));
                $this->lwphpword->set_value($template_string_jumlah_hbl, $this->__is_cetak_var_not_blank($row->JUMLAH, "-"));
                $this->lwphpword->set_value($template_string_satuan_hbl, $this->__is_cetak_var_not_blank($row->SATUAN, "-"));
                $this->lwphpword->set_value($template_string_keterangan_hbl, $this->__is_cetak_var_not_blank($row->KETERANGAN,"-"));

                $this->lwphpword->set_value($template_string_asal_usul_harta_hbl, $this->__is_cetak_var_not_blank($row->ASAL_USUL_HARTA, "-"));
                $this->lwphpword->set_value($template_string_nilai_lama_hbl, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_hbl, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_hbl, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    private function __arrange_cetak_data_surat_berharga($data, $is_copy) {
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
                $this->lwphpword->set_value($template_string_custodian_sb, $this->__is_cetak_var_not_blank($row->CUSTODIAN,"-"));

                $this->lwphpword->set_value($template_string_nomor_rekening_sb, $this->__is_cetak_var_not_blank($row->NOMOR_REKENING, "-"));
                $this->lwphpword->set_value($template_string_asal_usul_harta_sbb, $this->__is_cetak_var_not_blank($row->ASAL_USUL_HARTA, "-"));
                $this->lwphpword->set_value($template_string_nilai_lama_sb, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_sb, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_sb, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    private function __arrange_cetak_data_kas_dan_setara_kas($data, $is_copy) {
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

                $this->lwphpword->set_value($template_string_asal_usul_harta_hksk, $this->__is_cetak_var_not_blank($row->ASAL_USUL_HARTA, "-"));
                $this->lwphpword->set_value($template_string_nilai_equivalen_hksk, 'Rp. ' . number_rupiah($row->NILAI_EQUIVALEN));
                $this->lwphpword->set_value($template_string_keterangan_hksk, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    private function __arrange_cetak_data_harta_lainnya($data, $is_copy) {
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

                $this->lwphpword->set_value($template_string_asal_usul_harta_hl, $this->__is_cetak_var_not_blank($row->ASAL_USUL_HARTA, "-"));
                $this->lwphpword->set_value($template_string_nilai_lama_hl, $this->__get_cetak_nilai_lama($row->NILAI_LAMA, $is_copy));
                $this->lwphpword->set_value($template_string_nilai_pelaporan_hl, 'Rp. ' . number_rupiah($row->NILAI_PELAPORAN));
                $this->lwphpword->set_value($template_string_keterangan_hl, $this->__get_cetak_keterangan($row->IS_PELEPASAN, $row->JENIS_PELEPASAN, $row->STATUS_HARTA, $is_copy));
            }
        }
    }

    private function __arrange_cetak_data_hutang($data, $is_copy) {
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

    private function __arrange_cetak_data_penerimaan($data, $is_copy) {
        $C_PENERIMAAN = array();
        $C_PENERIMAAN[0] = 0;
        $C_PENERIMAAN[1] = 0;
        $C_PENERIMAAN[2] = 0;
        $PN_A = array();
        $PN_B = array();
        $PN_ELSE = array();
        foreach ($data as $PM) {
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

		//gwe_dump(array("PN_A"=>$PN_A,"PN_B"=>$PN_B, "PN_ELSE"=>$PN_ELSE), TRUE);

        if ($C_PENERIMAAN[0] >= 1) {
            $this->lwphpword->clone_row('nopna', $C_PENERIMAAN[0]);
			//gwe_dump(array($PN_A), TRUE);
            foreach ($PN_A as $key => $row) {
                $template_string_nopna = 'nopna#' . ($key + 1);

                $template_string_jenis_penerimaan_a = 'jenis_penerimaan_a#' . ($key + 1);
                $template_string_pn_a = 'pn_a#' . ($key + 1);
                $template_string_pasangan_a = 'pasangan_a#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopna, ($key + 1));
                $this->lwphpword->set_value($template_string_jenis_penerimaan_a, $row->JENIS_PENERIMAAN);
                $this->lwphpword->set_value($template_string_pn_a, 'Rp. ' . number_rupiah($row->PN));
                $this->lwphpword->set_value($template_string_pasangan_a, $row->PASANGAN);
            }
        } else {
            $this->lwphpword->clone_row('nopna', 0);
            //$this->lwphpword->deleteBlock('nopna', 0);
        }

        if ($C_PENERIMAAN[1] >= 1) {
            $this->lwphpword->clone_row('nopnb', $C_PENERIMAAN[1]);
            foreach ($PN_B as $key => $row) {
                $template_string_nopnb = 'nopnb#' . ($key + 1);
				$template_string_jenis_penerimaan_b = 'jenis_penerimaan_b#' . ($key + 1);
                $template_string_pn_b = 'pn_b#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopnb, ($key + 1));
				$this->lwphpword->set_value($template_string_jenis_penerimaan_b, $row->JENIS_PENERIMAAN);
                $this->lwphpword->set_value($template_string_pn_b, 'Rp. ' . number_rupiah($row->PN));
            }
        } else {
            $this->lwphpword->clone_row('nopnb', 0);
        }

        if ($C_PENERIMAAN[2] >= 1) {
            $this->lwphpword->clone_row('nopnc', $C_PENERIMAAN[2]);
            foreach ($PN_ELSE as $key => $row) {
                $template_string_nopnc = 'nopnc#' . ($key + 1);
                $template_string_pn_c = 'pn_c#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopnc, ($key + 1));
                $this->lwphpword->set_value($template_string_pn_c, 'Rp. ' . number_rupiah($row->PN));
            }
        } else {
            $this->lwphpword->clone_row('nopnc', 0);
        }
    }

    private function __arrange_cetak_data_pengeluaran($data, $is_copy) {
        $PENGELUARAN = $data;
        $C_PENGELUARAN = array();
        $C_PENGELUARAN[0] = 0;
        $C_PENGELUARAN[1] = 0;
        $C_PENGELUARAN[2] = 0;

        $CP_A = [];
        $CP_B = [];
        $CP_ELSE = [];
        foreach ($data as $PNG) {
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

        if ($C_PENGELUARAN[0] > 1) {
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
            $this->lwphpword->clone_row('nopppr', 0);
        }

        if ($C_PENGELUARAN[1] > 1) {
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
            $this->lwphpword->clone_row('noppph', 0);
        }

        if ($C_PENGELUARAN[2] > 1) {
            $this->lwphpword->clone_row('nopppl', $C_PENGELUARAN[1]);
            foreach ($CP_ELSE as $key => $row) {
                $template_string_nopppl = 'nopppl#' . ($key + 1);

                $template_string_pppl_jen = 'pppl_jen#' . ($key + 1);
                $template_string_pppl_jml = 'pppl_jml#' . ($key + 1);

                $this->lwphpword->set_value($template_string_nopppl, ($key + 1));
                $this->lwphpword->set_value($template_string_pppl_jen, $row->JENIS_PENGELUARAN);
                $this->lwphpword->set_value($template_string_pppl_jml, 'Rp. ' . number_rupiah($row->JML));
            }
        } else {
            $this->lwphpword->clone_row('nopppl', 0);
        }
    }

    private function __arrange_cetak_data_lampiran_fasilitas($data, $is_copy){
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
                $this->lwphpword->set_value($template_string_jenis_fasilitas_fls, $row->JENIS_FASILITAS);
                $this->lwphpword->set_value($template_string_keterangan_uraian_fls, $row->KETERANGAN);
                $this->lwphpword->set_value($template_string_pemberi_fasilitas_fls, $row->PEMBERI_FASILITAS);
                $this->lwphpword->set_value($template_string_keterangan_fls, $row->KETERANGAN);
            }
        }
    }

    function cetak($ID_LHKPN = FALSE) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = $this->__get_data_cetak($ID_LHKPN);
            $is_copy = NULL;

            if ($data['LHKPN'] && property_exists($data['LHKPN'], 'IS_COPY')) {
                $is_copy = $data['LHKPN']->IS_COPY;
            }

            list($BIDANG, $LEMBAGA, $JABATAN) = $this->__set_data_cetak_jabatan($data['JABATAN']);

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
             */
            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "callable_model_function" => "insert_cqrcode_with_filename"
            ]);

            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {

                /**
                 * DATA PRIBADI
                 */
                $this->lwphpword->set_value("NAMA_LENGKAP", $data['PRIBADI']->NAMA_LENGKAP);
                $this->lwphpword->set_value("NHK", $data['PRIBADI']->NHK);
                $this->lwphpword->set_value("BIDANG", $BIDANG);
                $this->lwphpword->set_value("LEMBAGA", $LEMBAGA);
                $this->lwphpword->set_value("JABATAN", $JABATAN);
                $this->lwphpword->set_value("TGL_LAPOR", tgl_format($data['LHKPN']->tgl_lapor));

                /**
                 * DATA KELUARGA
                 */
                $this->__arrange_cetak_data_keluarga($data['KELUARGA']);

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

                    $output_filename = "IkhtisarHarta" . date('d-F-Y') . $ID_LHKPN . ".docx";

//                    $pesan = " Silahkan Klik <a href=\"".base_url('file/wrd_gen')."/".$save_document_success."\">disini ";
//                    $subject = "Lampiran Surat Kuasa Mengumumkan LHKPN";
//
//                    $this->__send_to_mailbox($this->session->userdata('ID_USER'), $pesan, $subject);

                    //$this->lwphpword->download($save_document_success, $output_filename, 'pdf');
                    $this->lwphpword->download($save_document_success, $output_filename);
                }
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        } else {
            redirect('portal/filing');
        }
    }

    /**
     * @deprecated by Lahir Wisada Santoso
     * @see $this->cetak() above
     * @param type $ID_LHKPN
     */
    function __cetak($ID_LHKPN = FALSE) {
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
//            var_dump($data['PRIBADI']->NHK);exit;
//            echo $html;exit;
            try {
//                var_dump(file_exists(APPPATH . 'third_party/mpdf6.0/src/Mpdf.php'));exit;
                include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->AddPage();
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->lastPage();
                $pdf->Output('ikhtisar.pdf', 'I');
//                $pdf = new mPDF('c', 'A4-L');
//                $pdf->WriteHTML($html);
//                $pdf->Output();
            } catch (Exception $e) {

            }
        } else {
            redirect('portal/filing');
        }
    }

    function preview($ID_LHKPN, $KETENTUAN = 0, $OPTION = 0) {
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

    function lhkpn($ID_LHKPN) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->where('t_lhkpn.ID_PN', $this->session->userdata('ID_PN'));
        $data = $this->db->get('t_lhkpn')->row();
        return $data;
    }

    function pribadi($ID_LHKPN) {
        $this->db->select('t_lhkpn_data_pribadi.*,m_negara.KODE_ISO3,.m_negara.NAMA_NEGARA,m_area_prov.*,m_area_kab.*,t_pn.NHK');
        $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
        $this->db->group_by('t_lhkpn_data_pribadi.ID');
        $this->db->order_by('t_lhkpn_data_pribadi.ID', 'DESC');
        $this->db->where('t_lhkpn_data_pribadi.IS_ACTIVE', '1');
        $this->db->join('m_negara', 'm_negara.KODE_ISO3 = t_lhkpn_data_pribadi.KD_ISO3_NEGARA', 'LEFT');
        $this->db->join('m_area_prov', 'm_area_prov.NAME = t_lhkpn_data_pribadi.PROVINSI', 'LEFT');
        $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = t_lhkpn_data_pribadi.KABKOT', 'LEFT');
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
        $data = $this->db->get('t_lhkpn_data_pribadi')->row();
        return $data;
    }

    function keluarga($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_keluarga')->result();
        return $data;
    }

    function jabatan($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_lhkpn_jabatan.LEMBAGA', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_lhkpn_jabatan.UNIT_KERJA', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = t_lhkpn_jabatan.SUB_UNIT_KERJA', 'left');
        $this->db->join('m_bidang', ',m_bidang.BDG_ID = m_inst_satker.INST_BDG_ID');
        $this->db->group_by('t_lhkpn_jabatan.ID');
        $data = $this->db->get('t_lhkpn_jabatan')->result();
        return $data;
    }

    function harta_tidak_bergerak($ID_LHKPN) {
        $this->db->select('
        	m_jenis_bukti.*,
        	m_mata_uang.*,
        	m_negara.NAMA_NEGARA,
        	t_lhkpn_harta_tidak_bergerak.*,
        	t_lhkpn_harta_tidak_bergerak.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `t_lhkpn_harta_tidak_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_tidak_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_tidak_bergerak AS OLD WHERE OLD.ID = t_lhkpn_harta_tidak_bergerak.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_tidak_bergerak.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (t_lhkpn_harta_tidak_bergerak.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
        	m_jenis_bukti.JENIS_BUKTI AS JENIS_BUKTI_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_tidak_bergerak WHERE t_lhkpn_pelepasan_harta_tidak_bergerak.ID_HARTA = t_lhkpn_harta_tidak_bergerak.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');
        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = 'ID';
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = t_lhkpn_harta_tidak_bergerak.ID_NEGARA', 'left');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_tidak_bergerak')->result();
        return $data;
    }

    function harta_bergerak($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_bergerak';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	m_jenis_bukti.*,
        	m_jenis_bukti.JENIS_BUKTI AS N_JENIS_BUKTI,
        	t_lhkpn_harta_bergerak.*,
        	m_jenis_harta.NAMA AS JENIS_HARTA,
        	t_lhkpn_harta_bergerak.STATUS as STATUS_HARTA,
                CASE
                      WHEN `t_lhkpn_harta_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_bergerak AS OLD WHERE OLD.ID = t_lhkpn_harta_bergerak.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_bergerak.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (t_lhkpn_harta_bergerak.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_bergerak WHERE t_lhkpn_pelepasan_harta_bergerak.ID_HARTA = t_lhkpn_harta_bergerak.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_bergerak')->result();
        return $data;
    }

    function harta_bergerak_lain($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_bergerak_lain';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_bergerak_lain.*,
        	t_lhkpn_harta_bergerak_lain.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `t_lhkpn_harta_bergerak_lain`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_bergerak_lain`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_harta.NAMA as JENIS_HARTA,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_bergerak_lain AS OLD WHERE OLD.ID = t_lhkpn_harta_bergerak_lain.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_bergerak_lain.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (t_lhkpn_harta_bergerak_lain.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_bergerak_lain WHERE t_lhkpn_pelepasan_harta_bergerak_lain.ID_HARTA = t_lhkpn_harta_bergerak_lain.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_bergerak_lain')->result();
        return $data;
    }

    function harta_surat_berharga($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_surat_berharga.*,
        	t_lhkpn_harta_surat_berharga.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `t_lhkpn_harta_surat_berharga`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_surat_berharga`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_surat_berharga AS OLD WHERE OLD.ID = t_lhkpn_harta_surat_berharga.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_surat_berharga.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_surat_berharga WHERE t_lhkpn_pelepasan_harta_surat_berharga.ID_HARTA = t_lhkpn_harta_surat_berharga.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_surat_berharga')->result();
        return $data;
    }

    function harta_lainnya($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_lainnya';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_lainnya.*,
        	t_lhkpn_harta_lainnya.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `t_lhkpn_harta_lainnya`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_lainnya`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_harta.NAMA AS NAMA_JENIS,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_lainnya AS OLD WHERE OLD.ID = t_lhkpn_harta_lainnya.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_lainnya.ASAL_USUL) ) AS ASAL_USUL_HARTA,
       		(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_lainnya WHERE t_lhkpn_pelepasan_harta_lainnya.ID_HARTA = t_lhkpn_harta_lainnya.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN
       	');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_lainnya')->result();
        return $data;
    }

    function harta_kas($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_kas';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	m_mata_uang.*,
        	t_lhkpn_harta_kas.*,
        	t_lhkpn_harta_kas.STATUS AS STATUS_HARTA,
                CASE
                      WHEN `t_lhkpn_harta_kas`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_kas`.`NILAI_SALDO`
                END `NILAI_SALDO`,
        	(SELECT NILAI_SALDO FROM t_lhkpn_harta_kas AS OLD WHERE OLD.ID = t_lhkpn_harta_kas.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_kas.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_kas WHERE t_lhkpn_pelepasan_harta_kas.ID_HARTA = t_lhkpn_harta_kas.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_kas')->result();
        return $data;
    }

    function hutang($ID_LHKPN) {
        $TABLE = 't_lhkpn_hutang';
        $PK = 'ID_HUTANG';
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = ' . $TABLE . '.KODE_JENIS');
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_hutang')->result();
        return $data;
    }

    function penerimaan($ID_LHKPN) {
        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_penerimaan_kas2')->result();
        return $data;
    }

    function pengeluaran($ID_LHKPN) {
        $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
        $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_pengeluaran_kas2')->result();
        return $data;
    }

    function fasilitas($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_fasilitas')->result();
        return $data;
    }

}
