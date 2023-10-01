<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lhkpnoffline_verifikasi extends MY_Controller {

    public $limit = 10;
    public $iscetak = false;
    public $list_bukti = [];
    public $list_jenis_harta = [];

    public function __construct() {
        parent::__construct();
        $this->load->model('mglobal');
        $this->config->load('harta');
        call_user_func('ng::islogin');
        $this->uri_segment = 5;
        $this->offset = $this->uri->segment($this->uri_segment);

        // prepare search
        foreach ((array) @$this->input->post('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];
        }
    }

    function test_penerimaan_bc() {
        $this->load->model('Mimplhkpntolhkpn');
        $this->Mimplhkpntolhkpn->copy_to_penerimaan2(20, 23423, TRUE);
    }

    function view_uploaded($id_lhkpn = NULL, $mode = NULL, $show = false) {
        // cek jika $id_lhkpn null

        if ($id_lhkpn == NULL) {
            show_error('invalid url', 404);
            die('invalid url');
        }

        $this->load->model(array(
            'Mlhkpnofflinekeluarga',
            'Mimpxllhkpn',
            'Mlhkpnoffline',
            'Mlhkpnofflinefasilitas',
            'mlhkpn',
        ));

        $file_list = array();

        $upperli = $this->input->get('upperli');
        $bottomli = $this->input->get('bottomli');
        $this->data['upperli'] = $upperli ? $upperli : 'li1';
        $this->data['bottomli'] = $bottomli ? $bottomli : FALSE;
        unset($upperli, $bootomli);

        $this->load->model('mlhkpndokpendukung', '', TRUE);

        $this->data['icon'] = 'fa-book';
        $this->data['is_pn'] = $this->session->userdata('IS_PN');
        $this->data['title'] = 'Validasi Excel';
        $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
        $breadcrumbitem[] = ['E Filling' => 'index.php/dashboard/efilling'];
        $breadcrumbitem[] = [ucwords(strtolower(__CLASS__)) => $this->segmentTo[2]];
        $breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
        $breadcrumbdata = [];
        foreach ($breadcrumbitem as $list) {
            $breadcrumbdata = array_merge($breadcrumbdata, $list);
        }
        //        $joinMATA_UANG = $this->Mimpxllhkpn->joinMATA_UANG;
        $joinHARTA_TIDAK_BERGERAK = $this->Mimpxllhkpn->joinHARTA_TIDAK_BERGERAK;
        $joinHARTA_BERGERAK = $this->Mimpxllhkpn->joinHARTA_BERGERAK;
        $joinHARTA_PERABOTAN = $this->Mimpxllhkpn->joinHARTA_PERABOTAN;
        $joinHARTA_SURAT = $this->Mimpxllhkpn->joinHARTA_SURAT;
        $joinHARTA_KAS = $this->Mimpxllhkpn->joinHARTA_KAS;
        $joinHARTA_LAINNYA = $this->Mimpxllhkpn->joinHARTA_LAINNYA;

        $joinHARTA_HUTANG = $this->Mimpxllhkpn->joinHARTA_HUTANG;

        $where_eHARTA_TIDAK_BERGERAK = "data.id_imp_xl_lhkpn = '$id_lhkpn'";
        // $where_eHARTA_TIDAK_BERGERAK= "(provinsi.IDPROV = kabkot.IDPROV OR data.NEGARA = '1') and SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT_AREA";
        $PROV = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND '' = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as AREA_PROV";
        $selectHARTA_TIDAK_BERGERAK = 'IS_CHECKED, data.NEGARA AS ID_NEGARA, PROV, NAMA_NEGARA, KAB_KOT, IS_PELEPASAN, ATAS_NAMA_LAINNYA, STATUS, SIMBOL, data.id_imp_xl_lhkpn_harta_tidak_bergerak as ID, data.ID_HARTA as ID_HARTA, data.ID_imp_xl_LHKPN, data.ID_imp_xl_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL,' . $KABKOT . ',' . $PROV . ', data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

        $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);

        $this->data['list_harta'] = $this->get_list_jenis_harta();

        $this->data['rand_id'] = rand();
        $this->lhkpn_temp_file_bukti($id_lhkpn);

        $path_download_location = $this->get_download_lhkpn_import_location() . $this->data['rand_id'] . "/";
        $this->data['download_location'] = base_url() . $path_download_location;

        //        $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0];
        $please_show_me = $this->input->get('please_show_me');
        $not_send = " t_imp_xl_lhkpn.is_send <> 1 ";
        if ($please_show_me == 'r@h4s14n3g4r$') {
            $not_send = " 1=1 ";
        }
        $data_lhkpn = $this->mglobal->get_data_all('t_imp_xl_lhkpn', [['table' => 'T_PN', 'on' => 't_imp_xl_lhkpn.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "t_imp_xl_lhkpn.id_imp_xl_lhkpn = '$id_lhkpn' and $not_send ");

        $info_penerimaan_offline = $this->mglobal->get_by_id("t_lhkpnoffline_penerimaan", "ID_IMP_XL_LHKPN", $id_lhkpn);

        if ($data_lhkpn && $info_penerimaan_offline) {
            $this->data['LHKPN'] = current($data_lhkpn);
            $data_lhkpn = current($data_lhkpn);
        } else {
            show_error('invalid url', 404);
            die('invalid url');
        }

        //        $this->data['id_lhkpn'] = $this->data['LHKPN']->ID_LHKPN;
        $this->data['id_imp_xl_lhkpn'] = $this->data['LHKPN']->id_imp_xl_lhkpn;
        $this->data['secure_id_imp_xl_lhkpn'] = make_secure_text($this->data['id_imp_xl_lhkpn']);
        $this->data['status_lhkpn'] = $this->data['LHKPN']->STATUS;
        $this->data['info_penerimaan_offline'] = $info_penerimaan_offline;

        unset($info_penerimaan_offline);

        $summary_harta = $this->get_summary_harta($this->data['LHKPN']->id_imp_xl_lhkpn);
        $this->data = array_merge($summary_harta, $this->data);

        $this->data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
        $this->data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
        $imp_lhkpn_data_pribadi = $this->mglobal->get_data_all('t_imp_xl_lhkpn_data_pribadi', NULL, NULL, '*', "t_imp_xl_lhkpn_data_pribadi.id_imp_xl_lhkpn = '$id_lhkpn'");
        $this->data['DATA_PRIBADI'] = NULL;
        if (!empty($imp_lhkpn_data_pribadi)) {
            $this->data['DATA_PRIBADI'] = make_secure_object('id_imp_xl_lhkpn_data_pribadi', current($imp_lhkpn_data_pribadi));
            $this->save_to_folder_nik($this->data['DATA_PRIBADI'], FALSE, "FOTO", array(), FALSE);
        }
        unset($imp_lhkpn_data_pribadi);

        $this->Mimpxllhkpn->set_select_join_t_lhkpn_imp_xl_jabatan();

        $this->data['IMP_LHKPN_JABATAN'] = $this->mglobal->secure_get_by_secure_id('t_imp_xl_lhkpn_jabatan', 'id_imp_xl_lhkpn', 'id_imp_xl_lhkpn_jabatan', $id_lhkpn);
        
        $this->data['UNIT_KERJA_ADA'] = FALSE;
        $this->data['ID_JABATAN_ADA'] = FALSE;
        
//        var_dump($this->data['IMP_LHKPN_JABATAN']);exit;
        
        if($this->data['IMP_LHKPN_JABATAN']){
            if(!is_null($this->data['IMP_LHKPN_JABATAN']->ID_JABATAN) && strlen(trim($this->data['IMP_LHKPN_JABATAN']->ID_JABATAN)) > 0){
                $this->data['ID_JABATAN_ADA'] = TRUE;
            }
            if(!is_null($this->data['IMP_LHKPN_JABATAN']->UNIT_KERJA) && strlen(trim($this->data['IMP_LHKPN_JABATAN']->UNIT_KERJA)) > 0){
                $this->data['UNIT_KERJA_ADA'] = TRUE;
            }
        }

        $this->data['KAS'] = @$this->mglobal->get_data_all('t_imp_xl_lhkpn_harta_kas', NULL, "FILE_BUKTI <> '' AND KODE_JENIS <> '1'", '*', "id_imp_xl_lhkpn = '$id_lhkpn'");

        $this->data['rinci_keluargas'] = $this->Mlhkpnofflinekeluarga->get_rincian($id_lhkpn);

        $this->data['KELUARGAS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_keluarga', FALSE, 't_imp_xl_lhkpn_keluarga', NULL, NULL, '*', "id_imp_xl_lhkpn = '$id_lhkpn'");

        $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_harta_tidak_bergerak', 'data', 't_imp_xl_lhkpn_harta_tidak_bergerak as data', $joinHARTA_TIDAK_BERGERAK, NULL, array($selectHARTA_TIDAK_BERGERAK, FALSE), $where_eHARTA_TIDAK_BERGERAK);

        $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_harta_bergerak', 't_imp_xl_lhkpn_harta_bergerak', 't_imp_xl_lhkpn_harta_bergerak', $joinHARTA_BERGERAK, NULL, '*,t_imp_xl_lhkpn_harta_bergerak.ID_imp_xl_lhkpn_harta_bergerak, t_imp_xl_lhkpn_harta_bergerak.ID_imp_xl_lhkpn_harta_bergerak as ID_HARTA_BERGERAK', "t_imp_xl_lhkpn_harta_bergerak.id_imp_xl_lhkpn = '$id_lhkpn'");

        $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_harta_bergerak_lain', 't_imp_xl_lhkpn_harta_bergerak_lain', 't_imp_xl_lhkpn_harta_bergerak_lain', $joinHARTA_PERABOTAN, NULL, '*, t_imp_xl_lhkpn_harta_bergerak_lain.id_imp_xl_lhkpn_harta_bergerak_lain as ID_HARTA_BERGERAK2', "t_imp_xl_lhkpn_harta_bergerak_lain.id_imp_xl_lhkpn = '$id_lhkpn'");
        $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_harta_surat_berharga', 't_imp_xl_lhkpn_harta_surat_berharga', 't_imp_xl_lhkpn_harta_surat_berharga', $joinHARTA_SURAT, NULL, "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN, t_imp_xl_lhkpn_harta_surat_berharga.id_imp_xl_lhkpn_harta_surat_berharga as ID_SURAT_BERHARGA", "t_imp_xl_lhkpn_harta_surat_berharga.id_imp_xl_lhkpn = '$id_lhkpn'");
        $this->data['HARTA_KASS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_harta_kas', FALSE, 't_imp_xl_lhkpn_harta_kas', $joinHARTA_KAS, NULL, '*, t_imp_xl_lhkpn_harta_kas.id_imp_xl_lhkpn_harta_kas as ID_KAS', "t_imp_xl_lhkpn_harta_kas.id_imp_xl_lhkpn = '$id_lhkpn'");
        $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_harta_lainnya', FALSE, 't_imp_xl_lhkpn_harta_lainnya', $joinHARTA_LAINNYA, NULL, '*,t_imp_xl_lhkpn_harta_lainnya.id_imp_xl_lhkpn_harta_lainnya as ID_HARTA_LAINNYA', "t_imp_xl_lhkpn_harta_lainnya.id_imp_xl_lhkpn = '$id_lhkpn'");
        $this->data['HUTANGS'] = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_hutang', FALSE, 't_imp_xl_lhkpn_hutang', $joinHARTA_HUTANG, NULL, '*, m_jenis_hutang.NAMA AS NAMA_JENIS_HUTANG', "t_imp_xl_lhkpn_hutang.id_imp_xl_lhkpn = '$id_lhkpn'");
        $this->data['PENERIMAAN_KASS'] = $this->data['getGolongan1'];
        $this->data['PENGELUARAN_KASS'] = $this->data['getGolongan2'];

        $this->data['lamp2s'] = $this->mglobal->get_data_all_secure('ID_imp_xl_lhkpn_fasilitas', FALSE, 't_imp_xl_lhkpn_fasilitas', NULL, NULL, '*', "t_imp_xl_lhkpn_fasilitas.id_imp_xl_lhkpn = '$id_lhkpn'");

        $this->data['dokpendukungs'] = FALSE;

        $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);

        $this->data['list_bukti'] = $this->get_list_bukti();
        $this->data['list_bukti_alat_transportasi'] = $this->get_list_bukti('2', 1, ['ID_JENIS_BUKTI', 'ASC']);

        $this->data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
        $this->data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);

        // SELECT item verification
        $verificationItem = $this->mglobal->get_data_all('T_VERIFICATION_ITEM', null, null, '*', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        foreach ($verificationItem as $key) {
            $this->data['verifItem'][$key->ITEMVER][$key->ID] = ['hasil' => $key->HASIL, 'catatan' => $key->CATATAN];
        }
        // SELECT hasil verifikasi
        $this->data['hasilVerifikasi'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1'], 'HASIL_VERIFIKASI', "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0]->HASIL_VERIFIKASI);

        $this->data['lampiran_pelepasan'] = $this->get_pelepasan($id_lhkpn);

        //perhitunganpemaasukan kas
        $whereperhitunganPengPem = "(IS_ACTIVE = '1' OR  IS_ACTIVE IS NULL) AND id_imp_xl_lhkpn = '$id_lhkpn'";
//        $this->data['getPenka'] = $this->mlhkpn->getValue('t_imp_xl_lhkpn_pengeluaran_kas', $whereperhitunganpengeluaran);
        $this->data['getPenka'] = $this->mglobal->secure_get_by_secure_id('t_imp_xl_lhkpn_pengeluaran_kas', 'id_imp_xl_lhkpn', 'id_imp_xl_lhkpn_pengeluaran_kas', $id_lhkpn, 'object', $whereperhitunganPengPem);

        //perhitunganpengeluaran kas
//        $this->data['getPemka'] = $this->mlhkpn->getValue('t_imp_xl_lhkpn_penerimaan_kas', $whereperhitunganpemaasukan);
        $this->data['getPemka'] = $this->mglobal->secure_get_by_secure_id('t_imp_xl_lhkpn_penerimaan_kas', 'id_imp_xl_lhkpn', 'id_imp_xl_lhkpn_penerimaan_kas', $id_lhkpn, 'object', $whereperhitunganPengPem);
        $this->data['mode'] = $mode;

//        $agenda = date('Y', strtotime($this->data['LHKPN']->TGL_LAPOR)) . '/' . ($this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['LHKPN']->NIK . '/' . $this->data['LHKPN']->ID_LHKPN;

        $this->data['tampil'] = 'LHKPN : - ';

        if ($this->data['DATA_PRIBADI'] != NULL) {
            $this->data['tampil'] = 'LHKPN : ' . show_me($this->data['DATA_PRIBADI'], 'NAMA_LENGKAP') . ' (' . show_me($this->data['DATA_PRIBADI'], 'NIK') . ')';
        }
//echo "ada";exit;
//        $this->data['lampiran_hibah'] = FALSE;
//        $this->data['lampiran_hibah'] = $this->_lampiran_hibah($id_lhkpn); // lihat di controller LHKPN.php

        $this->data['show'] = $show;

        $this->data['aStatus'] = [
            1 => [
                'label' => 'info',
                'name' => 'Tetap'
            ],
            2 => [
                'label' => 'warning',
                'name' => 'Ubah'
            ],
            3 => [
                'label' => 'success',
                'name' => 'Baru'
            ],
            4 => [
                'label' => 'danger',
                'name' => 'Lapor'
            ]
        ];

        $this->data["jenis_penerimaan_kas_pn"] = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $this->data["golongan_penerimaan_kas_pn"] = $this->config->item('golongan_penerimaan_kas_pn', 'harta');
        $this->data["jenis_pengeluaran_kas_pn"] = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $this->data["golongan_pengeluaran_kas_pn"] = $this->config->item('golongan_pengeluaran_kas_pn', 'harta');
        $this->data["lhkpn_offline_melalui"] = array_flip($this->config->item('lhkpn_offline_melalui', 'harta'));

        $this->data["hubungan_keluarga"] = $this->config->item('hubungan_keluarga', 'harta');
        $js_page = array();

//        $data_lhkpn
        $file_list_ikhtisar = explode(", ", $data_lhkpn->FILE_BUKTI_IKHTISAR);
        $this->data['file_list_ikhtisar'] = current($file_list_ikhtisar) == "" ? array() : $file_list_ikhtisar;
        $file_list_skm = explode(", ", $data_lhkpn->FILE_BUKTI_SKM);
        $this->data['file_list_skm'] = current($file_list_skm) == "" ? array() : $file_list_skm;
        $file_list_sk = explode(", ", $data_lhkpn->FILE_BUKTI_SK);
        $this->data['file_list_sk'] = current($file_list_sk) == "" ? array() : $file_list_sk;

        unset($file_list_sk, $file_list_skm, $file_list_ikhtisar);

        $this->data['path_skm_upload'] = self::DIR_TEMP_SKM_UPLOAD;
        $this->data['path_skb_upload'] = self::DIR_TEMP_SKUASA_UPLOAD;
        $this->data['path_ikhtisar_upload'] = self::DIR_TEMP_IKHTISAR_UPLOAD;
        $this->data['path_temp_upload'] = self::DIR_TEMP_UPLOAD;
        $js_page[] = $this->load->view('lhkpn/v_include_plugin_js', array(), TRUE);
        $js_page[] = $this->load->view("lhkpnoffline/penerimaan/js/js_lwsupload", array(), TRUE);
        $js_page[] = $this->load->view("lhkpnoffline_validation/js/js_upload_dokumen", array(
            "id_imp_xl_lhkpn_secured" => make_secure_text($data_lhkpn->id_imp_xl_lhkpn),
            "LHKPN" => $this->data['LHKPN'],
            "path_temp_upload" => self::DIR_TEMP_UPLOAD,
            "rand_id_path" => $this->data['lhkpn_penerimaan']->RAND_ID,
                ), TRUE);
        $this->data["js_page"] = $js_page;

        //var_dump($this->data['KELUARGAS']);exit;
//        $this->load->view('lhkpnoffline/penerimaan/js/js_lws_upload');
        $this->load->view('lhkpn/lhkpn_entry2', $this->data);
    }

    function delete_penerimaan_offline($id = FALSE) {

        if ($id) {

            $data = array(
                'IS_ACTIVE' => '0'
            );
            $this->db->where('ID_IMP_XL_LHKPN', $id);
            $this->db->update('t_lhkpnoffline_penerimaan', $data);
        }
//        redirect('efill/lhkpnoffline/index/penerimaan');
    }

    function daftar_pemanfaatan($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $pemanfaatan = $this->mlhkpnharta->get_pemanfaatan($gol);
        foreach ($pemanfaatan as $key) {
            $data[$key->ID_PEMANFAATAN] = $key->NOMOR_KODE . '. ' . $key->PEMANFAATAN;
        }
        return $data;
    }

    /**
     * 
     * @param mix $gol isinya : 'all'(default), 1, 2    ; menurut golongan hartanya
     * @param mix $first_index isinya : primary_key(default), [number]  ; jika number maka index array akan dimulai dari number tersebut
     * @param array $order_by ['FIELD_NAME', 'DESC'] default = ['ID_JENIS_BUKTI', 'DESC']
     * @return type
     */
    private function get_list_bukti($gol = 'all', $first_index = 'primary_key', $order_by = FALSE) {
        $condition = "IS_ACTIVE = '1' ";
        if ($gol != 'all') {
            $condition .= " AND GOLONGAN_HARTA = '" . $gol . "'";
        }

        if (!$order_by) {
            $order_by = ['ID_JENIS_BUKTI', 'DESC'];
        }
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, $condition, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, $order_by);

        $this->list_bukti = [];
        foreach ($jenis_bukti as $key => $value) {
            if (is_numeric($first_index)) {
                $idx = $key + $first_index;
                $this->list_bukti[$idx] = $value->JENIS_BUKTI;
            } else {
                $this->list_bukti[$value->ID_JENIS_BUKTI] = $value->JENIS_BUKTI;
            }
        }
        unset($jenis_bukti);
        return $this->list_bukti;
    }

    private function get_list_jenis_harta() {
        if (empty($this->list_jenis_harta)) {
            $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA', NULL, ['ID_JENIS_HARTA', 'DESC']);
            $this->list_jenis_harta = [];
            foreach ($jenis_HARTA as $key) {
                $this->list_jenis_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
            }
            unset($jenis_HARTA);
        }
        return $this->list_jenis_harta;
    }

    private function show_in_array($obj, $keyname, $array_data, $default_value = "-") {
        return (in_array(show_me($obj, $keyname), array_keys($array_data)) ? show_me($obj, $keyname) : $default_value);
    }

    private function get_summary_harta($id_lhkpn = FALSE) {
        $summary_harta = [];

        if ($id_lhkpn) {
            $summary_harta['hartirak'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_harta_tidak_bergerak', 'NILAI_PELAPORAN', 'sum_hartirak', 'id_imp_xl_lhkpn');
            $summary_harta['harger'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_harta_bergerak', 'NILAI_PELAPORAN', 'sum_harger', 'id_imp_xl_lhkpn');
            $summary_harta['harger2'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_harta_bergerak_lain', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger2', 'id_imp_xl_lhkpn');
            $summary_harta['suberga'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_harta_surat_berharga', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_suberga', 'id_imp_xl_lhkpn');
            $summary_harta['kaseka'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_harta_kas', "NILAI_EQUIVALEN", 'sum_kaseka', 'id_imp_xl_lhkpn');
            $summary_harta['harlin'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_harta_lainnya', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harlin', 'id_imp_xl_lhkpn');
            $summary_harta['_hutang'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_imp_xl_lhkpn_hutang', 'SALDO_HUTANG', 'sum_hutang', 'id_imp_xl_lhkpn');
        }
        return $summary_harta;
    }

    private function get_pelepasan($id_lhkpn = FALSE) {
        $pelepasan = [];

        //jenis harta bergerak lain
        $list_harta_berhenti = [
            '1' => 'Perabotan Rumah Tangga',
            '2' => 'Barang Elektronik',
            '3' => 'Perhiasan & Logam / Batu Mulia',
            '4' => 'Barang Seni / Antik',
            '5' => 'Persediaan',
            '6' => 'Harta Bergerak Lainnya',
        ];
        //jenis harta surat berharga
        $list_harta_surat = [
            '1' => 'Penyertaan Modal pada Badan Hukum',
            '2' => 'Investasi',
        ];
        //jenis harta kas
        $list_harta_kas = [
            '1' => 'Uang Tunai',
            '2' => 'Deposite',
            '3' => 'Giro',
            '4' => 'Tabungan',
            '5' => 'Lainnya',
        ];
        //jenis harta lainnya
        $list_harta_lain = [
            '1' => 'Piutang',
            '2' => 'Kerjasama Usaha yang Tidak Berbadan Hukum',
            '3' => 'Hak Kekayaan Intelektual',
            '4' => 'Sewa Jangka Panjang Dibayar Dimuka',
            '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
        ];

        $list_bukti = $this->get_list_bukti();
        $list_harta = $this->get_list_jenis_harta();

        if (!$id_lhkpn) {
            return $pelepasan;
        }

        //select lampiran pelepasan
        $selectlampiranpelepasan = 'A.JENIS_PELEPASAN_HARTA, A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';

        $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_keluarga', FALSE, 't_imp_xl_lhkpn_keluarga', NULL, NULL, '*', "id_imp_xl_lhkpn = '$id_lhkpn'");

        $pelepasanhartabergeraklain = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_pelepasan_harta_bergerak_lain', 'A', 't_imp_xl_lhkpn_pelepasan_harta_bergerak_lain as A', NULL, NULL, "A.ID_imp_xl_lhkpn_pelepasan_harta_bergerak_lain, " . $selectlampiranpelepasan, "A.id_imp_xl_lhkpn = '" . $id_lhkpn . "'");
        $pelepasankas = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_pelepasan_harta_kas', 'A', 't_imp_xl_lhkpn_pelepasan_harta_kas as A', NULL, NULL, "A.ID_imp_xl_lhkpn_pelepasan_harta_kas, " . $selectlampiranpelepasan, "A.id_imp_xl_lhkpn = '" . $id_lhkpn . "'");
        $pelepasanhartatidakbergerak = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_pelepasan_harta_tidak_bergerak', 'A', 't_imp_xl_lhkpn_pelepasan_harta_tidak_bergerak as A', NULL, NULL, "A.ID_imp_xl_lhkpn_pelepasan_harta_tidak_bergerak, " . $selectlampiranpelepasan, "A.id_imp_xl_lhkpn = '" . $id_lhkpn . "'");
        $pelepasanhartabergerak = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_pelepasan_harta_bergerak', 'A', 't_imp_xl_lhkpn_pelepasan_harta_bergerak as A', NULL, NULL, "A.ID_imp_xl_lhkpn_pelepasan_harta_bergerak, " . $selectlampiranpelepasan, "A.id_imp_xl_lhkpn = '" . $id_lhkpn . "'");
        $pelepasansuratberharga = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_pelepasan_harta_surat_berharga', 'A', 't_imp_xl_lhkpn_pelepasan_harta_surat_berharga as A', NULL, NULL, "A.ID_imp_xl_lhkpn_pelepasan_harta_surat_berharga, " . $selectlampiranpelepasan, "A.id_imp_xl_lhkpn = '" . $id_lhkpn . "'");
        $pelepasanhartalainnya = $this->mglobal->get_data_all_secure('id_imp_xl_lhkpn_pelepasan_harta_lainnya', 'A', 't_imp_xl_lhkpn_pelepasan_harta_lainnya as A', NULL, NULL, "A.ID_imp_xl_lhkpn_pelepasan_harta_lainnya, " . $selectlampiranpelepasan, "A.id_imp_xl_lhkpn = '" . $id_lhkpn . "'");

        //packing hasil calling data lampiran pelepasan
        if (!empty($pelepasanhartatidakbergerak)) {
            foreach ($pelepasanhartatidakbergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->get_jenis_pelepasan_data($key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => show_me($key, 'TANGGAL_TRANSAKSI'),
                    'URAIAN_HARTA' => "Tanah/Bangunan , Atas Nama " . show_me($key, 'ATAS_NAMA') . " dengan luas tanah " . show_me($key, 'LUAS_TANAH') . " dan luas bangunan " . show_me($key, 'LUAS_BANGUNAN') . " dengan bukti berupa " . $this->show_in_array($key, 'JENIS_BUKTI', $list_bukti) . " dengan nomor bukti " . show_me($key, 'NOMOR_BUKTI'),
                    'ALAMAT' => show_me($key, 'ALAMAT'),
                    'NILAI' => show_me($key, 'NILAI_PELEPASAN'),
                    'PIHAK_DUA' => show_me($key, 'NAMA'),
                    'STATUS' => '0',
                    'id_secure' => show_me($key, 'id_imp_xl_lhkpn_pelepasan_harta_tidak_bergerak_secure'),
                    'jpl' => make_secure_text("harta_tidak_bergerak"),
                ];
            }
        }
        if (!empty($pelepasanhartabergerak)) {
            foreach ($pelepasanhartabergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->get_jenis_pelepasan_data($key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => show_me($key, 'TANGGAL_TRANSAKSI'),
                    'URAIAN_HARTA' => "Sebuah " . $this->show_in_array($key, 'KODE_JENIS', $list_harta) . " , Atas Nama " . show_me($key, 'ATAS_NAMA') . " , merek " . show_me($key, 'MEREK') . " dengan nomor registrasi " . show_me($key, 'NOPOL_REGISTRASI') . " dan nomor bukti " . show_me($key, 'NOMOR_BUKTI'),
                    'ALAMAT' => show_me($key, 'ALAMAT'),
                    'NILAI' => show_me($key, 'NILAI_PELEPASAN'),
                    'PIHAK_DUA' => show_me($key, 'NAMA'),
                    'STATUS' => '0',
                    'id_secure' => show_me($key, 'id_imp_xl_lhkpn_pelepasan_harta_bergerak_secure'),
                    'jpl' => make_secure_text("harta_bergerak"),
                ];
            }
        }
        if (!empty($pelepasanhartabergeraklain)) {
            foreach ($pelepasanhartabergeraklain as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->get_jenis_pelepasan_data($key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => show_me($key, 'TANGGAL_TRANSAKSI'),
                    'URAIAN_HARTA' => $this->show_in_array($key, 'KODE_JENIS', $list_harta_berhenti) . " bernama " . show_me($key, 'NAMA_HARTA') . " , Atas nama " . show_me($key, 'ATAS_NAMA') . " dengan jumlah " . show_me($key, 'JUMLAH') . ' ' . show_me($key, 'SATUAN'),
                    'ALAMAT' => show_me($key, 'ALAMAT'),
                    'NILAI' => show_me($key, 'NILAI_PELEPASAN'),
                    'PIHAK_DUA' => show_me($key, 'NAMA'),
                    'STATUS' => '0',
                    'id_secure' => show_me($key, 'id_imp_xl_lhkpn_pelepasan_harta_bergerak_lain_secure'),
                    'jpl' => make_secure_text("harta_bergerak_lainnya"),
                ];
            }
        }
        if (!empty($pelepasansuratberharga)) {
            foreach ($pelepasansuratberharga as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->get_jenis_pelepasan_data($key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => show_me($key, 'TANGGAL_TRANSAKSI'),
                    'URAIAN_HARTA' => $this->show_in_array($key, 'KODE_JENIS', $list_harta_surat) . ', Atas nama ' . show_me($key, 'ATAS_NAMA') . ' berupa surat ' . show_me($key, 'NAMA_SURAT') . ' dengan jumlah ' . show_me($key, 'JUMLAH') . ' ' . show_me($key, 'SATUAN'),
                    'ALAMAT' => show_me($key, 'ALAMAT'),
                    'NILAI' => show_me($key, 'NILAI_PELEPASAN'),
                    'PIHAK_DUA' => show_me($key, 'NAMA'),
                    'STATUS' => '0',
                    'id_secure' => show_me($key, 'id_imp_xl_lhkpn_pelepasan_harta_surat_berharga_secure'),
                    'jpl' => make_secure_text("surat_berharga"),
                ];
            }
        }
        if (!empty($pelepasankas)) {
            foreach ($pelepasankas as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->get_jenis_pelepasan_data($key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => show_me($key, 'TANGGAL_TRANSAKSI'),
                    'URAIAN_HARTA' => "KAS berupa " . $this->show_in_array($key, 'KODE_JENIS', $list_harta_kas) . ', Atas nama ' . show_me($key, 'ATAS_NAMA') . ' pada bank ' . show_me($key, 'NAMA_BANK') . ' dengan nomor rekening ' . show_me($key, 'NOMOR_REKENING'),
                    'ALAMAT' => show_me($key, 'ALAMAT'),
                    'NILAI' => show_me($key, 'NILAI_PELEPASAN'),
                    'PIHAK_DUA' => show_me($key, 'NAMA'),
                    'STATUS' => '0',
                    'id_secure' => show_me($key, 'id_imp_xl_lhkpn_pelepasan_harta_kas_secure'),
                    'jpl' => make_secure_text("kas_dan_setara_kas"),
                ];
            }
        }
        if (!empty($pelepasanhartalainnya)) {
            foreach ($pelepasanhartalainnya as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->get_jenis_pelepasan_data($key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => show_me($key, 'TANGGAL_TRANSAKSI'),
                    'URAIAN_HARTA' => "Harta lain berupa " . $this->show_in_array($key, 'KODE_JENIS', $list_harta_lain) . ' dengan nama harta ' . show_me($key, 'NAMA_HARTA') . ' atas nama ' . show_me($key, 'ATAS_NAMA'),
                    'ALAMAT' => show_me($key, 'ALAMAT'),
                    'NILAI' => show_me($key, 'NILAI_PELEPASAN'),
                    'PIHAK_DUA' => show_me($key, 'NAMA'),
                    'STATUS' => '0',
                    'id_secure' => show_me($key, 'id_imp_xl_lhkpn_pelepasan_harta_lainnya_secure'),
                    'jpl' => make_secure_text("harta_lainnya"),
                ];
            }
        }

        unset($list_bukti);

        return $pelepasan;
    }

    private function get_jenis_pelepasan_data($index_jenis_pelepasan = FALSE) {
        $text_jenis_pelepasan = "-";
        if ($index_jenis_pelepasan) {
            $jenis_pelepasan = $this->config->item('jenis_pelepasan', 'harta');
            $text_jenis_pelepasan = (in_array($index_jenis_pelepasan, array_keys($jenis_pelepasan)) ? $jenis_pelepasan[$index_jenis_pelepasan] : "-");
            unset($jenis_pelepasan);
        }
        return $text_jenis_pelepasan;
    }

}
