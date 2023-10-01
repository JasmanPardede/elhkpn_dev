<?php

defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * Save data hasil upload Excel Pegawai Negeri Wajib Lapor.
 * 
 * Version: 1.1.10
 * @author Lahir Wisada Santoso <lahirwisada@gmail.com>, 2017.
 *
 * This library is intended to be compatible with CI 2.x.
 */
require_once APPPATH . "/libraries/Lexcel.php";

class Save_import_lhkpn_excel {

    const JML_ROW_LEGEND = 16;

    private $excellib;
    private $enable_debugging = TRUE;
    public $excel_filename = FALSE;
    private $catch_error = FALSE;
    private $upload_import_lhkpn_excel_path = '';
    public $version_compatible = array("1.6");
    public $excel_readable = FALSE;
    public $detected_version = "2.1";

    /**
     * @todo tambahkan versi selanjutnya
     */
    private $array_version = array(
        "1.0" => "B53",
        "1.1" => "B53",
        "1.2" => "B53", //Form_Version
        "1.3" => "B53", //Form_Version
        "1.4" => "B53", //Form_Version
        "1.5" => "B53", //Form_Version
        "1.6" => "B53", //Form_Version
        "1.7" => "B53", //Form_Version
        "1.8" => "B53", //Form_Version
        "1.11" => "B53", //Form_Version
        "2.1" => "B53", //Form_Version
    );

    /**
     * @todo tambahkan versi selanjutnya
     */
    private $array_version_keluarga = array(
        "1.0" => ["E6", "E12", "E18", "E24"],
        "1.1" => ["E6", "E12", "E18", "E24"],
        "1.2" => ["H6", "H12", "H18", "H24"],
        "1.3" => ["H6", "H12", "H18", "H24"],
        "1.4" => ["H6", "H12", "H18", "H24"],
        "1.5" => ["H6", "H12", "H18", "H24"],
        "1.6" => ["H6", "H12", "H18", "H24"],
        "1.7" => ["H6", "H12", "H18", "H24"],
        "1.8" => ["H6", "H12", "H18", "H24"],
        "1.11" => ["H6", "H12", "H18", "H24"],
        "2.1" => ["H6", "H12", "H18", "H24"],
    );

    /**
     * @todo tambahkan versi selanjutnya
     * @nilai perolehan AP10, dan nilai pelaporan AX10 <+=== cth utk form v1.0 index array AP10 = 0, AX10 = 1
     */
    private $array_version_IV_1 = array(
        "1.0" => [["AP10", "AX10"], ["AP24", "AX24"], ["AP38", "AX38"]],
        "1.1" => [["AP10", "AX10"], ["AP24", "AX24"], ["AP38", "AX38"]],
        "1.2" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.3" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.4" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.5" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.6" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.7" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.8" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "1.11" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
        "2.1" => [["AP11", "AX11"], ["AP27", "AX27"], ["AP43", "AX43"]],
    );

    /**
     * Laporan harta penyelenggara negara
     * @var type
     */
    public $response_form_satu = [
        "jenis_laporan" => FALSE,
        "tahun_pelaporan_periodik" => FALSE,
        "tahun_pelaporan_khusus" => FALSE,
        "gelar_depan" => FALSE,
        "nama" => FALSE,
        "gelar_belakang" => FALSE,
        "jabatan" => FALSE,
        "unit_kerja" => FALSE,
        "sub_unit_kerja" => FALSE,
        "lembaga" => FALSE,
        "alamat_kantor" => FALSE,
        "kota_lapor" => FALSE,
        "tgl_lapor" => FALSE,
    ];

    /**
     * Data Pribadi
     */
    public $response_form_dua = [
        "gelar_depan" => FALSE,
        "nama" => FALSE,
        "gelar_belakang" => FALSE,
        "jenis_kelamin" => FALSE,
        "nik" => FALSE,
        "no_kk" => FALSE,
        "npwp" => FALSE,
        "email_pribadi" => FALSE,
        "telpon_rumah" => FALSE,
        "hp" => FALSE,
        "jabatan" => FALSE,
        "jabatan_lainnya" => FALSE,
        "eselon" => FALSE,
        "sub_unit_kerja" => FALSE,
        "unit_kerja" => FALSE,
        "lembaga" => FALSE,
        "alamat_kantor" => FALSE,
        "tempat_lahir" => FALSE,
        "tanggal_lahir" => FALSE,
        "alamat_tinggal" => FALSE,
    ];

    /**
     * Data Keluarga
     */
    public $response_data_keluarga = [];
    public $attribute_data_keluarga = [
        "nama" => FALSE,
        "status_hubungan" => FALSE,
        "tempat_lahir" => FALSE,
        "tanggal_lahir" => FALSE,
        "pekerjaan" => FALSE,
        "nomor_telpon" => FALSE,
        "alamat_rumah" => FALSE
    ];

    /**
     * Data Harta Tidak Bergerak
     */
    public $response_data_tidak_bergerak = [];
    public $attribute_data_tidak_bergerak = [
        "jalan" => FALSE,
        "kel" => FALSE,
        "kec" => FALSE,
        "kab_kot" => FALSE,
        "prov" => FALSE,
        "negara" => FALSE,
        "luas_tanah" => FALSE,
        "luas_bangunan" => FALSE,
        "jenis_bukti" => FALSE,
        "atas_nama" => FALSE,
        "asal_usul" => FALSE,
        "pemanfaatan" => FALSE,
        "nilai_perolehan" => FALSE,
        "nilai_pelaporan" => FALSE,
    ];

    /**
     * Data Harta Bergerak (Alat Transportasi dan Mesin)
     */
    public $response_data_bergerak = [];
    public $attribute_data_bergerak = [
        "kode_jenis" => FALSE,
        "merek" => FALSE,
        "model" => FALSE,
        "tahun_pembuatan" => FALSE,
        "nopol_registrasi" => FALSE,
        "jenis_bukti" => FALSE,
        "asal_usul" => FALSE,
        "atas_nama" => FALSE,
        "pemanfaatan" => FALSE,
        "ket_lainnya" => FALSE,
        "nilai_perolehan" => FALSE,
        "nilai_pelaporan" => FALSE,
    ];

    /**
     * Data Harta Bergerak Lainnya
     */
    public $response_data_bergerak_lainnya = [];
    public $attribute_data_bergerak_lainnya = [
        "kode_jenis" => FALSE,
        "nama" => FALSE,
        "jumlah" => FALSE,
        "satuan" => FALSE,
        "keterangan" => FALSE,
        "asal_usul" => FALSE,
        "nilai_perolehan" => FALSE,
        "nilai_pelaporan" => FALSE,
    ];

    /**
     * Data Harta Surat Berharga
     */
    public $response_data_surat_berharga = [];
    public $attribute_data_surat_berharga = [
        "kode_jenis" => FALSE,
        "atas_nama" => FALSE,
        "nama_penerbit" => FALSE,
        "custodian" => FALSE,
        "nomor_rekening" => FALSE,
        "asal_usul" => FALSE,
        "nilai_perolehan" => FALSE,
        "nilai_pelaporan" => FALSE,
    ];

    /**
     * Data kas dan satara kas
     */
    public $response_data_kas_setara_kas = [];
    public $attribute_data_kas_setara_kas = [
        "kode_jenis" => FALSE,
        "keterangan" => FALSE,
        "nama_bank" => FALSE,
        "nomor_rekening" => FALSE,
        "atas_nama_rekening" => FALSE,
        "asal_usul" => FALSE,
        "mata_uang" => FALSE,
        "nilai_kurs" => FALSE,
        "nilai_saldo" => FALSE,
        "nilai_equivalen" => FALSE,
    ];

    /**
     * Data harta Lainnya
     */
    public $response_data_harta_lainnya = [];
    public $attribute_data_harta_lainnya = [
        "kode_jenis" => FALSE,
        "keterangan" => FALSE,
        "asal_usul" => FALSE,
        "nilai_perolehan" => FALSE,
        "nilai_pelaporan" => FALSE,
    ];

    /**
     * Data hutang
     */
    public $response_data_hutang = [];
    public $attribute_data_hutang = [
        "kode_jenis" => FALSE,
        "atas_nama" => FALSE,
        "ket_lainnya" => FALSE,
        "nama_kreditur" => FALSE,
        "agunan" => FALSE,
        "awal_hutang" => FALSE,
        "saldo_hutang" => FALSE,
    ];

    /**
     * Data Penerimaan Kas
     */
    public $response_data_penerimaan_kas = FALSE;
    public $response_data_penerimaan_kas_pasangan = FALSE;

    /**
     * Data Pengeluaran kas
     */
    public $response_data_pengeluaran_kas = FALSE;

    /**
     * Data fasilitas
     */
    public $response_data_fasilitas = [];
    public $attribute_data_fasilitas = [
        "kode_jenis" => FALSE,
        "keterangan" => FALSE,
        "pemberi_fasilitas" => FALSE,
        "keterangan_lain" => FALSE,
    ];

    /**
     * Data Pelepasan
     */
    public $response_data_pelepasan = [];
    public $attribute_data_pelepasan = [
        "jenis_pelepasan_harta" => FALSE,
        "keterangan" => FALSE,
        "kategori_harta" => FALSE,
        "nama_harta" => FALSE,
        "nilai_pelepasan" => FALSE,
        "nama_pihak_kedua" => FALSE,
        "alamat_pihak_kedua" => FALSE,
    ];

    private function __set_debugging() {
        if ($this->enable_debugging) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }
    }

    public function get_filename() {
        return $this->excel_filename;
    }

    public function get_full_path() {
        return $this->upload_import_lhkpn_excel_path . $this->excel_filename;
    }

    private function __set_filename($filename = FALSE) {
        if ($filename) {
            $this->excel_filename = $filename;
        }
    }

    private function __configure_library($config) {
        $this->upload_import_lhkpn_excel_path = "./uploads/lhkpn_import_excel/final/";
        if (is_array($config) && !empty($config)) {
            if (in_array("filename", array_keys($config))) {
                $this->__set_filename($config["filename"]);
            }

            if (in_array("upload_import_lhkpn_excel_path", array_keys($config))) {
                $this->upload_import_lhkpn_excel_path = $config["upload_import_lhkpn_excel_path"];
            }
        }
    }

    public function __construct($config = array()) {
        $this->__set_debugging();

        $this->__configure_library($config);

        $this->set_excellib();

        $this->catch_error = FALSE;
    }

    public function set_excellib() {
        $this->excellib = new Lexcel();
    }

    public function trash_excellib() {
        unset($this->excellib);
        $this->excellib = FALSE;
    }

    function myErrorHandler($errno, $errstr, $errfile, $errline) {
        $this->catch_error = TRUE;
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }

        /* Don't execute PHP internal error handler */
        return TRUE;
    }

    public function read_data_asal_usul($active_sheet, $arr_cols, $row_index) {
        $arr_response = [];
        foreach ($arr_cols as $key => $col) {
            $cell_name = $col . "" . $row_index;
            if ($active_sheet->getCell($cell_name)->getValue() == 'TRUE') {
                $arr_response[] = $key + 1;
            }
        }
        unset($active_sheet, $arr_cols);
        return implode(",", $arr_response);
    }

    /**
     * Read Excl Version
     * @return boolean
     */
    private function readVersion() {
        //Form LHKPN-KPK-Versi 1.1
        //Form LHKPN-KPK-Versi 1.6

        $active_sheet = FALSE;
        try {
            $active_sheet = $this->excellib->setActiveSheetIndexByName("I");
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return FAlSE;
        }
        $version = FALSE;

        if ($active_sheet) {

            foreach ($this->array_version as $version_number => $version_cell) {

                $version = $active_sheet->getCell($version_cell)->getCalculatedValue() == "Form LHKPN-KPK-Versi " . $version_number;

                if ($version) {
                    $version = $version_number;
                    $this->detected_version = $version_number;
                    break;
                }
            }
        }


        return $version;
    }

    private function is_compatible() {
        $excel_version = $this->readVersion();
//        if (in_array($excel_version, $this->version_compatible)) {
//            $this->excel_readable = TRUE;
//            return TRUE;
//        }
//        return FALSE;
        $this->excel_readable = TRUE;
        return TRUE;
    }

    public function read_excel() {
        $load_excel_data_success = FALSE;
        $response = FALSE;
        $msg_error = "";
        try {
            $response = $this->excellib->load($this->get_full_path(), TRUE);
            $load_excel_data_success = TRUE;
        } catch (Exception $ex) {
            $load_excel_data_success = FALSE;
            $msg_error = $ex->getMessage();
        }

        $arr_test = NULL;

        if (!$response && $load_excel_data_success) {

            $old_error_handler = set_error_handler(array($this, "myErrorHandler"));
        } else {
//            var_dump($this->is_compatible());exit;
            if ($this->is_compatible()) {

                $this->readSheet_I();
                $this->readSheet_II();
                $this->readSheet_III();
                $this->readSheet_IV_1();
                $this->readSheet_IV_2_1();
                $this->readSheet_IV_2_2();
                $this->readSheet_IV_3();
                $this->readSheet_IV_4();
                $this->readSheet_IV_5();
                $this->readSheet_IV_6();
                $this->readSheet_V();
                $this->readSheet_VI();
                $this->readSheet_Lampiran2_fasilitas();
                $this->readSheet_Lampiran1_Penjualan_Pelepasan();
                $this->readSheet_Lampiran1_Penjualan();
                $this->readSheet_SKM();
            }
        }

        $this->trash_excellib();

        return (object) array(
                    /**
                     * Laporan harta penyelenggara negara
                     */
//                    "response_form_skm" => $this->response_data_skm,
                    /**
                     * Laporan harta penyelenggara negara
                     */
                    "response_form_satu" => $this->response_form_satu,
                    /**
                     * Data Pribadi
                     */
                    "response_form_dua" => $this->response_form_dua,
                    /**
                     * Data Keluarga
                     */
                    "response_data_keluarga" => $this->response_data_keluarga,
                    /**
                     * Data Harta Tidak Bergerak
                     */
                    "response_data_tidak_bergerak" => $this->response_data_tidak_bergerak,
                    /**
                     * Data Harta Bergerak (Alat Transportasi dan Mesin)
                     */
                    "response_data_bergerak" => $this->response_data_bergerak,
                    /**
                     * Data Harta Bergerak Lainnya
                     */
                    "response_data_bergerak_lainnya" => $this->response_data_bergerak_lainnya,
                    /**
                     * Data Harta Surat Berharga
                     */
                    "response_data_surat_berharga" => $this->response_data_surat_berharga,
                    /**
                     * Data kas dan satara kas
                     */
                    "response_data_kas_setara_kas" => $this->response_data_kas_setara_kas,
                    /**
                     * Data harta Lainnya
                     */
                    "response_data_harta_lainnya" => $this->response_data_harta_lainnya,
                    /**
                     * Data hutang
                     */
                    "response_data_hutang" => $this->response_data_hutang,
                    /**
                     * Data Penerimaan Kas
                     */
                    "response_data_penerimaan_kas" => $this->response_data_penerimaan_kas,
                    "response_data_penerimaan_kas_pasangan" => $this->response_data_penerimaan_kas_pasangan,
                    /**
                     * Data Pengeluaran kas
                     */
                    "response_data_pengeluaran_kas" => $this->response_data_pengeluaran_kas,
                    /**
                     * Data fasilitas
                     */
                    "response_data_fasilitas" => $this->response_data_fasilitas,
                    /**
                     * Data Pelepasan
                     */
                    "response_data_pelepasan" => $this->response_data_pelepasan,
        );
    }

    private function read_cell_number($active_sheet, $cellname) {
        $val = $active_sheet->getCell($cellname)->getCalculatedValue();
        $new_val = "0";
        if($val){
            $arr_val = explode(",", $val);
            $decimal = "";
            if(count($arr_val) > 1){
                $decimal = $arr_val[1];
            }
            $arr_number = explode(".", $arr_val[0]);
            $number = implode("", $arr_number);
            $new_val = $decimal != "" ? $number.".".$decimal : $number;
        }
        
        unset($active_sheet);
        return trim($new_val) != "" ? $new_val : "0";
    }

    public function data_pelepasan_filled($data) {
//        return (trim($data["jenis_pelepasan_harta"]) != "" &&
//                trim($data["kategori_harta"]) != "") &&
//                (trim($data["nama_harta"]) != "" ||
//                trim($data["nilai_pelepasan"]) != "" ||
//                trim($data["nama_pihak_kedua"]) != "" ||
//                trim($data["alamat_pihak_kedua"]) != "");
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return trim($data["nilai_pelepasan"]) != "";
    }

    public function readSheet_Lampiran1_Penjualan()
    {
        return $this->readSheet_Lampiran1_Penjualan_Pelepasan('2.1');
    }

    public function readSheet_Lampiran1_Penjualan_Pelepasan($version = 'old') {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            if ($version == '2.1') {
                $sheet_name = "Lampiran1-Pelepasan";
            }
            else{
                $sheet_name = "Lampiran1-Penjualan-Pelepasan";
            }
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {

                $attribute_data_pelepasan = [
                    "jenis_pelepasan_harta" => $active_sheet->getCell('G8')->getValue(),
                    "keterangan" => $active_sheet->getCell('H10')->getValue(),
                    "kategori_harta" => $active_sheet->getCell('I8')->getValue(),
                    "nama_harta" => $active_sheet->getCell('P8')->getValue(),
                    "nilai_pelepasan" => $active_sheet->getCell('AA10')->getValue(),
                    "nama_pihak_kedua" => $active_sheet->getCell('AK8')->getValue(),
                    "alamat_pihak_kedua" => $active_sheet->getCell('AK10')->getValue(),
                ];

                if ($this->data_pelepasan_filled($attribute_data_pelepasan)) {
                    $has_data = TRUE;
                    $this->response_data_pelepasan[] = $attribute_data_pelepasan;
                }

                $attribute_data_pelepasan = [
                    "jenis_pelepasan_harta" => $active_sheet->getCell('G15')->getValue(),
                    "keterangan" => $active_sheet->getCell('H17')->getValue(),
                    "kategori_harta" => $active_sheet->getCell('I15')->getValue(),
                    "nama_harta" => $active_sheet->getCell('P15')->getValue(),
                    "nilai_pelepasan" => $active_sheet->getCell('AA17')->getValue(),
                    "nama_pihak_kedua" => $active_sheet->getCell('AK15')->getValue(),
                    "alamat_pihak_kedua" => $active_sheet->getCell('AK17')->getValue(),
                ];

                if ($this->data_pelepasan_filled($attribute_data_pelepasan)) {
                    $has_data = TRUE;
                    $this->response_data_pelepasan[] = $attribute_data_pelepasan;
                }

                $attribute_data_pelepasan = [
                    "jenis_pelepasan_harta" => $active_sheet->getCell('G22')->getValue(),
                    "keterangan" => $active_sheet->getCell('H24')->getValue(),
                    "kategori_harta" => $active_sheet->getCell('I22')->getValue(),
                    "nama_harta" => $active_sheet->getCell('P22')->getValue(),
                    "nilai_pelepasan" => $active_sheet->getCell('AA24')->getValue(),
                    "nama_pihak_kedua" => $active_sheet->getCell('AK22')->getValue(),
                    "alamat_pihak_kedua" => $active_sheet->getCell('AK24')->getValue(),
                ];

                if ($this->data_pelepasan_filled($attribute_data_pelepasan)) {
                    $has_data = TRUE;
                    $this->response_data_pelepasan[] = $attribute_data_pelepasan;
                }

                $attribute_data_pelepasan = [
                    "jenis_pelepasan_harta" => $active_sheet->getCell('G29')->getValue(),
                    "keterangan" => $active_sheet->getCell('H31')->getValue(),
                    "kategori_harta" => $active_sheet->getCell('I29')->getValue(),
                    "nama_harta" => $active_sheet->getCell('P29')->getValue(),
                    "nilai_pelepasan" => $active_sheet->getCell('AA31')->getValue(),
                    "nama_pihak_kedua" => $active_sheet->getCell('AK29')->getValue(),
                    "alamat_pihak_kedua" => $active_sheet->getCell('AK31')->getValue(),
                ];

                if ($this->data_pelepasan_filled($attribute_data_pelepasan)) {
                    $has_data = TRUE;
                    $this->response_data_pelepasan[] = $attribute_data_pelepasan;
                }

                $attribute_data_pelepasan = [
                    "jenis_pelepasan_harta" => $active_sheet->getCell('G36')->getValue(),
                    "keterangan" => $active_sheet->getCell('H38')->getValue(),
                    "kategori_harta" => $active_sheet->getCell('I36')->getValue(),
                    "nama_harta" => $active_sheet->getCell('P36')->getValue(),
                    "nilai_pelepasan" => $active_sheet->getCell('AA38')->getValue(),
                    "nama_pihak_kedua" => $active_sheet->getCell('AK36')->getValue(),
                    "alamat_pihak_kedua" => $active_sheet->getCell('AK38')->getValue(),
                ];

                if ($this->data_pelepasan_filled($attribute_data_pelepasan)) {
                    $has_data = TRUE;
                    $this->response_data_pelepasan[] = $attribute_data_pelepasan;
                }

                unset($attribute_data_pelepasan);

                $has_sheet = TRUE;
            }
            unset($active_sheet);
            $counter_i++;
        }
        return $has_data;
    }

    public function data_fasilitas_filled($data) {
//        return trim($data["kode_jenis"]) != "" ||
//                trim($data["keterangan"]) != "" ||
//                trim($data["pemberi_fasilitas"]) != "" ||
//                trim($data["keterangan_lain"]) != "";

        $filled = FALSE;
        foreach ($data as $val) {
            if ($val != "") {
                $filled = TRUE;
            }
        }
        return $filled;
    }

    public function readSheet_Lampiran2_fasilitas() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "Lampiran2-Fasilitas";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {

                $attribute_data_fasilitas = [
                    "kode_jenis" => $active_sheet->getCell('H8')->getValue(),
                    "keterangan" => $active_sheet->getCell('H10')->getValue(),
                    "pemberi_fasilitas" => $active_sheet->getCell('S8')->getValue(),
                    "keterangan_lain" => $active_sheet->getCell('AF8')->getValue(),
                ];

                if ($this->data_fasilitas_filled($attribute_data_fasilitas)) {
                    $has_data = TRUE;
                    $this->response_data_fasilitas[] = $attribute_data_fasilitas;
                }

                $attribute_data_fasilitas = [
                    "kode_jenis" => $active_sheet->getCell('H15')->getValue(),
                    "keterangan" => $active_sheet->getCell('H17')->getValue(),
                    "pemberi_fasilitas" => $active_sheet->getCell('S15')->getValue(),
                    "keterangan_lain" => $active_sheet->getCell('AF15')->getValue(),
                ];

                if ($this->data_fasilitas_filled($attribute_data_fasilitas)) {
                    $has_data = TRUE;
                    $this->response_data_fasilitas[] = $attribute_data_fasilitas;
                }

                $attribute_data_fasilitas = [
                    "kode_jenis" => $active_sheet->getCell('H22')->getValue(),
                    "keterangan" => $active_sheet->getCell('H24')->getValue(),
                    "pemberi_fasilitas" => $active_sheet->getCell('S22')->getValue(),
                    "keterangan_lain" => $active_sheet->getCell('AF22')->getValue(),
                ];

                if ($this->data_fasilitas_filled($attribute_data_fasilitas)) {
                    $has_data = TRUE;
                    $this->response_data_fasilitas[] = $attribute_data_fasilitas;
                }

                $attribute_data_fasilitas = [
                    "kode_jenis" => $active_sheet->getCell('H29')->getValue(),
                    "keterangan" => $active_sheet->getCell('H31')->getValue(),
                    "pemberi_fasilitas" => $active_sheet->getCell('S29')->getValue(),
                    "keterangan_lain" => $active_sheet->getCell('AF29')->getValue(),
                ];

                if ($this->data_fasilitas_filled($attribute_data_fasilitas)) {
                    $has_data = TRUE;
                    $this->response_data_fasilitas[] = $attribute_data_fasilitas;
                }

                $attribute_data_fasilitas = [
                    "kode_jenis" => $active_sheet->getCell('H36')->getValue(),
                    "keterangan" => $active_sheet->getCell('H38')->getValue(),
                    "pemberi_fasilitas" => $active_sheet->getCell('S36')->getValue(),
                    "keterangan_lain" => $active_sheet->getCell('AF36')->getValue(),
                ];

                if ($this->data_fasilitas_filled($attribute_data_fasilitas)) {
                    $has_data = TRUE;
                    $this->response_data_fasilitas[] = $attribute_data_fasilitas;
                }

                unset($attribute_data_fasilitas);

                $has_sheet = TRUE;
            }
            unset($active_sheet);
            $counter_i++;
        }
        return $has_data;
    }

    public function readSheet_VI() {
        $this->response_data_pengeluaran_kas = (object) array(
                    "A" => array(
                        (object) array("A0" => 0),
                        (object) array("A1" => 0),
                        (object) array("A2" => 0),
                        (object) array("A3" => 0),
                        (object) array("A4" => 0),
                    ),
                    "B" => array(
                        (object) array("B0" => 0),
                        (object) array("B1" => 0),
                        (object) array("B2" => 0)
                    ),
                    "C" => array(
                        (object) array("C0" => 0),
                        (object) array("C1" => 0),
                        (object) array("C2" => 0)
                    ),
        );

        $active_sheet = FALSE;
        try {
            $active_sheet = $this->excellib->setActiveSheetIndexByName("VI.Pengeluaran");
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return FAlSE;
        }

        if ($active_sheet !== FALSE) {
            $this->response_data_pengeluaran_kas = (object) array(
                        "A" => array(
                            (object) array("A0" => $this->read_cell_number($active_sheet, "L10")),
                            (object) array("A1" => $this->read_cell_number($active_sheet, "L13")),
                            (object) array("A2" => $this->read_cell_number($active_sheet, "L16")),
                            (object) array("A3" => $this->read_cell_number($active_sheet, "L19")),
//                            (object) array("A4" => "0"),
                        ),
                        "B" => array(
                            (object) array("B0" => $this->read_cell_number($active_sheet, "L26")),
                            (object) array("B1" => $this->read_cell_number($active_sheet, "L29")),
                            (object) array("B2" => $this->read_cell_number($active_sheet, "L32"))
                        ),
                        "C" => array(
                            (object) array("C0" => $this->read_cell_number($active_sheet, "L39")),
                            (object) array("C1" => $this->read_cell_number($active_sheet, "L42")),
                            (object) array("C2" => $this->read_cell_number($active_sheet, "L45"))
                        ),
            );
            unset($active_sheet);
            return TRUE;
        }
        return FALSE;
    }

    public function readSheet_V() {

        $this->response_data_penerimaan_kas = (object) array(
                    "A" => array(
                        (object) array("A0" => 0),
                        (object) array("A1" => 0),
                        (object) array("A2" => 0),
                        (object) array("A3" => 0),
                        (object) array("A4" => 0),
                    ),
                    "B" => array(
                        (object) array("B0" => 0),
                        (object) array("B1" => 0),
                        (object) array("B2" => 0),
                        (object) array("B3" => 0),
                        (object) array("B4" => 0),
                    ),
                    "C" => array(
                        (object) array("C0" => 0),
                        (object) array("C1" => 0),
                        (object) array("C2" => 0),
                        (object) array("C3" => 0),
                    )
        );

        $this->response_data_penerimaan_kas_pasangan = array(
            (object) array("PA0" => 0),
            (object) array("PA1" => 0),
            (object) array("PA2" => 0),
            (object) array("PA3" => 0),
            (object) array("PA4" => 0),
        );

        $active_sheet = FALSE;
        try {
            $active_sheet = $this->excellib->setActiveSheetIndexByName("V.Penerimaan");
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return FAlSE;
        }

        if ($active_sheet !== FALSE) {

            $this->response_data_penerimaan_kas = (object) array(
                        "A" => array(
                            (object) array("A0" => $this->read_cell_number($active_sheet, "H11")),
                            (object) array("A1" => $this->read_cell_number($active_sheet, "H13")),
                            (object) array("A2" => $this->read_cell_number($active_sheet, "H16")),
                            (object) array("A3" => $this->read_cell_number($active_sheet, "H19")),
                            (object) array("A4" => $this->read_cell_number($active_sheet, "H22")),
                        ),
                        "B" => array(
                            (object) array("B0" => $this->read_cell_number($active_sheet, "AJ29")),
                            (object) array("B1" => $this->read_cell_number($active_sheet, "AJ32")),
                            (object) array("B2" => $this->read_cell_number($active_sheet, "AJ35")),
                            (object) array("B3" => $this->read_cell_number($active_sheet, "AJ38")),
                            (object) array("B4" => $this->read_cell_number($active_sheet, "AJ41")),
                        ),
                        "C" => array(
                            (object) array("C0" => $this->read_cell_number($active_sheet, "AJ48")),
                            (object) array("C1" => $this->read_cell_number($active_sheet, "AJ51")),
                            (object) array("C2" => $this->read_cell_number($active_sheet, "AJ54")),
                            (object) array("C3" => $this->read_cell_number($active_sheet, "AJ57")),
                        )
            );

            $this->response_data_penerimaan_kas_pasangan = array(
                (object) array("PA0" => $this->read_cell_number($active_sheet, "V11")),
                (object) array("PA1" => $this->read_cell_number($active_sheet, "V13")),
                (object) array("PA2" => $this->read_cell_number($active_sheet, "V16")),
                (object) array("PA3" => $this->read_cell_number($active_sheet, "V19")),
                (object) array("PA4" => $this->read_cell_number($active_sheet, "V22")),
            );
            unset($active_sheet);
            return TRUE;
        }
        return FALSE;
    }

    public function data_hutang_filled($data) {
//        return trim($data["kode_jenis"]) != "" &&
//                trim($data["atas_nama"]) != "" &&
//                trim($data["nama_kreditur"]) != "" &&
//                trim($data["awal_hutang"]) != "" &&
//                trim($data["saldo_hutang"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return trim($data["awal_hutang"]) != "" ||
                trim($data["saldo_hutang"]) != "";
    }

    public function readSheet_IV_6() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "IV.6";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {


                $attribute_data_hutang = [
                    "kode_jenis" => $active_sheet->getCell('H8')->getValue(),
                    "atas_nama" => $active_sheet->getCell('H10')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('F12')->getValue(),
                    "nama_kreditur" => $active_sheet->getCell('M8')->getValue(),
                    "agunan" => $active_sheet->getCell('X8')->getValue(),
                    "awal_hutang" => $active_sheet->getCell('AJ8')->getCalculatedValue(),
                    "saldo_hutang" => $active_sheet->getCell('AO8')->getCalculatedValue(),
                ];

                if ($this->data_hutang_filled($attribute_data_hutang)) {
                    $has_data = TRUE;
                    $this->response_data_hutang[] = $attribute_data_hutang;
                }

                $attribute_data_hutang = [
                    "kode_jenis" => $active_sheet->getCell('H15')->getValue(),
                    "atas_nama" => $active_sheet->getCell('H17')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('F19')->getValue(),
                    "nama_kreditur" => $active_sheet->getCell('M15')->getValue(),
                    "agunan" => $active_sheet->getCell('X15')->getValue(),
                    "awal_hutang" => $active_sheet->getCell('AJ15')->getCalculatedValue(),
                    "saldo_hutang" => $active_sheet->getCell('AO15')->getCalculatedValue(),
                ];

                if ($this->data_hutang_filled($attribute_data_hutang)) {
                    $has_data = TRUE;
                    $this->response_data_hutang[] = $attribute_data_hutang;
                }

                $attribute_data_hutang = [
                    "kode_jenis" => $active_sheet->getCell('H22')->getValue(),
                    "atas_nama" => $active_sheet->getCell('H24')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('F26')->getValue(),
                    "nama_kreditur" => $active_sheet->getCell('M22')->getValue(),
                    "agunan" => $active_sheet->getCell('X22')->getValue(),
                    "awal_hutang" => $active_sheet->getCell('AJ22')->getCalculatedValue(),
                    "saldo_hutang" => $active_sheet->getCell('AO22')->getCalculatedValue(),
                ];

                if ($this->data_hutang_filled($attribute_data_hutang)) {
                    $has_data = TRUE;
                    $this->response_data_hutang[] = $attribute_data_hutang;
                }

                $attribute_data_hutang = [
                    "kode_jenis" => $active_sheet->getCell('H29')->getValue(),
                    "atas_nama" => $active_sheet->getCell('H31')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('F33')->getValue(),
                    "nama_kreditur" => $active_sheet->getCell('M29')->getValue(),
                    "agunan" => $active_sheet->getCell('X29')->getValue(),
                    "awal_hutang" => $active_sheet->getCell('AJ29')->getCalculatedValue(),
                    "saldo_hutang" => $active_sheet->getCell('AO29')->getCalculatedValue(),
                ];

                if ($this->data_hutang_filled($attribute_data_hutang)) {
                    $has_data = TRUE;
                    $this->response_data_hutang[] = $attribute_data_hutang;
                }

                unset($attribute_data_hutang);

                $has_sheet = TRUE;
            }
            $counter_i++;
            unset($active_sheet);
        }
        return $has_data;
    }

    public function data_harta_lainnya_filled($data) {
//        return trim($data["kode_jenis"]) != "" &&
//                trim($data["keterangan"]) != "" &&
////                trim($data["asal_usul"]) != "" &&
//                trim($data["nilai_perolehan"]) != "" &&
//                trim($data["nilai_pelaporan"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return $this->check_harta_filled($data);
    }

    public function readSheet_IV_5() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "IV.5";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $col_asal_usul = ["AW", "AX", "AY", "AZ", "BA", "BB"];

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {


                $attribute_data_harta_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H8')->getValue(),
                    "keterangan" => $active_sheet->getCell('I10')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "10"),
                    "nilai_perolehan" => $active_sheet->getCell('AH8')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AO8')->getCalculatedValue(),
                ];

                if ($this->data_harta_lainnya_filled($attribute_data_harta_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_harta_lainnya[] = $attribute_data_harta_lainnya;
                }

                $attribute_data_harta_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H14')->getValue(),
                    "keterangan" => $active_sheet->getCell('I16')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "16"),
                    "nilai_perolehan" => $active_sheet->getCell('AH14')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AO14')->getCalculatedValue(),
                ];

                if ($this->data_harta_lainnya_filled($attribute_data_harta_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_harta_lainnya[] = $attribute_data_harta_lainnya;
                }

                $attribute_data_harta_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H20')->getValue(),
                    "keterangan" => $active_sheet->getCell('I22')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "22"),
                    "nilai_perolehan" => $active_sheet->getCell('AH20')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AO20')->getCalculatedValue(),
                ];

                if ($this->data_harta_lainnya_filled($attribute_data_harta_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_harta_lainnya[] = $attribute_data_harta_lainnya;
                }

                $attribute_data_harta_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H26')->getValue(),
                    "keterangan" => $active_sheet->getCell('I28')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "28"),
                    "nilai_perolehan" => $active_sheet->getCell('AH26')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AO26')->getCalculatedValue(),
                ];

                if ($this->data_harta_lainnya_filled($attribute_data_harta_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_harta_lainnya[] = $attribute_data_harta_lainnya;
                }

                unset($attribute_data_harta_lainnya);

                $has_sheet = TRUE;
            }
            unset($active_sheet);
            $counter_i++;
        }
        return $has_data;
    }

    public function data_harta_kas_dan_setara_kas_filled($data) {
//        return trim($data["kode_jenis"]) != "" &&
//                trim($data["nama_bank"]) != "" &&
//                trim($data["nomor_rekening"]) != "" &&
//                trim($data["atas_nama_rekening"]) != "" &&
////                trim($data["asal_usul"]) != "" &&
////                trim($data["mata_uang"]) != "" &&
//                trim($data["nilai_kurs"]) != "" &&
//                trim($data["nilai_saldo"]) != "" &&
//                trim($data["nilai_equivalen"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return trim($data["nilai_saldo"]) != "" && is_numeric(trim($data["nilai_saldo"]));
    }

    public function readSheet_IV_4() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "IV.4";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            $active_sheet = FALSE;
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $col_asal_usul = ["AM", "AN", "AO", "AP", "AQ", "AR"];


            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {
                $attribute_data_kas_setara_kas = [
                    "kode_jenis" => $active_sheet->getCell('F8')->getValue(),
                    "keterangan" => $active_sheet->getCell('E11')->getValue(),
                    "nama_bank" => $active_sheet->getCell('O9')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('O13')->getValue(),
                    "atas_nama_rekening" => $active_sheet->getCell('Q15')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "10"),
                    "mata_uang" => $active_sheet->getCell('AG8')->getValue(),
                    "nilai_kurs" => $active_sheet->getCell('AG11')->getValue(),
                    "nilai_saldo" => $active_sheet->getCell('AG13')->getValue(),
                    "nilai_equivalen" => $active_sheet->getCell('AG15')->getCalculatedValue(),
                ];

                if ($this->data_harta_kas_dan_setara_kas_filled($attribute_data_kas_setara_kas)) {
                    $has_data = TRUE;
                    $this->response_data_kas_setara_kas[] = $attribute_data_kas_setara_kas;
                }

                $attribute_data_kas_setara_kas = [
                    "kode_jenis" => $active_sheet->getCell('F18')->getValue(),
                    "keterangan" => $active_sheet->getCell('E21')->getValue(),
                    "nama_bank" => $active_sheet->getCell('O19')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('O23')->getValue(),
                    "atas_nama_rekening" => $active_sheet->getCell('Q25')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "20"),
                    "mata_uang" => $active_sheet->getCell('AG18')->getValue(),
                    "nilai_kurs" => $active_sheet->getCell('AG21')->getValue(),
                    "nilai_saldo" => $active_sheet->getCell('AG23')->getValue(),
                    "nilai_equivalen" => $active_sheet->getCell('AG25')->getCalculatedValue(),
                ];

                if ($this->data_harta_kas_dan_setara_kas_filled($attribute_data_kas_setara_kas)) {
                    $has_data = TRUE;
                    $this->response_data_kas_setara_kas[] = $attribute_data_kas_setara_kas;
                }

                $attribute_data_kas_setara_kas = [
                    "kode_jenis" => $active_sheet->getCell('F28')->getValue(),
                    "keterangan" => $active_sheet->getCell('E31')->getValue(),
                    "nama_bank" => $active_sheet->getCell('O29')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('O33')->getValue(),
                    "atas_nama_rekening" => $active_sheet->getCell('Q35')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "30"),
                    "mata_uang" => $active_sheet->getCell('AG28')->getValue(),
                    "nilai_kurs" => $active_sheet->getCell('AG31')->getValue(),
                    "nilai_saldo" => $active_sheet->getCell('AG33')->getValue(),
                    "nilai_equivalen" => $active_sheet->getCell('AG35')->getCalculatedValue(),
                ];

                if ($this->data_harta_kas_dan_setara_kas_filled($attribute_data_kas_setara_kas)) {
                    $has_data = TRUE;
                    $this->response_data_kas_setara_kas[] = $attribute_data_kas_setara_kas;
                }

                $attribute_data_kas_setara_kas = [
                    "kode_jenis" => $active_sheet->getCell('F38')->getValue(),
                    "keterangan" => $active_sheet->getCell('E41')->getValue(),
                    "nama_bank" => $active_sheet->getCell('O39')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('O43')->getValue(),
                    "atas_nama_rekening" => $active_sheet->getCell('Q45')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "40"),
                    "mata_uang" => $active_sheet->getCell('AG38')->getValue(),
                    "nilai_kurs" => $active_sheet->getCell('AG41')->getValue(),
                    "nilai_saldo" => $active_sheet->getCell('AG43')->getValue(),
                    "nilai_equivalen" => $active_sheet->getCell('AG45')->getCalculatedValue(),
                ];

                if ($this->data_harta_kas_dan_setara_kas_filled($attribute_data_kas_setara_kas)) {
                    $has_data = TRUE;
                    $this->response_data_kas_setara_kas[] = $attribute_data_kas_setara_kas;
                }

                unset($attribute_data_kas_setara_kas);

                $has_sheet = TRUE;
            }
            $counter_i++;
            unset($active_sheet);
        }
        return $has_data;
    }

    public function data_surat_berharga_filled($data) {
//        return trim($data["kode_jenis"]) != "" &&
//                trim($data["atas_nama"]) != "" &&
//                trim($data["nama_penerbit"]) != "" &&
//                trim($data["custodian"]) != "" &&
//                trim($data["nomor_rekening"]) != "" &&
////                trim($data["asal_usul"]) != "" &&
//                trim($data["nilai_perolehan"]) != "" &&
//                trim($data["nilai_pelaporan"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return $this->check_harta_filled($data);
    }

    public function readSheet_IV_3() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "IV.3";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $col_asal_usul = ["BF", "BG", "BH", "BI", "BJ", "BK"];


            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {


                $attribute_data_surat_berharga = [
                    "kode_jenis" => $active_sheet->getCell('I8')->getValue(),
                    "atas_nama" => $active_sheet->getCell('I10')->getValue(),
                    "nama_penerbit" => $active_sheet->getCell('L12')->getValue(),
                    "custodian" => $active_sheet->getCell('L14')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('U10')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "10"),
                    "nilai_perolehan" => $active_sheet->getCell('AK10')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AV10')->getCalculatedValue(),
                ];

                if ($this->data_surat_berharga_filled($attribute_data_surat_berharga)) {
                    $has_data = TRUE;
                    $this->response_data_surat_berharga[] = $attribute_data_surat_berharga;
                }

                $attribute_data_surat_berharga = [
                    "kode_jenis" => $active_sheet->getCell('I17')->getValue(),
                    "atas_nama" => $active_sheet->getCell('I19')->getValue(),
                    "nama_penerbit" => $active_sheet->getCell('L21')->getValue(),
                    "custodian" => $active_sheet->getCell('L23')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('U19')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "19"),
                    "nilai_perolehan" => $active_sheet->getCell('AK19')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AV19')->getCalculatedValue(),
                ];

                if ($this->data_surat_berharga_filled($attribute_data_surat_berharga)) {
                    $has_data = TRUE;
                    $this->response_data_surat_berharga[] = $attribute_data_surat_berharga;
                }

                $attribute_data_surat_berharga = [
                    "kode_jenis" => $active_sheet->getCell('I26')->getValue(),
                    "atas_nama" => $active_sheet->getCell('I28')->getValue(),
                    "nama_penerbit" => $active_sheet->getCell('L30')->getValue(),
                    "custodian" => $active_sheet->getCell('L32')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('U28')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "28"),
                    "nilai_perolehan" => $active_sheet->getCell('AK28')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AV28')->getCalculatedValue(),
                ];

                if ($this->data_surat_berharga_filled($attribute_data_surat_berharga)) {
                    $has_data = TRUE;
                    $this->response_data_surat_berharga[] = $attribute_data_surat_berharga;
                }

                $attribute_data_surat_berharga = [
                    "kode_jenis" => $active_sheet->getCell('I35')->getValue(),
                    "atas_nama" => $active_sheet->getCell('I37')->getValue(),
                    "nama_penerbit" => $active_sheet->getCell('L39')->getValue(),
                    "custodian" => $active_sheet->getCell('L41')->getValue(),
                    "nomor_rekening" => $active_sheet->getCell('U37')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "37"),
                    "nilai_perolehan" => $active_sheet->getCell('AK37')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AV37')->getCalculatedValue(),
                ];

                if ($this->data_surat_berharga_filled($attribute_data_surat_berharga)) {
                    $has_data = TRUE;
                    $this->response_data_surat_berharga[] = $attribute_data_surat_berharga;
                }

                unset($attribute_data_surat_berharga);

                $has_sheet = TRUE;
            }
            $counter_i++;
            unset($active_sheet);
        }
        return $has_data;
    }

    public function data_harta_bergerak_lainnya_filled($data) {

//        return trim($data["kode_jenis"]) != "" &&
//                trim($data["jumlah"]) != "" &&
//                trim($data["satuan"]) != "" &&
////                trim($data["asal_usul"]) != "" &&
//                trim($data["nilai_perolehan"]) != "" &&
//                trim($data["nilai_pelaporan"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return $this->check_harta_filled($data);
    }

    public function readSheet_IV_2_2() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "IV.2.2";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $col_asal_usul = ["BB", "BC", "BD", "BE", "BF", "BG"];

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {

                $attribute_data_bergerak_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H8')->getValue(),
//                    "nama" => $active_sheet->getCell('H8')->getValue(),
                    "jumlah" => $active_sheet->getCell('H10')->getValue(),
                    "satuan" => $active_sheet->getCell('H12')->getValue(),
                    "keterangan" => $active_sheet->getCell('J14')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "10"),
                    "nilai_perolehan" => $active_sheet->getCell('AJ10')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AS10')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_lainnya_filled($attribute_data_bergerak_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak_lainnya[] = $attribute_data_bergerak_lainnya;
                }

                $attribute_data_bergerak_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H17')->getValue(),
//                    "nama" => $active_sheet->getCell('H8')->getValue(),
                    "jumlah" => $active_sheet->getCell('H19')->getValue(),
                    "satuan" => $active_sheet->getCell('H21')->getValue(),
                    "keterangan" => $active_sheet->getCell('J23')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "19"),
                    "nilai_perolehan" => $active_sheet->getCell('AJ19')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AS19')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_lainnya_filled($attribute_data_bergerak_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak_lainnya[] = $attribute_data_bergerak_lainnya;
                }

                $attribute_data_bergerak_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H26')->getValue(),
//                    "nama" => $active_sheet->getCell('H8')->getValue(),
                    "jumlah" => $active_sheet->getCell('H28')->getValue(),
                    "satuan" => $active_sheet->getCell('H30')->getValue(),
                    "keterangan" => $active_sheet->getCell('J32')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "28"),
                    "nilai_perolehan" => $active_sheet->getCell('AJ28')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AS28')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_lainnya_filled($attribute_data_bergerak_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak_lainnya[] = $attribute_data_bergerak_lainnya;
                }

                $attribute_data_bergerak_lainnya = [
                    "kode_jenis" => $active_sheet->getCell('H35')->getValue(),
//                    "nama" => $active_sheet->getCell('H8')->getValue(),
                    "jumlah" => $active_sheet->getCell('H38')->getValue(),
                    "satuan" => $active_sheet->getCell('H40')->getValue(),
                    "keterangan" => $active_sheet->getCell('J42')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "38"),
                    "nilai_perolehan" => $active_sheet->getCell('AJ38')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AS38')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_lainnya_filled($attribute_data_bergerak_lainnya)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak_lainnya[] = $attribute_data_bergerak_lainnya;
                }

                unset($attribute_data_bergerak_lainnya);

                $has_sheet = TRUE;
            }
            $counter_i++;
            unset($active_sheet);
        }
        return $has_data;
    }

    public function data_harta_bergerak_filled($data) {

//        return trim($data["kode_jenis"]) != "" &&
//          trim($data["merek"]) != "" &&
//                trim($data["model"]) != "" &&
//                trim($data["tahun_pembuatan"]) != "" &&
//                trim($data["nopol_registrasi"]) != "" &&
////                trim($data["jenis_bukti"]) != "" &&
////                trim($data["asal_usul"]) != "" &&
////                trim($data["pemanfaatan"]) != "" &&
//                trim($data["nilai_perolehan"]) != "" &&
//                trim($data["nilai_pelaporan"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;

        return $this->check_harta_filled($data);
    }

    public function readSheet_IV_2_1() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "IV.2.1";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $col_asal_usul = ["BD", "BE", "BF", "BG", "BH", "BI"];

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {

                $attribute_data_bergerak = [
                    "kode_jenis" => $active_sheet->getCell('H8')->getValue(),
                    "merek" => $active_sheet->getCell('H10')->getValue(),
                    "model" => $active_sheet->getCell('H12')->getValue(),
                    "tahun_pembuatan" => $active_sheet->getCell('L14')->getValue(),
                    "nopol_registrasi" => $active_sheet->getCell('L16')->getValue(),
                    "jenis_bukti" => $active_sheet->getCell('AD8')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "10"),
                    "atas_nama" => $active_sheet->getCell('AD12')->getValue(),
                    "pemanfaatan" => $active_sheet->getCell('BC14')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('AD16')->getValue(),
                    "nilai_perolehan" => $active_sheet->getCell('AM10')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AU10')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_filled($attribute_data_bergerak)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak[] = $attribute_data_bergerak;
                }

                $attribute_data_bergerak = [
                    "kode_jenis" => $active_sheet->getCell('H19')->getValue(),
                    "merek" => $active_sheet->getCell('H21')->getValue(),
                    "model" => $active_sheet->getCell('H23')->getValue(),
                    "tahun_pembuatan" => $active_sheet->getCell('L25')->getValue(),
                    "nopol_registrasi" => $active_sheet->getCell('L27')->getValue(),
                    "jenis_bukti" => $active_sheet->getCell('AD19')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "21"),
                    "atas_nama" => $active_sheet->getCell('AD23')->getValue(),
                    "pemanfaatan" => $active_sheet->getCell('BC25')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('AD27')->getValue(),
                    "nilai_perolehan" => $active_sheet->getCell('AM21')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AU21')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_filled($attribute_data_bergerak)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak[] = $attribute_data_bergerak;
                }

                $attribute_data_bergerak = [
                    "kode_jenis" => $active_sheet->getCell('H30')->getValue(),
                    "merek" => $active_sheet->getCell('H32')->getValue(),
                    "model" => $active_sheet->getCell('H34')->getValue(),
                    "tahun_pembuatan" => $active_sheet->getCell('L36')->getValue(),
                    "nopol_registrasi" => $active_sheet->getCell('L38')->getValue(),
                    "jenis_bukti" => $active_sheet->getCell('AD30')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "32"),
                    "atas_nama" => $active_sheet->getCell('AD34')->getValue(),
                    "pemanfaatan" => $active_sheet->getCell('BC36')->getValue(),
                    "ket_lainnya" => $active_sheet->getCell('AD38')->getValue(),
                    "nilai_perolehan" => $active_sheet->getCell('AM32')->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell('AU32')->getCalculatedValue(),
                ];

                if ($this->data_harta_bergerak_filled($attribute_data_bergerak)) {
                    $has_data = TRUE;
                    $this->response_data_bergerak[] = $attribute_data_bergerak;
                }

                unset($attribute_data_bergerak);

                $has_sheet = TRUE;
            }
            unset($active_sheet);
            $counter_i++;
        }
        return $has_data;
    }

    public function data_harta_tidak_bergerak_filled($data) {

//        $if_atas_nama_lainnya_not_null = trim($data["atas_nama"]) == "3" ? trim($data["atas_nama"]) == "3" && trim($data["atas_nama_lainnya"]) != "" : trim($data["atas_nama"]) != "";
//
//        return trim($data["jalan"]) != "" &&
//                trim($data["kel"]) != "" &&
//                trim($data["kec"]) != "" &&
//                trim($data["kab_kot"]) != "" &&
//                trim($data["prov"]) != "" &&
//                trim($data["negara"]) != "" &&
//                trim($data["luas_tanah"]) != "" &&
//                trim($data["luas_bangunan"]) != "" &&
////                trim($data["jenis_bukti"]) != "" &&
//                trim($data["nomor_bukti"]) != "" &&
////                trim($data["asal_usul"]) != "" &&
////                trim($data["pemanfaatan"]) != "" &&
//                trim($data["nilai_perolehan"]) != "" &&
//                trim($data["nilai_pelaporan"]) != "" &&
//                $if_atas_nama_lainnya_not_null;

        return $this->check_harta_filled($data);

//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;
    }

    private function check_harta_filled($data) {
        return trim($data["nilai_perolehan"]) != "" ||
                trim($data["nilai_pelaporan"]) != "";
    }

    public function readSheet_IV_1() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        $cols_nilai = $this->array_version_IV_1[$this->detected_version];

        while ($has_sheet) {
            $sheet_name = "IV.1";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $col_asal_usul = ["BG", "BH", "BI", "BJ", "BK", "BL"];
            $col_pemanfaatan = ["BG", "BH", "BI", "BJ"];

            $has_sheet = FALSE;
            if ($active_sheet !== FALSE) {

                $attribute_data_tidak_bergerak = [
                    "jalan" => $active_sheet->getCell('I8')->getValue(),
                    "kel" => $active_sheet->getCell('I11')->getValue(),
                    "kec" => $active_sheet->getCell('I13')->getValue(),
                    "kab_kot" => $active_sheet->getCell('I16')->getValue(),
                    "prov" => $active_sheet->getCell('I19')->getValue(),
                    "negara" => $active_sheet->getCell('I21')->getValue(),
                    "luas_tanah" => $active_sheet->getCell('W11')->getCalculatedValue(),
                    "luas_bangunan" => $active_sheet->getCell('W16')->getCalculatedValue(),
                    "jenis_bukti" => $active_sheet->getCell('AG8')->getValue(),
                    "nomor_bukti" => $active_sheet->getCell('AG11')->getValue(),
                    "atas_nama" => $active_sheet->getCell('AG13')->getValue(),
                    "atas_nama_lainnya" => $active_sheet->getCell('AH13')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "16"),
                    "pemanfaatan" => $this->read_data_asal_usul($active_sheet, $col_pemanfaatan, "19"),
                    "nilai_perolehan" => $active_sheet->getCell($cols_nilai[0][0])->getCalculatedValue(),
//                    "nilai_perolehan" => $active_sheet->getCell($cols_nilai[0][0])->getValue(),
                    "nilai_pelaporan" => $active_sheet->getCell($cols_nilai[0][1])->getCalculatedValue(),
//                    "nilai_pelaporan" => $active_sheet->getCell($cols_nilai[0][1])->getValue(),
                ];

                if ($this->data_harta_tidak_bergerak_filled($attribute_data_tidak_bergerak)) {
                    $has_data = TRUE;
                    $this->response_data_tidak_bergerak[] = $attribute_data_tidak_bergerak;
                }

                $attribute_data_tidak_bergerak = [
                    "jalan" => $active_sheet->getCell('I24')->getValue(),
                    "kel" => $active_sheet->getCell('I27')->getValue(),
                    "kec" => $active_sheet->getCell('I29')->getValue(),
                    "kab_kot" => $active_sheet->getCell('I32')->getValue(),
                    "prov" => $active_sheet->getCell('I35')->getValue(),
                    "negara" => $active_sheet->getCell('I37')->getValue(),
                    "luas_tanah" => $active_sheet->getCell('W27')->getCalculatedValue(),
                    "luas_bangunan" => $active_sheet->getCell('W32')->getCalculatedValue(),
                    "jenis_bukti" => $active_sheet->getCell('AG24')->getValue() < 3 ? $active_sheet->getCell('AG24')->getValue() : 1,
                    "nomor_bukti" => $active_sheet->getCell('AG27')->getValue(),
                    "atas_nama" => $active_sheet->getCell('AG29')->getValue(),
                    "atas_nama_lainnya" => $active_sheet->getCell('AH29')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "32"),
                    "pemanfaatan" => $this->read_data_asal_usul($active_sheet, $col_pemanfaatan, "35"),
                    "nilai_perolehan" => $active_sheet->getCell($cols_nilai[1][0])->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell($cols_nilai[1][1])->getCalculatedValue(),
                ];

                if ($this->data_harta_tidak_bergerak_filled($attribute_data_tidak_bergerak)) {
                    $has_data = TRUE;
                    $this->response_data_tidak_bergerak[] = $attribute_data_tidak_bergerak;
                }

                $attribute_data_tidak_bergerak = [
                    "jalan" => $active_sheet->getCell('I40')->getValue(),
                    "kel" => $active_sheet->getCell('I43')->getValue(),
                    "kec" => $active_sheet->getCell('I45')->getValue(),
                    "kab_kot" => $active_sheet->getCell('I48')->getValue(),
                    "prov" => $active_sheet->getCell('I50')->getValue(),
                    "negara" => $active_sheet->getCell('I52')->getValue(),
                    "luas_tanah" => $active_sheet->getCell('W43')->getCalculatedValue(),
                    "luas_bangunan" => $active_sheet->getCell('W48')->getCalculatedValue(),
                    "jenis_bukti" => $active_sheet->getCell('AG40')->getValue(),
                    "nomor_bukti" => $active_sheet->getCell('AG43')->getValue(),
                    "atas_nama" => $active_sheet->getCell('AG45')->getValue(),
                    "atas_nama_lainnya" => $active_sheet->getCell('AH45')->getValue(),
                    "asal_usul" => $this->read_data_asal_usul($active_sheet, $col_asal_usul, "48"),
                    "pemanfaatan" => $this->read_data_asal_usul($active_sheet, $col_pemanfaatan, "51"),
                    "nilai_perolehan" => $active_sheet->getCell($cols_nilai[2][0])->getCalculatedValue(),
                    "nilai_pelaporan" => $active_sheet->getCell($cols_nilai[2][1])->getCalculatedValue(),
                ];

                if ($this->data_harta_tidak_bergerak_filled($attribute_data_tidak_bergerak)) {
                    $has_data = TRUE;
                    $this->response_data_tidak_bergerak[] = $attribute_data_tidak_bergerak;
                }

                unset($attribute_data_tidak_bergerak);

                $has_sheet = TRUE;
            }
            unset($active_sheet);
            $counter_i++;
        }
        return $has_data;
    }

    public function data_keluarga_filled($data) {
        return trim($data["nama"]) != "";
//                && trim($data_keluarga["tempat_lahir"]) != "";
//        $filled = FALSE;
//        foreach ($data as $val) {
//            if ($val != "") {
//                $filled = TRUE;
//            }
//        }
//        return $filled;
    }

    private function __get_alamat_rumah_keluarga($active_sheet, $coordinate) {
        $alamat_rumah = $active_sheet->getCell($coordinate)->getOldCalculatedValue();
        if (is_null($alamat_rumah) || $alamat_rumah == "") {
            $alamat_rumah = $active_sheet->getCell($coordinate)->getValue();
        }

        unset($active_sheet);

        return $alamat_rumah;
    }

    private function get_jenis_kelamin_version($active_sheet, $coordinate) {
        $jenis_kelamin = $active_sheet->getCell($coordinate)->getValue();
        if ($jenis_kelamin == '2') {
            $jenis_kelamin = 'LAKI-LAKI';
        }
        elseif ($jenis_kelamin == '2'){
            $jenis_kelamin = 'PEREMPUAN';
        }
        else{
            $jenis_kelamin = '';
        }
        unset($active_sheet);

        return $jenis_kelamin;
    }

    public function readSheet_III() {
        $counter_i = 1;
        $has_sheet = TRUE;
        $has_data = FALSE;

        while ($has_sheet) {
            $sheet_name = "III";
//            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i - 1) . ")" : "";
            $sheet_name .= $counter_i > 1 ? " (" . ($counter_i) . ")" : "";
            try {
                $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return FAlSE;
            }

            $cols_nama = $this->array_version_keluarga[$this->detected_version];

            $has_sheet = FALSE;

            if ($active_sheet !== FALSE) {

                $attribute_data_keluarga = [
                    "nama" => $active_sheet->getCell($cols_nama[0])->getValue(),
                    "status_hubungan" => $active_sheet->getCell('BG10')->getValue(),
                    "tempat_lahir" => $active_sheet->getCell('R6')->getValue(),
                    "tanggal_lahir" => $active_sheet->getCell('BI13')->getOldCalculatedValue(),
                    "bulan_lahir" => $active_sheet->getCell('BJ13')->getOldCalculatedValue(),
                    "tahun_lahir" => $active_sheet->getCell('BK13')->getOldCalculatedValue(),
                    "pekerjaan" => $active_sheet->getCell('AB6')->getValue(),
                    "nomor_telpon" => $active_sheet->getCell('AN6')->getValue(),
                    "alamat_rumah" => $this->__get_alamat_rumah_keluarga($active_sheet, 'AV6'),
                    "jenis_kelamin" => $this->get_jenis_kelamin_version($active_sheet, 'BO10')
                    // "jenis_kelamin" => $active_sheet->getCell('BO10')->getValue()
                ];

                if ($this->data_keluarga_filled($attribute_data_keluarga)) {
                    $has_data = TRUE;
                    $this->response_data_keluarga[] = $attribute_data_keluarga;
                }

                $attribute_data_keluarga = [
                    "nama" => $active_sheet->getCell($cols_nama[1])->getValue(),
                    "status_hubungan" => $active_sheet->getCell('BG16')->getValue(),
                    "tempat_lahir" => $active_sheet->getCell('R12')->getValue(),
                    "tanggal_lahir" => $active_sheet->getCell('BI14')->getOldCalculatedValue(),
                    "bulan_lahir" => $active_sheet->getCell('BJ14')->getOldCalculatedValue(),
                    "tahun_lahir" => $active_sheet->getCell('BK14')->getOldCalculatedValue(),
                    "pekerjaan" => $active_sheet->getCell('AB12')->getValue(),
                    "nomor_telpon" => $active_sheet->getCell('AN12')->getValue(),
                    "alamat_rumah" => $this->__get_alamat_rumah_keluarga($active_sheet, 'AV12'),
                    "jenis_kelamin" => $this->get_jenis_kelamin_version($active_sheet, 'BO16')
                    // "jenis_kelamin" => $active_sheet->getCell('BO16')->getValue()
                ];

                if ($this->data_keluarga_filled($attribute_data_keluarga)) {
                    $has_data = TRUE;
                    $this->response_data_keluarga[] = $attribute_data_keluarga;
                }

                $attribute_data_keluarga = [
                    "nama" => $active_sheet->getCell($cols_nama[2])->getValue(),
                    "status_hubungan" => $active_sheet->getCell('BG22')->getValue(),
                    "tempat_lahir" => $active_sheet->getCell('R18')->getValue(),
                    "tanggal_lahir" => $active_sheet->getCell('BI15')->getOldCalculatedValue(),
                    "bulan_lahir" => $active_sheet->getCell('BJ15')->getOldCalculatedValue(),
                    "tahun_lahir" => $active_sheet->getCell('BK15')->getOldCalculatedValue(),
                    "pekerjaan" => $active_sheet->getCell('AB18')->getValue(),
                    "nomor_telpon" => $active_sheet->getCell('AN18')->getValue(),
                    "alamat_rumah" => $this->__get_alamat_rumah_keluarga($active_sheet, 'AV18'),
                    "jenis_kelamin" => $this->get_jenis_kelamin_version($active_sheet, 'BO22')
                    // "jenis_kelamin" => $active_sheet->getCell('BO22')->getValue()
                ];

                if ($this->data_keluarga_filled($attribute_data_keluarga)) {
                    $has_data = TRUE;
                    $this->response_data_keluarga[] = $attribute_data_keluarga;
                }

                $attribute_data_keluarga = [
                    "nama" => $active_sheet->getCell($cols_nama[3])->getValue(),
                    "status_hubungan" => $active_sheet->getCell('BG28')->getValue(),
                    "tempat_lahir" => $active_sheet->getCell('R24')->getValue(),
                    "tanggal_lahir" => $active_sheet->getCell('BI16')->getOldCalculatedValue(),
                    "bulan_lahir" => $active_sheet->getCell('BJ16')->getOldCalculatedValue(),
                    "tahun_lahir" => $active_sheet->getCell('BK16')->getOldCalculatedValue(),
                    "pekerjaan" => $active_sheet->getCell('AB24')->getValue(),
                    "nomor_telpon" => $active_sheet->getCell('AN24')->getValue(),
                    "alamat_rumah" => $this->__get_alamat_rumah_keluarga($active_sheet, 'AV24'),
                    "jenis_kelamin" => $this->get_jenis_kelamin_version($active_sheet, 'BO28')
                    // "jenis_kelamin" => $active_sheet->getCell('BO28')->getValue()
                ];

                if ($this->data_keluarga_filled($attribute_data_keluarga)) {
                    $has_data = TRUE;
                    $this->response_data_keluarga[] = $attribute_data_keluarga;
                }
                unset($attribute_data_keluarga);
                $has_sheet = TRUE;
            }
            unset($active_sheet);
            $counter_i++;
        }
        return $has_data;
    }

    public function readSheet_II() {
        $counter_i = 1;
        $has_sheet = TRUE;

        $sheet_name = "II";
        try {
            $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return FAlSE;
        }

        if ($active_sheet !== FALSE) {

            $npwp = $active_sheet->getCell('R15')->getValue() .
                    $active_sheet->getCell('T15')->getValue() .
                    $active_sheet->getCell('W15')->getValue() .
                    $active_sheet->getCell('Z15')->getValue() .
                    $active_sheet->getCell('AB15')->getValue() .
                    $active_sheet->getCell('AD15')->getValue();

            $kode_area_telp_rumah = $active_sheet->getCell('R19')->getValue();
            $telp_rumah = $active_sheet->getCell('W19')->getValue();

            $this->response_form_dua = [
                "gelar_depan" => $active_sheet->getCell('R5')->getCalculatedValue(),
                "nama" => $active_sheet->getCell('Y5')->getCalculatedValue(),
                "gelar_belakang" => $active_sheet->getCell('AJ5')->getCalculatedValue(),
                "agama" => $active_sheet->getCell('R7')->getValue(),
                "jenis_kelamin" => $active_sheet->getCell('R9')->getValue(),
                "nik" => $active_sheet->getCell('R11')->getValue(),
                "no_kk" => $active_sheet->getCell('R13')->getValue(),
                "npwp" => $npwp,
                "email_pribadi" => $active_sheet->getCell('R17')->getValue(),
                "telpon_rumah" => $kode_area_telp_rumah . $telp_rumah,
                "hp" => $active_sheet->getCell('R21')->getValue(),
                "jabatan" => $active_sheet->getCell('R23')->getValue(),
                "jabatan_lainnya" => FALSE,
                "eselon" => $active_sheet->getCell('AU25')->getValue(),
                "sub_unit_kerja" => $active_sheet->getCell('R27')->getValue(),
                "unit_kerja" => $active_sheet->getCell('R29')->getValue(),
                "lembaga" => $active_sheet->getCell('R31')->getValue(),
                "alamat_kantor" => $active_sheet->getCell('R33')->getValue(),
            ];
            return TRUE;
        }
        $has_sheet = FALSE;
        return FALSE;
    }

    private function __get_tanggal_pelaporan_khusus_default($active_sheet, $coordinate) {
//        return $active_sheet->getCell('AA9')->getFormattedValue();

        return $this->detect_tanggal($active_sheet->getCell($coordinate)->getFormattedValue());
    }

    private function __get_tanggal_pelaporan_khusus_1_1($active_sheet) {
        return $active_sheet->getCell('AA9')->getFormattedValue() . "/" . $active_sheet->getCell('AC9')->getFormattedValue() . "/" . $active_sheet->getCell('AE9')->getFormattedValue();
    }

    private function __get_tanggal_pelaporan_khusus_1_3($active_sheet, $coordinate) {
//        $detected_tanggal_pelaporan_khusus = $active_sheet->getCell('AA9')->getFormattedValue();
        $detected_tanggal_pelaporan_khusus = $active_sheet->getCell($coordinate)->getFormattedValue();
        if (!is_null($detected_tanggal_pelaporan_khusus) && strlen($detected_tanggal_pelaporan_khusus) > 0) {
            $separator = 'BA45' ? '-' : '/';
            $arr_tgl = explode($separator, $detected_tanggal_pelaporan_khusus);

            if (is_array($arr_tgl) && !empty($arr_tgl) && count($arr_tgl) == 3) {
                if (intval($arr_tgl[2]) < 2000) {
                    $arr_tgl[2] = intval($arr_tgl[2]) + 2000;
                }

                return $arr_tgl[2] . "-" . $arr_tgl[1] . "-" . $arr_tgl[0];
            }
        }
        return "";
    }

    /**
     * @todo tambahkan versi selanjutnya
     */
    private function __get_tanggal_pelaporan_khusus($active_sheet, $coordinate = 'AA9') {

        $detected_tanggal_pelaporan = '';

        switch ($this->detected_version) {
            case '1.1':
                $detected_tanggal_pelaporan = $this->__get_tanggal_pelaporan_khusus_1_1($active_sheet);
            case '1.3':
                $detected_tanggal_pelaporan = $this->__get_tanggal_pelaporan_khusus_1_3($active_sheet, $coordinate);
            default:
                $detected_tanggal_pelaporan = $this->__get_tanggal_pelaporan_khusus_default($active_sheet, $coordinate);
        }
        return $detected_tanggal_pelaporan;
    }

    public function readSheet_I() {

        $counter_i = 1;
        $has_sheet = TRUE;

        $arr_laporan_khusus = [1, 4, 3];

//        while ($has_sheet) {
        $sheet_name = "I";
//            $sheet_name .= $counter_i > 0 ? " (" . $counter_i . ")" : "";
        try {
            $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return FAlSE;
        }

        if ($active_sheet !== FALSE) {
            $last_row = $active_sheet->getHighestRow();
            $this->response_form_satu["jenis_laporan"] = $active_sheet->getCell('BJ7')->getValue();
            $this->response_form_satu["tahun_pelaporan_periodik"] = $active_sheet->getCell('BB9')->getValue();
//            $this->response_form_satu["tanggal_pelaporan_khusus"] = $active_sheet->getCell('AA9')->getFormattedValue();
//            $this->response_form_satu["tanggal_pelaporan_khusus"] = $active_sheet->getCell('AA9')->getFormattedValue();
            /**
              if ($this->detected_version == '1.1') {
              $this->response_form_satu["tanggal_pelaporan_khusus"] = $active_sheet->getCell('AA9')->getFormattedValue() . "/" . $active_sheet->getCell('AC9')->getFormattedValue() . "/" . $active_sheet->getCell('AE9')->getFormattedValue();
              } else {
              $this->response_form_satu["tanggal_pelaporan_khusus"] = $this->detect_tanggal($active_sheet->getCell('AA9')->getFormattedValue());
              }

             */
            $this->response_form_satu["tanggal_pelaporan_khusus"] = $this->__get_tanggal_pelaporan_khusus($active_sheet);

            $this->response_form_satu["gelar_depan"] = $active_sheet->getCell('R13')->getValue();
            $this->response_form_satu["gelar_belakang"] = $active_sheet->getCell('AV13')->getValue();
            $this->response_form_satu["nama"] = $active_sheet->getCell('W13')->getValue();
            $this->response_form_satu["jabatan"] = $active_sheet->getCell('R15')->getValue();
            $this->response_form_satu["unit_kerja"] = $active_sheet->getCell('AM15')->getValue();
            $this->response_form_satu["sub_unit_kerja"] = $active_sheet->getCell('R17')->getValue();
            $this->response_form_satu["lembaga"] = $active_sheet->getCell('AM17')->getValue();
            $this->response_form_satu["alamat_kantor"] = $active_sheet->getCell('R19')->getValue();
            $this->response_form_satu["kota_lapor"] = $active_sheet->getCell('AU45')->getValue();

//            $this->response_form_satu["tgl_lapor"] = $this->detect_tanggal($active_sheet->getCell('BA45')->getFormattedValue());
            $this->response_form_satu["tgl_lapor"] = $this->__get_tanggal_pelaporan_khusus($active_sheet);

            $final_jenis_laporan = "Periodik";
            if (in_array($this->response_form_satu["jenis_laporan"], $arr_laporan_khusus)) {
                $final_jenis_laporan = "khusus";
            }

            return TRUE;
        }

        $has_sheet = FALSE;
        return FALSE;
//        }
//        exit;
    }

    public function detect_tanggal($tgl_lahir = "") {
        $response_tanggal = "-";
        $is_tgl_timeformatted = FALSE;
        $flag = FALSE;
        if (preg_match("/[a-z]/i", $tgl_lahir)) {
            $tgl_lahir = trim(preg_replace('/\s+/', ' ', $tgl_lahir));
            $month = trim(preg_replace('/[0-9]/i', '', $tgl_lahir));

            $arr_tgl_th = explode(strtolower($month), strtolower(trim(preg_replace('/\s+/', '', $tgl_lahir))));

            $m_strtotime = strtotime($month);

            if (!$m_strtotime) {
                $m_strtotime = bulan($month, FALSE, TRUE);

                if (!is_null($m_strtotime)) {
                    $m_strtotime++;
                }
            } else {
                $m_strtotime = date('m', $m_strtotime);
            }

            if (!is_null($m_strtotime)) {
                $is_tgl_timeformatted = strtotime($arr_tgl_th[1] . "-" . $m_strtotime . "-" . $arr_tgl_th[0]);

//                    echo date('d-m-Y', $is_tgl_timeformatted)."       ".$arr_tgl_th[1] . "-" . $m_strtotime . "-" . $arr_tgl_th[0];exit;
            }

            if ($is_tgl_timeformatted) {
                $response_tanggal = date('Y-m-d', $is_tgl_timeformatted);
            }
        } else {
            /**
             * non alphabet
             */
            if (strlen($tgl_lahir) > 0 && trim($tgl_lahir) != "" && !is_null($tgl_lahir)) {
                $flag = 99;


                /**
                 * kombinasi tanggal dengan menggunakan - (strip)
                 */
                if (!$is_tgl_timeformatted) {
                    $arr_tgl = explode('-', trim(preg_replace('/\s+/', '', $tgl_lahir)));

                    if (count($arr_tgl) == 3) {
                        //mktime(0, 0, 0, 7, 1, 2000)
                        /**
                         * m-d-Y
                         * to d-m-Y
                         */
                        $flag = 1;
                        $is_tgl_timeformatted = strtotime($arr_tgl[1] . "-" . $arr_tgl[0] . "-" . $arr_tgl[2]);

                        /**
                         * [d-Y-m; Y-d-m; m-Y-d] to d-m-Y
                         */
                        if (!$is_tgl_timeformatted) {
                            $flag = 2;
                            $is_tgl_timeformatted = strtotime($arr_tgl[0] . "-" . $arr_tgl[2] . "-" . $arr_tgl[1]);
                        }

                        if ($is_tgl_timeformatted) {
                            $response_tanggal = date('Y-m-d', strtotime($tgl_lahir));
                        }
                    }
                }

                /**
                 * kombinasi tanggal dengan menggunakan / (slash)
                 */
                if (!$is_tgl_timeformatted) {
                    $arr_tgl = explode('/', trim(preg_replace('/\s+/', '', $tgl_lahir)));
                    if (count($arr_tgl) == 3) {
                        //mktime(0, 0, 0, 7, 1, 2000)
                        /**
                         * m-d-Y
                         * to d-m-Y
                         */
                        $flag = 3;
                        $is_tgl_timeformatted = strtotime($arr_tgl[1] . "-" . $arr_tgl[0] . "-" . $arr_tgl[2]);

                        /**
                         * [d-Y-m; Y-d-m; m-Y-d] to d-m-Y
                         */
                        if (!$is_tgl_timeformatted) {
                            $flag = 4;
                            $is_tgl_timeformatted = strtotime($arr_tgl[0] . "-" . $arr_tgl[2] . "-" . $arr_tgl[1]);
                        }
                    }
                }

                if (!$is_tgl_timeformatted) {
                    if (strtotime($tgl_lahir)) {
                        $flag = 90;
                        $response_tanggal = date('Y-m-d', strtotime($tgl_lahir));
                        $is_tgl_timeformatted = TRUE;
                    }
                }
            }
        }

        return $response_tanggal;
    }

    public function readSheet_SKM() {

        $counter_i = 1;
        $has_sheet = TRUE;

        $arr_laporan_khusus = [1, 4, 3];

        $sheet_name = "Lampiran3-SKM";
        try {
            $active_sheet = $this->excellib->setActiveSheetIndexByName($sheet_name);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return FAlSE;
        }

        if ($active_sheet !== FALSE) {
            $last_row = $active_sheet->getHighestRow();
            $this->response_form_dua["alamat_tinggal"] = $active_sheet->getCell('M10')->getValue();
            $this->response_form_dua["tempat_lahir"] = $active_sheet->getCell('M8')->getValue();

//            $this->response_form_dua["tanggal_lahir"] = $active_sheet->getCell('Z8')->getFormattedValue();
            $this->response_form_dua["tanggal_lahir"] = $this->detect_tanggal($active_sheet->getCell('Z8')->getFormattedValue());

//            $cth = "01/22/1976"; // m/d/Y (TRUE) diterima dari Excel
//            $cth = "22 january 1976"; // d m Y (TRUE) EN
//            $cth = "22 jan 1976"; // d M Y (TRUE)
//            $cth = "22-01-1976"; // d-m-Y (TRUE)
//            $cth = "1976-01-22"; // Y-m-d (TRUE)
//            
//            $cth = "22/01/1976"; // d/m/Y (FALSE)
//            $cth = "01-22-1976"; // m-d-Y (FALSE)
//            $cth = "22 januari 1976"; // d m Y (FALSE)
//            $cth = "22 01 1976"; // d m Y (FALSE)
//            $cth = "01 22 1976"; // m-d-Y (FALSE)
//            $cth = "1976 01 22"; // Y m d (FALSE)
//            $cth = "1976 22 01"; // Y d m (FALSE)
//            $cth = "1976 january 22"; // Y-m-d (FALSE)
//            $cth = "1976 jan 22"; // Y-m-d (FALSE)
//            $tgl_lahir = $active_sheet->getCell('Z8')->getFormattedValue();
//            $is_tgl_timeformatted = FALSE;
//            $flag = FALSE;
//            if (preg_match("/[a-z]/i", $tgl_lahir)) {
//                $tgl_lahir = trim(preg_replace('/\s+/', ' ', $tgl_lahir));
//                $month = trim(preg_replace('/[0-9]/i', '', $tgl_lahir));
//
//                $arr_tgl_th = explode(strtolower($month), strtolower(trim(preg_replace('/\s+/', '', $tgl_lahir))));
//
//                $m_strtotime = strtotime($month);
//
//                if (!$m_strtotime) {
//                    $m_strtotime = bulan($month, FALSE, TRUE);
//
//                    if (!is_null($m_strtotime)) {
//                        $m_strtotime++;
//                    }
//                }else{
//                    $m_strtotime = date('m', $m_strtotime);
//                }
//
//                if (!is_null($m_strtotime)) {
//                    $is_tgl_timeformatted = strtotime($arr_tgl_th[1] . "-" . $m_strtotime . "-" . $arr_tgl_th[0]);
//                    
////                    echo date('d-m-Y', $is_tgl_timeformatted)."       ".$arr_tgl_th[1] . "-" . $m_strtotime . "-" . $arr_tgl_th[0];exit;
//                }
//
//                if ($is_tgl_timeformatted) {
//                    $this->response_form_dua["tanggal_lahir"] = date('Y-m-d', $is_tgl_timeformatted);
//                }
//            } else {
//                /**
//                 * non alphabet
//                 */
//                if (strlen($tgl_lahir) > 0 && trim($tgl_lahir) != "" && !is_null($tgl_lahir)) {
//                    $flag = 99;
//
//
//                    /**
//                     * kombinasi tanggal dengan menggunakan - (strip)
//                     */
//                    if (!$is_tgl_timeformatted) {
//                        $arr_tgl = explode('-', trim(preg_replace('/\s+/', '', $tgl_lahir)));
//
//                        if (count($arr_tgl) == 3) {
//                            //mktime(0, 0, 0, 7, 1, 2000)
//                            /**
//                             * m-d-Y
//                             * to d-m-Y
//                             */
//                            $flag = 1;
//                            $is_tgl_timeformatted = strtotime($arr_tgl[1] . "-" . $arr_tgl[0] . "-" . $arr_tgl[2]);
//
//                            /**
//                             * [d-Y-m; Y-d-m; m-Y-d] to d-m-Y
//                             */
//                            if (!$is_tgl_timeformatted) {
//                                $flag = 2;
//                                $is_tgl_timeformatted = strtotime($arr_tgl[0] . "-" . $arr_tgl[2] . "-" . $arr_tgl[1]);
//                            }
//
//                            if ($is_tgl_timeformatted) {
//                                $this->response_form_dua["tanggal_lahir"] = date('Y-m-d', strtotime($tgl_lahir));
//                            }
//                        }
//                    }
//
//                    /**
//                     * kombinasi tanggal dengan menggunakan / (slash)
//                     */
//                    if (!$is_tgl_timeformatted) {
//                        $arr_tgl = explode('/', trim(preg_replace('/\s+/', '', $tgl_lahir)));
//                        if (count($arr_tgl) == 3) {
//                            //mktime(0, 0, 0, 7, 1, 2000)
//                            /**
//                             * m-d-Y
//                             * to d-m-Y
//                             */
//                            $flag = 3;
//                            $is_tgl_timeformatted = strtotime($arr_tgl[1] . "-" . $arr_tgl[0] . "-" . $arr_tgl[2]);
//
//                            /**
//                             * [d-Y-m; Y-d-m; m-Y-d] to d-m-Y
//                             */
//                            if (!$is_tgl_timeformatted) {
//                                $flag = 4;
//                                $is_tgl_timeformatted = strtotime($arr_tgl[0] . "-" . $arr_tgl[2] . "-" . $arr_tgl[1]);
//                            }
//                        }
//                    }
//
//                    if (!$is_tgl_timeformatted) {
//                        if (strtotime($tgl_lahir)) {
//                            $flag = 90;
//                            $this->response_form_dua["tanggal_lahir"] = date('Y-m-d', strtotime($tgl_lahir));
//                            $is_tgl_timeformatted = TRUE;
//                        }
//                    }
//                }
//            }
//            echo $this->response_form_dua["tanggal_lahir"],"   ".$tgl_lahir;exit;

            return TRUE;
        }

        $has_sheet = FALSE;
        return FALSE;
//        }
//        exit;
    }

}
