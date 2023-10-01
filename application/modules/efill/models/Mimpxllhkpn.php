<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mimpxllhkpn extends CI_Model {

    const KODE_JENIS_HARTA_PELEPASAN = '30';

    public $joinMATA_UANG = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
            // ['table' => 'M_JENIS_HARTA'      , 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = T_LHKPN_HARTA_KAS.KODE_JENIS', 'join' => 'left'],
    ];
    public $joinHARTA_TIDAK_BERGERAK = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
        ['table' => 'M_AREA as area', 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV', 'join' => 'left'],
    ];
    public $joinHARTA_BERGERAK = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
    ];
    public $joinHARTA_HUTANG = [
        ['table' => 'm_jenis_hutang', 'on' => 'KODE_JENIS  = ID_JENIS_HUTANG'],
    ];
    public $joinHARTA_PERABOTAN = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = t_imp_xl_lhkpn_harta_bergerak_lain.KODE_JENIS', 'join' => 'left'],
    ];
    public $joinHARTA_SURAT = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = t_imp_xl_lhkpn_harta_surat_berharga.KODE_JENIS', 'join' => 'left'],
    ];
    public $joinHARTA_KAS = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = t_imp_xl_lhkpn_harta_kas.KODE_JENIS', 'join' => 'left'],
    ];
    public $joinHARTA_LAINNYA = [
        ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ['table' => 'M_JENIS_HARTA', 'on' => 'M_JENIS_HARTA.ID_JENIS_HARTA = t_imp_xl_lhkpn_harta_lainnya.KODE_JENIS', 'join' => 'left'],
    ];
    public $table = 't_imp_xl_lhkpn';
    private $__jenis_harta_bergerak = array(
        "2",
        "3",
        "4",
        "6",
        "7",
    );
    private $__jenis_harta_bergerak_lainnya = array(
        "9",
        "10",
        "11",
        "12",
        "13",
        "14",
    );
    private $__jenis_harta_surat_berharga = array(
        "15",
        "17",
    );
    private $__jenis_harta_kas_dan_setara_kas = array(
        "18",
        "19",
        "20",
        "21",
        "22",
    );
    private $__jenis_harta_lainnya = array(
        "23",
        "24",
        "25",
        "26",
        "27",
        "28",
        "29",
        "30",
    );

    public function __construct() {
        parent::__construct();
    }

    private function __check_integer_data($data_insert, $int_data_field) {
        foreach ($int_data_field as $int_field) {
            if (array_key_exists($int_field, $data_insert)) {
                $data_insert[$int_field] = intval($data_insert[$int_field]);
                if ($data_insert[$int_field] <= 0) {
                    unset($data_insert[$int_field]);
                }
            }
        }
        return $data_insert;
    }

    public function add_new($jenis_laporan, $id_pn, $tgl_lapor, $tgl_kirim, $nip, $entry_via, $status, $USERNAME) {
        $this->db->insert($this->table, array(
            'jenis_laporan' => $jenis_laporan,
            'id_pn' => $id_pn,
            'tgl_lapor' => $tgl_lapor,
            'tgl_kirim' => $tgl_kirim,
            'nip' => $nip,
            'entry_via' => $entry_via,
            'status' => $status,
            'USERNAME_ENTRI' => $USERNAME
        ));

        return $this->db->insert_id();
    }

    private function __add_data_pribadi($gelar_depan, $nama_lengkap, $gelar_belakang, $jenis_kelamin, $nik, $no_kk, $npwp, $email_pribadi, $telpon_rumah, $hp, $jabatan, $jabatan_lainnya, $eselon, $id_agama, $id_lhkpn_temp, $agama, $alamat_tinggal, $tempat_lahir, $tanggal_lahir, $foto) {
        $data_insert = clean_data_insert(array(
            "id_imp_xl_lhkpn" => $id_lhkpn_temp,
            "GELAR_DEPAN" => $gelar_depan,
            "NAMA_LENGKAP" => $nama_lengkap,
            "GELAR_BELAKANG" => $gelar_belakang,
            "JENIS_KELAMIN" => $jenis_kelamin,
            "NIK" => $nik,
            "NO_KK" => $no_kk,
            "NPWP" => $npwp,
            "EMAIL_PRIBADI" => $email_pribadi,
            "TELPON_RUMAH" => $telpon_rumah,
            "HP" => $hp,
            "JABATAN" => $jabatan,
            "JABATAN_LAINNYA" => $jabatan_lainnya,
            "ESELON" => $eselon,
            "ID_AGAMA" => $id_agama,
            "AGAMA" => $agama,
            "FOTO" => $foto,
            "TEMPAT_LAHIR" => $tempat_lahir,
            "TANGGAL_LAHIR" => $tanggal_lahir,
            "ALAMAT_RUMAH" => $alamat_tinggal,
        ));

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_data_pribadi', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    private function __evaluate_insert_value($array_keys = FALSE, $array_data = FALSE) {
        if (!$array_keys || !is_array($array_data) || (is_array($array_data) && (is_null($array_data) || empty($array_data)))) {
            return FALSE;
        }

        foreach ($array_keys as $key => $field_name) {
            if (!in_array($field_name, array_keys($array_data))) {
                $array_data[$field_name] = NULL;
            }
        }
        return $array_data;
    }

    public function add_data_pribadi($data_pribadi = FALSE, $id_lhkpn_temp = FALSE, $foto = NULL) {
        $insert_response = FALSE;
        if ($data_pribadi && !empty($data_pribadi) && $id_lhkpn_temp) {

            $id_agama = NULL;
            if (array_key_exists("agama", $data_pribadi)) {
                $id_agama = $this->Magama->get_id_agama_by_nama($data_pribadi["agama"]);
            }
            try {

                $array_keys = array("gelar_depan",
                    "nama",
                    "gelar_belakang",
                    "jenis_kelamin",
                    "nik",
                    "no_kk",
                    "npwp",
                    "email_pribadi",
                    "telpon_rumah",
                    "hp",
                    "jabatan",
                    "jabatan_lainnya",
                    "eselon",
                    "agama",
                    "alamat_tinggal",
                    "tempat_lahir",
                    "tanggal_lahir");

                if ($data_pribadi = $this->__evaluate_insert_value($array_keys, $data_pribadi)) {

                    $insert_response = $this->__add_data_pribadi(
                            $data_pribadi["gelar_depan"], $data_pribadi["nama"], $data_pribadi["gelar_belakang"], $data_pribadi["jenis_kelamin"], $data_pribadi["nik"], $data_pribadi["no_kk"], $data_pribadi["npwp"], $data_pribadi["email_pribadi"], $data_pribadi["telpon_rumah"], $data_pribadi["hp"], $data_pribadi["jabatan"], $data_pribadi["jabatan_lainnya"], $data_pribadi["eselon"], $id_agama, $id_lhkpn_temp, $data_pribadi["agama"], $data_pribadi["alamat_tinggal"], $data_pribadi["tempat_lahir"], $data_pribadi["tanggal_lahir"], $foto
                    );
                }
            } catch (Exception $ex) {
                
            }
        }
        return $insert_response;
    }

    private function __add_data_jabatan(
    $id_lhkpn_temp, $inst_satkerkd, $uk_id, $suk_id, $id_jabatan, $alamat_kantor, $lembaga_detected, $unit_kerja_detected, $sub_unit_kerja_detected, $jabatan_detected, $eselon, $deskripsi_jabatan
    ) {
        if (is_numeric($eselon)) {
            $eselon_from_ereg = $eselon;
        }
        else{
            $eselon_from_ereg = NULL;
        }
        $data_insert = clean_data_insert(array(
            "id_imp_xl_lhkpn" => $id_lhkpn_temp,
            "DESKRIPSI_JABATAN" => $deskripsi_jabatan,
            "ESELON" => $eselon_from_ereg,
            "LEMBAGA" => $inst_satkerkd,
            "UNIT_KERJA" => $uk_id,
            "SUB_UNIT_KERJA" => $suk_id,
            "ID_JABATAN" => $id_jabatan,
            "ALAMAT_KANTOR" => $alamat_kantor,
            "LEMBAGA_TERDETEKSI" => $lembaga_detected,
            "UK_TERDETEKSI" => $unit_kerja_detected,
            "SUK_TERDETEKSI" => $sub_unit_kerja_detected,
            "JABATAN_TERDETEKSI" => $jabatan_detected,
            "IS_PRIMARY" => '1',
        ));

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_jabatan', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }
    
    public function add_data_jabatan($T_PN_JABATAN, $data_jabatan = FALSE, $id_lhkpn_temp = FALSE) {
        $insert_response = FALSE;
        
        $eselon=$T_PN_JABATAN->ESELON;
        $inst_satkerkd = $T_PN_JABATAN->LEMBAGA;
        $uk_id = $T_PN_JABATAN->UK_ID;
        $suk_id = $T_PN_JABATAN->SUK_ID;
        $id_jabatan = $T_PN_JABATAN->ID_JABATAN;
        $deskripsi_jabatan = $T_PN_JABATAN->DESKRIPSI_JABATAN;
        
        unset($T_PN_JABATAN);
        
        if ($data_jabatan && !empty($data_jabatan) && $id_lhkpn_temp) {
            try {
                $insert_response = $this->__add_data_jabatan(
                        $id_lhkpn_temp, $inst_satkerkd, $uk_id, $suk_id, $id_jabatan, $data_jabatan["alamat_kantor"], $data_jabatan["lembaga"], $data_jabatan["unit_kerja"], $data_jabatan["sub_unit_kerja"], $data_jabatan["jabatan"], $eselon, $deskripsi_jabatan
                );
            } catch (Exception $ex) {
                
            }
        }
        return $insert_response;
    }

    /**
     * Data Jabatan diambil dari e-Registration karena saat ini (28-desember-2017) sedang dilakukan persiapan
     *      menghadapi pilkada.
     * 
     * @see $this->add_data_jabatan()
     * 
     * @param type $data_jabatan
     * @param type $id_lhkpn_temp
     * @return type
     */
    public function deprecated_28_desember_2017_add_data_jabatan($data_jabatan = FALSE, $id_lhkpn_temp = FALSE) {
        $insert_response = FALSE;
        if ($data_jabatan && !empty($data_jabatan) && $id_lhkpn_temp) {

            $inst_satkerkd = $this->Minstansi->get_id_instansi_by_nama($data_jabatan["lembaga"]);

            $uk_id = FALSE;
            if ($inst_satkerkd) {
                $uk_id = $this->Munitkerja->get_uk_id_by_name_and_lembaga_id($inst_satkerkd, $data_jabatan["unit_kerja"]);
            }

            $suk_id = NULL;
            if ($uk_id) {
                $suk_id = $this->Munitkerja->get_suk_id_by_name_and_uk_id($uk_id, $data_jabatan["sub_unit_kerja"]);
            } else {
                $uk_id = NULL;
            }

            $id_jabatan = NULL;
            if ($suk_id) {
                $id_jabatan = $this->Mjabatan->get_id_jabatan_by_nama_uk_id_dan_suk_id($uk_id, $suk_id, $data_jabatan["jabatan"]);
            }


            try {
                $insert_response = $this->__add_data_jabatan(
                        $id_lhkpn_temp, $inst_satkerkd, $uk_id, $suk_id, $id_jabatan, $data_jabatan["alamat_kantor"], $data_jabatan["lembaga"], $data_jabatan["unit_kerja"], $data_jabatan["sub_unit_kerja"], $data_jabatan["jabatan"]
                );
            } catch (Exception $ex) {
                
            }
        }
        return $insert_response;
    }

    private function __add_data_keluarga($nama, $hubungan, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $alamat_rumah, $nomor_telpon, $id_lhkpn_temp = FALSE) {
        if ($tanggal_lahir == '--') {
            $tanggal_lahir = '0000-00-00';
        }
        $data_insert = clean_data_insert(array(
            "id_imp_xl_lhkpn" => $id_lhkpn_temp,
            "NAMA" => $nama,
            "HUBUNGAN" => intval($hubungan),
            "TEMPAT_LAHIR" => $tempat_lahir,
            "TANGGAL_LAHIR" => $tanggal_lahir,
            "JENIS_KELAMIN" => $jenis_kelamin,
            "PEKERJAAN" => $pekerjaan,
            "ALAMAT_RUMAH" => $alamat_rumah,
            "NOMOR_TELPON" => $nomor_telpon
        ));

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_keluarga', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_keluarga($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {

            $array_key_data_keluarga = array(
                "nama",
                "status_hubungan",
                "tempat_lahir",
                "tahun_lahir",
                "bulan_lahir",
                "tanggal_lahir",
                "jenis_kelamin",
                "pekerjaan",
                "alamat_rumah",
                "nomor_telpon",
            );

            foreach ($data as $record) {
                try {
                    if ($record = $this->__evaluate_insert_value($array_key_data_keluarga, $record)) {
                        $insert_response = $this->__add_data_keluarga(
                                $record["nama"], $record["status_hubungan"], $record["tempat_lahir"], $record["tahun_lahir"] . "-" . $record["bulan_lahir"] . "-" . $record["tanggal_lahir"], $record["jenis_kelamin"], $record["pekerjaan"], $record["alamat_rumah"], $record["nomor_telpon"], $id_lhkpn_temp
                        );
                    }
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_harta_tidak_bergerak($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("jenis_bukti"));

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_harta_tidak_bergerak', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_harta_tidak_bergerak($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_harta_tidak_bergerak(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_harta_bergerak($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("kode_jenis", "jenis_bukti", "pemanfaatan"));

        if (array_key_exists("kode_jenis", $data_insert) && array_key_exists(($data_insert["kode_jenis"] - 1), $this->__jenis_harta_bergerak))
            $data_insert["kode_jenis"] = $this->__jenis_harta_bergerak[($data_insert["kode_jenis"] - 1)];
        else
            unset($data_insert["kode_jenis"]);

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_harta_bergerak', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_harta_bergerak($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_harta_bergerak(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_harta_bergerak_lainnya($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("kode_jenis", "pemanfaatan"));

        if (array_key_exists((intval($data_insert["kode_jenis"]) - 1), $this->__jenis_harta_bergerak_lainnya))
            $data_insert["kode_jenis"] = $this->__jenis_harta_bergerak_lainnya[($data_insert["kode_jenis"] - 1)];
        else
            unset($data_insert["kode_jenis"]);

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_harta_bergerak_lain', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_harta_bergerak_lainnya($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_harta_bergerak_lainnya(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_surat_berharga($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("kode_jenis"));

        if (array_key_exists(($data_insert["kode_jenis"] - 1), $this->__jenis_harta_surat_berharga))
            $data_insert["kode_jenis"] = $this->__jenis_harta_surat_berharga[($data_insert["kode_jenis"] - 1)];
        else
            unset($data_insert["kode_jenis"]);

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_harta_surat_berharga', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_surat_berharga($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_surat_berharga(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_kas_dan_setara_kas($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("kode_jenis", "nilai_saldo", "nilai_kurs", "nilai_equivalen"));

        if (array_key_exists("kode_jenis", $data_insert) && array_key_exists(($data_insert["kode_jenis"] - 1), $this->__jenis_harta_kas_dan_setara_kas))
            $data_insert["kode_jenis"] = $this->__jenis_harta_kas_dan_setara_kas[($data_insert["kode_jenis"] - 1)];
        else
            unset($data_insert["kode_jenis"]);

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_harta_kas', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_kas_dan_setara_kas($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_kas_dan_setara_kas(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_harta_lainnya($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("kode_jenis"));

        if (array_key_exists((intval($data_insert["kode_jenis"]) - 1), $this->__jenis_harta_lainnya))
            $data_insert["kode_jenis"] = $this->__jenis_harta_lainnya[($data_insert["kode_jenis"] - 1)];
        else
            unset($data_insert["kode_jenis"]);

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_harta_lainnya', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_harta_lainnya($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_harta_lainnya(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_hutang($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert = $this->__check_integer_data($data_insert, array("kode_jenis", "awal_hutang", "saldo_hutang"));

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_hutang', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_data_hutang($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_hutang(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    private function __add_data_fasilitas($data_insert, $id_lhkpn_temp = FALSE) {
        $data_insert["id_imp_xl_lhkpn"] = $id_lhkpn_temp;

        $data_insert["jenis_fasilitas"] = $this->__kode_to_jenis_fasilitas($data_insert["kode_jenis"]);
        unset($data_insert["kode_jenis"]);

        $insert_ok = $this->db->insert('t_imp_xl_lhkpn_fasilitas', $data_insert);

        if ($insert_ok) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    private function __kode_to_jenis_fasilitas($kode_fasilitas) {
        $jenis_fasilitas = "";
        if ($kode_fasilitas && in_array($kode_fasilitas, array("1", "2", "3", "4", "5", "6"))) {
            switch ($kode_fasilitas) {
                case "1":
                case 1:
                    $jenis_fasilitas = "Rumah Dinas";
                    break;
                case "2":
                case 2:
                    $jenis_fasilitas = "Biaya Hidup";
                    break;
                case "3":
                case 3:
                    $jenis_fasilitas = "Jaminan Kesehatan";
                    break;
                case "4":
                case 4:
                    $jenis_fasilitas = "Mobil Dinas";
                    break;
                case "5":
                case 5:
                    $jenis_fasilitas = "Opsi pembelian saham/surat berharga";
                    break;
                default:
                    $jenis_fasilitas = "Lainnya";
                    break;
            }
        }
        return $jenis_fasilitas;
    }

    public function add_data_fasilitas($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_fasilitas(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public function add_data_penerimaan_tunai($data = FALSE, $data_pasangan = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $data_pasangan && !empty($data_pasangan) && $id_lhkpn_temp) {
            $insert_ok = FALSE;
            try {
                $insert_ok = $this->db->insert('t_imp_xl_lhkpn_penerimaan_kas', array(
                    "id_imp_xl_lhkpn" => $id_lhkpn_temp,
                    "NILAI_PENERIMAAN_KAS_PN" => json_encode($data),
                    "NILAI_PENERIMAAN_KAS_PASANGAN" => json_encode($data_pasangan)
                ));
            } catch (Exception $ex) {
                
            }

            return $insert_ok ? $this->db->insert_id() : FALSE;
        }
        return FALSE;
    }

    public function add_data_pengeluaran_tunai($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            $insert_ok = FALSE;
            try {
                $insert_ok = $this->db->insert('t_imp_xl_lhkpn_pengeluaran_kas', array(
                    "id_imp_xl_lhkpn" => $id_lhkpn_temp,
                    "NILAI_PENGELUARAN_KAS" => json_encode($data),
                ));
            } catch (Exception $ex) {
                
            }

            return $insert_ok ? $this->db->insert_id() : FALSE;
        }
        return FALSE;
    }

    private function __add_data_pelepasan($data_insert, $id_lhkpn_temp = FALSE) {

        $array_function_list = array(
            "harta_bergerak" => "Alat Transportasi",
            "harta_bergerak_lainnya" => "Harta Bergerak Lainnya",
            "harta_lainnya" => "Harta Lainnya",
            "harta_tidak_bergerak" => "Tanah/ Bangunan",
            "kas_dan_setara_kas" => "kas/ Setara Kas",
            "surat_berharga" => "Surat Berharga",
        );

        $array_function_data_list = array(
            "harta_bergerak" => array(
                "kode_jenis" => "5",
                "nama" => $data_insert["nama_harta"],
                "ket_lainnya" => $data_insert["keterangan"],
                "jenis_lepas" => $data_insert["jenis_pelepasan_harta"],
                "nilai_jual" => $data_insert["nilai_pelepasan"],
                "nama_pihak2" => $data_insert["nama_pihak_kedua"],
                "alamat_pihak2" => $data_insert["alamat_pihak_kedua"],
                "is_pelepasan" => "1",
            ),
            "harta_bergerak_lainnya" => array(
                "kode_jenis" => "6",
                "nama" => $data_insert["nama_harta"],
                "ket_lainnya" => $data_insert["keterangan"],
                "jenis_lepas" => $data_insert["jenis_pelepasan_harta"],
                "nilai_jual" => $data_insert["nilai_pelepasan"],
                "nama_pihak2" => $data_insert["nama_pihak_kedua"],
                "alamat_pihak2" => $data_insert["alamat_pihak_kedua"],
                "is_pelepasan" => "1",
            ),
            "harta_lainnya" => array(
                "kode_jenis" => "8",
                "nama" => $data_insert["nama_harta"],
                "keterangan" => $data_insert["keterangan"],
                "jenis_lepas" => $data_insert["jenis_pelepasan_harta"],
                "nilai_jual" => $data_insert["nilai_pelepasan"],
                "nama_pihak2" => $data_insert["nama_pihak_kedua"],
                "alamat_pihak2" => $data_insert["alamat_pihak_kedua"],
                "is_pelepasan" => "1",
            ),
            "harta_tidak_bergerak" => array(
                "keterangan" => $data_insert["nama_harta"],
                "ket_lainnya" => $data_insert["keterangan"],
                "jenis_lepas" => $data_insert["jenis_pelepasan_harta"],
                "nilai_jual" => $data_insert["nilai_pelepasan"],
                "nama_pihak2" => $data_insert["nama_pihak_kedua"],
                "alamat_pihak2" => $data_insert["alamat_pihak_kedua"],
                "is_pelepasan" => "1",
            ),
            "kas_dan_setara_kas" => array(
                "keterangan" => $data_insert["nama_harta"] . "; " . $data_insert["keterangan"],
                "is_pelepasan" => "1",
            ),
            "surat_berharga" => array(
                "NAMA_SURAT_BERHARGA" => $data_insert["nama_harta"],
                "ket_lainnya" => $data_insert["keterangan"],
                "jenis_lepas" => $data_insert["jenis_pelepasan_harta"],
                "nilai_jual" => $data_insert["nilai_pelepasan"],
                "nama_pihak2" => $data_insert["nama_pihak_kedua"],
                "alamat_pihak2" => $data_insert["alamat_pihak_kedua"],
                "is_pelepasan" => "1",
            ),
        );

        $data_pelepasan = array(
            "uraian_harta" => $data_insert["nama_harta"] . "; " . $data_insert["keterangan"],
            "jenis_pelepasan_harta" => $data_insert["jenis_pelepasan_harta"],
            "nilai_pelepasan" => $data_insert["nilai_pelepasan"],
            "nama" => $data_insert["nama_pihak_kedua"],
            "alamat" => $data_insert["alamat_pihak_kedua"],
        );

        $prefix_temp_table_pelepasan = "t_imp_xl_lhkpn_pelepasan_";
        $array_table_pelepasan_list = array(
            "harta_bergerak" => "harta_bergerak",
            "harta_bergerak_lainnya" => "harta_bergerak_lain",
            "harta_lainnya" => "harta_lainnya",
            "harta_tidak_bergerak" => "harta_tidak_bergerak",
            "kas_dan_setara_kas" => "harta_kas",
            "surat_berharga" => "harta_surat_berharga",
        );

        $method_postfix = array_search($data_insert["kategori_harta"], $array_function_list);

        $temp_table_postfix = $method_postfix ? array_search($method_postfix, array_flip($array_table_pelepasan_list)) : FALSE;

        $inserted_harta_id = FALSE;
        if (trim($data_insert["kategori_harta"]) != '' && !empty($data_insert["kategori_harta"]) && $method_postfix && method_exists($this, '__add_data_' . $method_postfix) && $temp_table_postfix) {

            /**
             * @deprecated since 04 September 2017
             * @author Lahir Wisada
             */
//            $method_name = '__add_data_' . $method_postfix;
//            $inserted_harta_id = $this->{$method_name}($array_function_data_list[$method_postfix], $id_lhkpn_temp);

            $table_temp_name = $prefix_temp_table_pelepasan . $temp_table_postfix;

            $data_pelepasan["id_imp_xl_lhkpn"] = $id_lhkpn_temp;
            $inserted_ok = FALSE;
            try {
                $insert_ok = $this->db->insert($table_temp_name, $data_pelepasan);
            } catch (Exception $ex) {
                
            }

            if ($insert_ok) {
                return $this->db->insert_id();
            }
        }

        return FALSE;
    }

    public function add_data_pelepasan($data = FALSE, $id_lhkpn_temp = FALSE) {
        if ($data && !empty($data) && $id_lhkpn_temp) {
            foreach ($data as $record) {
                try {
                    $insert_response = $this->__add_data_pelepasan(
                            $record, $id_lhkpn_temp
                    );
                } catch (Exception $ex) {
                    
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public function set_select_join_t_lhkpn_imp_xl_jabatan() {
        $this->set_select_t_lhkpn_imp_xl_jabatan();
        $this->set_join_t_lhkpn_imp_xl_jabatan();
    }

    public function set_select_t_lhkpn_imp_xl_jabatan() {
        $str_select = "t_imp_xl_lhkpn_jabatan.*, "
                . "mj.NAMA_JABATAN, mj.ID_JABATAN, "
                . "suk.SUK_NAMA, suk.SUK_ID, "
                . "muk.UK_NAMA, muk.UK_ID, "
                . "mis.INST_NAMA, mis.INST_SATKERKD ";
        $this->db->select($str_select);
    }

    public function set_join_t_lhkpn_imp_xl_jabatan() {
        $this->db->join('M_JABATAN as mj', 'mj.ID_JABATAN = t_imp_xl_lhkpn_jabatan.ID_JABATAN', 'LEFT');
        $this->db->join('M_UNIT_KERJA as muk', 'muk.UK_ID = mj.UK_ID', 'LEFT');
        $this->db->join('m_sub_unit_kerja as suk', 'suk.SUK_ID = mj.SUK_ID', 'LEFT');
        $this->db->join('M_INST_SATKER as mis', 'mis.INST_SATKERKD = t_imp_xl_lhkpn_jabatan.LEMBAGA', 'LEFT');
    }

    /**
     * common method
     */
    public function get_data_temp_impor_lhkpn() {
        
    }

}
