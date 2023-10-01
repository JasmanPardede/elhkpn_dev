<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mcopylhkpntolhkpn extends CI_Model {

    /**
     *
     * akan direplace oleh controller
     * ambil path upload seharusnya menggunakan referensi dari MY_CONTROLLER
     */
    public $DIR_TEMP_UPLOAD = './uploads/lhkpn_import_excel/temp/';
    public $DIR_TEMP_SKM_UPLOAD = './uploads/lhkpn_import_excel/temp/skm/';
    public $DIR_TEMP_SKUASA_UPLOAD = './uploads/lhkpn_import_excel/temp/sk/';
    public $DIR_TEMP_IKHTISAR_UPLOAD = './uploads/lhkpn_import_excel/temp/ikhtisar/';
    public $DIR_IMP_UPLOAD = './uploads/lhkpn_import_excel/final/';
    public $DIR_SKM_UPLOAD = './uploads/data_skm/';
    public $DIR_SKUASA_UPLOAD = './uploads/data_sk/';
    public $DIR_IKHTISAR_UPLOAD = './uploads/ikhtisar/';
    private $status_lhkpn = '0';
    private $id_pn = FALSE;

    public function __construct() {
        parent::__construct();
        $this->config->load('harta');
    }

    private function __set_id_pn_by_id_imp_lhkpn($id_imp_xl_lhkpn) {
        
    }

    public function copy_to_lhkpn($id_lhkpn_prev) { display($id_lhkpn_prev);die;

        $id_lhkpn = $this->copy_to_t_lhkpn($id_lhkpn_prev);
            
        if ($id_lhkpn) {
            $this->copy_to_t_lhkpn_data_pribadi($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_fasilitas($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_bergerak($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_bergerak_lain($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_kas($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_lainnya($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_surat_berharga($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_harta_tidak_bergerak($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_hutang($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_keluarga($id_imp_xl_lhkpn, $id_lhkpn);
            
            $this->copy_to_t_lhkpn_jabatan($id_imp_xl_lhkpn, $id_lhkpn);
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_bergerak", "t_lhkpn_pelepasan_harta_bergerak");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_bergerak_lain", "t_lhkpn_pelepasan_harta_bergerak_lain");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_kas", "t_lhkpn_pelepasan_harta_kas");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_lainnya", "t_lhkpn_pelepasan_harta_lainnya");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_surat_berharga", "t_lhkpn_pelepasan_harta_surat_berharga");
//        $this->copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, "t_imp_xl_lhkpn_pelepasan_harta_tidak_bergerak", "t_lhkpn_pelepasan_harta_tidak_bergerak");
            $this->copy_to_t_lhkpn_penerimaan_kas($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_t_lhkpn_pengeluaran_kas($id_imp_xl_lhkpn, $id_lhkpn);

            $this->copy_to_pengeluaran2($id_imp_xl_lhkpn, $id_lhkpn);
            $this->copy_to_penerimaan2($id_imp_xl_lhkpn, $id_lhkpn);
            
            
        }
        return $id_lhkpn;
    }

    public function copy_to_penerimaan2($id_imp_xl_lhkpn, $id_lhkpn, $return_data_array = FALSE, $debug = FALSE) {

        $data_detail = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn_penerimaan_kas", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn_penerimaan_kas", make_secure_text($id_imp_xl_lhkpn));

//        if ($data_detail && !is_null($data_detail)) {

        $pn = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PN) ? $data_detail->NILAI_PENERIMAAN_KAS_PN : "{}");
        $pa = json_decode(!is_null($data_detail->NILAI_PENERIMAAN_KAS_PASANGAN) ? $data_detail->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");

        $jenis_penerimaan_kas_pn = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $golongan_penerimaan_kas_pn = $this->config->item('golongan_penerimaan_kas_pn', 'harta');

//            var_dump($jenis_penerimaan_kas_pn, $golongan_penerimaan_kas_pn);
//        exit;

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
        if ($debug) {
            var_dump($data_arr);
            exit;
        }

        if ($return_data_array) {
            return $data_arr;
        }

        $this->db->insert_batch('t_lhkpn_penerimaan_kas2', $data_arr);
    }

    public function copy_to_pengeluaran2($id_imp_xl_lhkpn, $id_lhkpn, $return_data_array = FALSE, $debug = FALSE) {

        $data_detail = $this->mglobal->secure_get_by_id("t_imp_xl_lhkpn_pengeluaran_kas", "id_imp_xl_lhkpn", "id_imp_xl_lhkpn_pengeluaran_kas", make_secure_text($id_imp_xl_lhkpn));

        $pn = json_decode(!is_null($data_detail->NILAI_PENGELUARAN_KAS) ? $data_detail->NILAI_PENGELUARAN_KAS : "{}");

        $jenis_pengeluaran_kas_pn = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $label = array('A', 'B', 'C');

        $data_arr = array();

        $k = 0;
        for ($i = 0; $i < count($jenis_pengeluaran_kas_pn); $i++) {
            for ($j = 0; $j < count($jenis_pengeluaran_kas_pn[$i]); $j++) {
                $code = $label[$i] . $j;

//                $result->{$code} = '0';

                $data_arr[$k] = array(
                    "ID_LHKPN" => $id_lhkpn,
                    "GROUP_JENIS" => $label[$i],
                    "KODE_JENIS" => $code,
                    "JENIS_PENGELUARAN" => $jenis_pengeluaran_kas_pn[$i][$j],
                    "JML" => 0,
                );

                if (property_exists($pn, $label[$i]) && property_exists($pn->{$label[$i]}[$j], $code)) {
//                    $result->{$code} = $pn->{$label[$i]}[$j]->$code;
                    $data_arr[$k]["JML"] = $pn->{$label[$i]}[$j]->$code;
                }
                $k++;
            }
        }

        unset($pn, $jenis_pengeluaran_kas_pn);
        if ($debug) {
            var_dump($data_arr);
            exit;
        }

        if ($return_data_array) {
            return $data_arr;
        }

        $this->db->insert_batch('t_lhkpn_pengeluaran_kas2', $data_arr);
    }

    private function copy_to_t_lhkpn_pengeluaran_kas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_pengeluaran_kas (  ID_LHKPN,   ID_JENIS_PENGELUARAN_KAS,   LAINNYA,   NILAI_PENGELUARAN_KAS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP) "
                . "(select   " . $id_lhkpn . ",   ID_JENIS_PENGELUARAN_KAS,   LAINNYA,   NILAI_PENGELUARAN_KAS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP "
                . " from t_imp_xl_lhkpn_pengeluaran_kas "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_penerimaan_kas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_penerimaan_kas (  ID_LHKPN,   ID_JENIS_PENERIMAAN_KAS,   LAINNYA,   NILAI_PENERIMAAN_KAS_PN,   NILAI_PENERIMAAN_KAS_PASANGAN,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP) "
                . "(select   " . $id_lhkpn . ",   ID_JENIS_PENERIMAAN_KAS,   LAINNYA,   NILAI_PENERIMAAN_KAS_PN,   NILAI_PENERIMAAN_KAS_PASANGAN,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP "
                . " from t_imp_xl_lhkpn_penerimaan_kas "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_pelepasan($id_imp_xl_lhkpn, $id_lhkpn, $table_source, $table_destination) {
        $sql = "insert into " . $table_destination . " (  ID_HARTA,   ID_LHKPN,   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from " . $table_source . " "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_pelepasan_harta_bergerak($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_pelepasan_harta_bergerak (  ID_HARTA,   ID_LHKPN,   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   JENIS_PELEPASAN_HARTA,   TANGGAL_TRANSAKSI,   URAIAN_HARTA,   NILAI_PELEPASAN,   NAMA,   ALAMAT,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from t_imp_xl_lhkpn_pelepasan_harta_bergerak "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_keluarga($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_keluarga (  ID_LHKPN,   NIK,   NAMA,   HUBUNGAN,   STATUS_HUBUNGAN,   TEMPAT_LAHIR,   TANGGAL_LAHIR,   JENIS_KELAMIN,   TEMPAT_NIKAH,   TANGGAL_NIKAH,   TEMPAT_CERAI,   TANGGAL_CERAI,   PEKERJAAN,   ALAMAT_RUMAH,   NOMOR_TELPON,   IS_ACTIVE,   KETERANGAN_DIHAPUS,   STATUS_CETAK_SURAT_KUASA,   CETAK_SURAT_KUASA_TIME,   SURAT_KUASA,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta) "
                . "(select   " . $id_lhkpn . ",   NIK,   NAMA,   HUBUNGAN,   STATUS_HUBUNGAN,   TEMPAT_LAHIR,   TANGGAL_LAHIR,   JENIS_KELAMIN,   TEMPAT_NIKAH,   TANGGAL_NIKAH,   TEMPAT_CERAI,   TANGGAL_CERAI,   PEKERJAAN,   ALAMAT_RUMAH,   NOMOR_TELPON,   IS_ACTIVE,   KETERANGAN_DIHAPUS,   STATUS_CETAK_SURAT_KUASA,   CETAK_SURAT_KUASA_TIME,   SURAT_KUASA,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   FormulirID,   ref_form_harta "
                . " from t_imp_xl_lhkpn_keluarga "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_hutang($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_hutang (  ID_HARTA,   ID_LHKPN,   ATAS_NAMA,   KODE_JENIS,   NAMA_KREDITUR,   TANGGAL_TRANSAKSI,   TANGGAL_JATUH_TEMPO,   AGUNAN,   AWAL_HUTANG,   SALDO_HUTANG,   STATUS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   ATAS_NAMA,   KODE_JENIS,   NAMA_KREDITUR,   TANGGAL_TRANSAKSI,   TANGGAL_JATUH_TEMPO,   AGUNAN,   AWAL_HUTANG,   SALDO_HUTANG,   STATUS,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA "
                . " from t_imp_xl_lhkpn_hutang "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_tidak_bergerak($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_tidak_bergerak (ID_HARTA,   ID_LHKPN,   NEGARA,   ID_NEGARA,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta) "
                . "(select ID_HARTA,   " . $id_lhkpn . ",   1,   2,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
                . " from t_imp_xl_lhkpn_harta_tidak_bergerak "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        /**
         * @todo Untuk Versi Excel Selanjutnya ID NEGARA (yg sekarang nilainya 1) dan NEGARA (yg sekarang nilainya 2) sebaiknya diberikan deteksi dari excel
         */
//        $sql = "insert into t_lhkpn_harta_tidak_bergerak (ID_HARTA,   ID_LHKPN,   NEGARA,   ID_NEGARA,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta) "
//                . "(select ID_HARTA,   " . $id_lhkpn . ",   1,   2,   JALAN,   KEL,   KEC,   KAB_KOT,   PROV,   LUAS_TANAH,   LUAS_BANGUNAN,   KETERANGAN,   JENIS_BUKTI,   NOMOR_BUKTI,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
//                . " from t_imp_xl_lhkpn_harta_tidak_bergerak "
//                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_surat_berharga($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_surat_berharga (  ID_HARTA,   ID_LHKPN,   KODE_JENIS,   JUMLAH,   SATUAN,   NAMA_SURAT_BERHARGA,   NAMA_PENERBIT,   CUSTODIAN,   NOMOR_REKENING,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_PELEPASAN,   STATUS,   IS_CHECKED,   FILE_BUKTI,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   JUMLAH,   SATUAN,   NAMA_SURAT_BERHARGA,   NAMA_PENERBIT,   CUSTODIAN,   NOMOR_REKENING,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   0,   STATUS,   IS_CHECKED,   FILE_BUKTI,   IS_LOAD,   FormulirID,   ref_form_harta,   KET_LAINNYA "
                . " from t_imp_xl_lhkpn_harta_surat_berharga "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_lainnya($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_lainnya (ID_HARTA,   ID_LHKPN,   KODE_JENIS,   NAMA,   KETERANGAN,   KUANTITAS,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   IS_PELEPASAN,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD ) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   NAMA,   KETERANGAN,   KUANTITAS,   ATAS_NAMA,   ASAL_USUL,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   STATUS,   0,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD "
                . " from t_imp_xl_lhkpn_harta_lainnya "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_kas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_kas (  ID_HARTA,   ID_LHKPN,   KODE_JENIS,   ASAL_USUL,   ATAS_NAMA_REKENING,   NAMA_BANK,   NOMOR_REKENING,   KETERANGAN,   TAHUN_BUKA_REKENING,   MATA_UANG,   NILAI_SALDO,   NILAI_KURS,   NILAI_EQUIVALEN,   STATUS,   IS_PELEPASAN,   FILE_BUKTI,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta ) "
                . "(select   ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   ASAL_USUL,   ATAS_NAMA_REKENING,   NAMA_BANK,   NOMOR_REKENING,   KETERANGAN,   TAHUN_BUKA_REKENING,   MATA_UANG,   NILAI_SALDO,   NILAI_KURS,   NILAI_EQUIVALEN,   STATUS,   0,   FILE_BUKTI,   IS_ACTIVE,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
                . " from t_imp_xl_lhkpn_harta_kas "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_bergerak_lain($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_bergerak_lain (ID_HARTA,   ID_LHKPN,   KODE_JENIS,   NAMA,   JUMLAH,   SATUAN,   KETERANGAN,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   STATUS,   IS_PELEPASAN,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta ) "
                . "(select ID_HARTA,   " . $id_lhkpn . ",   KODE_JENIS,   NAMA,   JUMLAH,   SATUAN,   KETERANGAN,   ATAS_NAMA,   ASAL_USUL,   PEMANFAATAN,   KET_LAINNYA,   TAHUN_PEROLEHAN_AWAL,   TAHUN_PEROLEHAN_AKHIR,   MATA_UANG,   NILAI_PEROLEHAN,   NILAI_PELAPORAN,   JENIS_NILAI_PELAPORAN,   STATUS,   0,   IS_ACTIVE,   JENIS_LEPAS,   TGL_TRANSAKSI,   NILAI_JUAL,   NAMA_PIHAK2,   ALAMAT_PIHAK2,   CREATED_TIME,   CREATED_BY,   CREATED_IP,   UPDATED_TIME,   UPDATED_BY,   UPDATED_IP,   IS_CHECKED,   IS_LOAD,   FormulirID,   ref_form_harta "
                . " from t_imp_xl_lhkpn_harta_bergerak_lain "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_harta_bergerak($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_harta_bergerak (ID_LHKPN, KODE_JENIS, MEREK, MODEL, TAHUN_PEMBUATAN, NOPOL_REGISTRASI, NAMA, JUMLAH, SATUAN, JENIS_BUKTI, NOMOR_BUKTI, ATAS_NAMA, ASAL_USUL, PEMANFAATAN, KET_LAINNYA, TAHUN_PEROLEHAN_AWAL, TAHUN_PEROLEHAN_AKHIR, MATA_UANG, NILAI_PEROLEHAN, NILAI_PELAPORAN, JENIS_NILAI_PELAPORAN, IS_ACTIVE, JENIS_LEPAS, TGL_TRANSAKSI, NILAI_JUAL, NAMA_PIHAK2, ALAMAT_PIHAK2, STATUS, IS_PELEPASAN, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_CHECKED, IS_LOAD, FormulirID, ref_form_harta) "
                . "(select " . $id_lhkpn . ", KODE_JENIS, MEREK, MODEL, TAHUN_PEMBUATAN, NOPOL_REGISTRASI, NAMA, JUMLAH, SATUAN, JENIS_BUKTI, NOMOR_BUKTI, ATAS_NAMA, ASAL_USUL, PEMANFAATAN, KET_LAINNYA, TAHUN_PEROLEHAN_AWAL, TAHUN_PEROLEHAN_AKHIR, MATA_UANG, NILAI_PEROLEHAN, NILAI_PELAPORAN, JENIS_NILAI_PELAPORAN, IS_ACTIVE, JENIS_LEPAS, TGL_TRANSAKSI, NILAI_JUAL, NAMA_PIHAK2, ALAMAT_PIHAK2, STATUS, 0, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_CHECKED, IS_LOAD, FormulirID, ref_form_harta "
                . " from t_imp_xl_lhkpn_harta_bergerak "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_fasilitas($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_fasilitas (ID_FASILITAS, ID_LHKPN, JENIS_FASILITAS, NAMA_FASILITAS, PEMBERI_FASILITAS, KETERANGAN, KETERANGAN_LAIN, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_LOAD) "
                . "(select ID_FASILITAS, " . $id_lhkpn . ", JENIS_FASILITAS, NAMA_FASILITAS, PEMBERI_FASILITAS, KETERANGAN, KETERANGAN_LAIN, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, IS_LOAD "
                . " from t_imp_xl_lhkpn_fasilitas "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);

        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_jabatan($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_jabatan (ID_LHKPN, ID_JABATAN, DESKRIPSI_JABATAN, ESELON, LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, TMT, SD, ALAMAT_KANTOR, EMAIL_KANTOR, FILE_SK, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, ID_STATUS_AKHIR_JABAT, IS_PRIMARY, TEXT_JABATAN_PUBLISH) "
                . "(select " . $id_lhkpn . ", ID_JABATAN, DESKRIPSI_JABATAN, ESELON, LEMBAGA, UNIT_KERJA, SUB_UNIT_KERJA, TMT, SD, ALAMAT_KANTOR, EMAIL_KANTOR, FILE_SK, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, ID_STATUS_AKHIR_JABAT, 1, TEXT_JABATAN_PUBLISH "
                . " from t_imp_xl_lhkpn_jabatan "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";
        
        $res = $this->db->query($sql);
        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function copy_to_t_lhkpn_data_pribadi($id_imp_xl_lhkpn, $id_lhkpn) {
        $sql = "insert into t_lhkpn_data_pribadi (ID_LHKPN, id_agama, GELAR_DEPAN, GELAR_BELAKANG, NAMA_LENGKAP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, NIK, NPWP, STATUS_PERKAWINAN, AGAMA, JABATAN, JABATAN_LAINNYA, ALAMAT_RUMAH, EMAIL_PRIBADI, PROVINSI, KABKOT, KECAMATAN, KELURAHAN, TELPON_RUMAH, HP, HP_LAINNYA, FOTO, FILE_NPWP, FILE_KTP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, KD_ISO3_NEGARA, NEGARA, ALAMAT_NEGARA, formulirid_migrasi, pnid_migrasi, NO_KK) "
                . "(select " . $id_lhkpn . ", id_agama, GELAR_DEPAN, GELAR_BELAKANG, NAMA_LENGKAP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, NIK, NPWP, STATUS_PERKAWINAN, AGAMA, JABATAN, JABATAN_LAINNYA, ALAMAT_RUMAH, EMAIL_PRIBADI, PROVINSI, KABKOT, KECAMATAN, KELURAHAN, TELPON_RUMAH, HP, HP_LAINNYA, FOTO, FILE_NPWP, FILE_KTP, IS_ACTIVE, CREATED_TIME, CREATED_BY, CREATED_IP, UPDATED_TIME, UPDATED_BY, UPDATED_IP, KD_ISO3_NEGARA, NEGARA, ALAMAT_NEGARA, formulirid_migrasi, pnid_migrasi, NO_KK "
                . " from t_imp_xl_lhkpn_data_pribadi "
                . "where id_imp_xl_lhkpn = '" . $id_imp_xl_lhkpn . "')";

        $res = $this->db->query($sql);
        if ($res) {
            return TRUE;
        }
        return FALSE;
    }

    private function transfer_files($lhkpn_record, $id_lhkpn) {
        
        $nik = encrypt_username($lhkpn_record["NIK"], 'e');
        
        
        if(!is_null($lhkpn_record["FILE_BUKTI_SKM"])){
            $this->deploy_files($lhkpn_record["FILE_BUKTI_SKM"], $this->DIR_TEMP_SKM_UPLOAD.$nik, $this->DIR_SKM_UPLOAD . "$nik/$id_lhkpn/");
        }
        
        if(!is_null($lhkpn_record["FILE_BUKTI_SK"])){
            $this->deploy_files($lhkpn_record["FILE_BUKTI_SK"], $this->DIR_TEMP_SKUASA_UPLOAD.$nik, $this->DIR_SKUASA_UPLOAD . "$nik/$id_lhkpn/");
        }
        
        if(!is_null($lhkpn_record["FILE_BUKTI_IKHTISAR"])){
            $this->deploy_files($lhkpn_record["FILE_BUKTI_IKHTISAR"], $this->DIR_TEMP_IKHTISAR_UPLOAD.$nik, $this->DIR_IKHTISAR_UPLOAD . "$nik/$id_lhkpn/");
        }
        
    }
    
    private function deploy_files($string_file, $origin, $destination){
        $file_names = explode(", ", $string_file);
        if(is_array($file_names)){
            foreach($file_names as $file_name){
                rename($origin.$file_name, $destination.$file_name);
            }
        }
    }

    private function copy_to_t_lhkpn($id_lhkpn_prev) {
        //$this->db->join('t_lhkpnoffline_penerimaan', 't_lhkpnoffline_penerimaan.id_imp_xl_lhkpn = t_imp_xl_lhkpn.id_imp_xl_lhkpn');
        //$this->db->join('t_pn', 't_pn.ID_PN = t_lhkpnoffline_penerimaan.ID_PN');
        //$this->db->select('t_imp_xl_lhkpn.*, t_pn.NIK, t_lhkpnoffline_penerimaan.RAND_ID');
        $record_found = $this->mglobal->get_by_id("t_lhkpn", "t_lhkpn.id_lhkpn", $id_lhkpn_prev, 'array');
		//display($record_found);die;

        if ($record_found) {

            

            $record_found = (array) $record_found;
            unset(
                    $record_found['id_imp_xl_lhkpn'], $record_found['is_send']
            );

            $record_found['status'] = $this->status_lhkpn;
            $record_found['is_active'] = '1';
            
            $insert_record = $record_found;
            if(array_key_exists("NIK", $insert_record)){
                unset($insert_record["NIK"]);
            }
            if(array_key_exists("RAND_ID", $insert_record)){
                unset($insert_record["RAND_ID"]);
            }
            
//            ["FILE_BUKTI_SKM"]=>
//  string(12) "Penguins.jpg"
//  ["FILE_BUKTI_SK"]=>
//  string(40) "Tulips.jpg, Hydrangeas.jpg, Penguins.jpg"
//  ["FILE_BUKTI_IKHTISAR"]=>
//  string(39) "Lahir Wisada Santoso.JPG, GedungKPK.jpg"
            
            $insert_record["FILE_BUKTI_SKM"] = json_encode(explode(", ", $insert_record["FILE_BUKTI_SKM"]));
            $insert_record["FILE_BUKTI_SK"] = json_encode(explode(", ", $insert_record["FILE_BUKTI_SK"]));
            $insert_record["FILE_BUKTI_IKHTISAR"] = json_encode(explode(", ", $insert_record["FILE_BUKTI_IKHTISAR"]));

            $this->db->insert('t_lhkpn', $insert_record);
            $id_lhkpn = $this->db->insert_id();
            
            $this->transfer_files($record_found, $id_lhkpn);

            $this->db->where('id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
            $this->db->update('t_imp_xl_lhkpn', array('is_send' => '1'));

            $this->db->where('id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
            $this->db->update('t_lhkpnoffline_penerimaan', array(
                'is_send' => '1',
                'id_lhkpn' => $id_lhkpn
                ));

            return $id_lhkpn;
        }
        return FALSE;
    }

}
