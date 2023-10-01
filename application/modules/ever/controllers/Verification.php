<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Controller
 *
 * @author Gunaones - SKELETON-2015 - PT.Mitreka Solusi Indonesia
 * @package Ever/Controllers/Verification
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Verification extends Nglibs {

    private $jenis_data_lhkpn = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'DOKUMENPENDUKUNG' => 'Dokumen Pendukung', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];

    public function __construct() {
        parent::__construct();
        $this->makses->initialize();
        $this->load->model('ever/Verification_model');
    }

    protected function beforeQuery($method) {

        if ($method != 'lhkpn') {
            $this->db->start_cache();
        }
    }

    protected function afterQuery($method = FALSE) {
        if ($method != 'lhkpn') {
            parent::afterQuery();
        }
    }

    private function get_list_bukti($gol = 'all', $first_index = 'primary_key', $order_by = FALSE) {
        $condition = "IS_ACTIVE = '1' ";
        if ($gol != 'all') {
            $condition .= " AND GOLONGAN_HARTA = '".$gol."'";
        }

        if(!$order_by){
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

    public function saveFinalVerif(){ 
        
        
        $this->db->trans_begin();
        $ID_LHKPN = @$this->input->post('ID_LHKPN', TRUE);

        $TGL_VER = @$this->input->post('tgl_ver_', TRUE);
        $final = @$this->input->post('final', TRUE);

        $full_name = @$this->input->post('nama_lengkap_msg', TRUE);
        $ins_name = @$this->input->post('nama_instansi_msg', TRUE);
        $jab_name = @$this->input->post('nama_jabatan_msg', TRUE);
        $bid_name = @$this->input->post('nama_bidang_msg', TRUE);
        $thn_laporan = @$this->input->post('tahun_pelaporan_msg', TRUE);
        $msg_ver = null;
        $data = array();

        if ($final != NULL){
            $info = "";
            if($final == '1'){
                $info = "Lengkap";
            }else if($final == '3'){
                $info = "Tidak Lengkap";
            }

            $msg_ver = '
            <table>
                <tr>
                    <td>
                        Yth. Sdr. '.$full_name.' <br/>
                        '.$ins_name.'
                        <br/>
                        Di Tempat
                    </td>
                </tr>
            </table>

                    Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan '.$info.' dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
            <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
            <tbody class="body-table">
                <tr>
                    <td width="20%" valign="top"><b>Atas Nama</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$full_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Jabatan</b></td>
                    <td width="5%" valign="top"><b>:</td>
                    <td>'.$jab_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Bidang</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$bid_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Lembaga</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$ins_name.'</td>
                </tr>
                <tr>
                    <td width="20%" valign="top"><b>Tanggal / Tahun Pelaporan</b></td>
                    <td width="5%" valign="top"><b>:</b></td>
                    <td>'.$thn_laporan.'</td>
                </tr>
            </tbody>
            </table>
                <p>Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau call center 198.
                    <table><table>
                <tr>
                    <td>
                        Terima kasih<br/>

                        Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                        --------------------------------------------------------------<br/>
                        Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                        &copy; 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                    </td>
                </tr>
            </table>';
        }

        $hasil_verif_lengkap = '{"VAL":{"DATAPRIBADI":"1","JABATAN":"1","KELUARGA":"1","HARTATIDAKBERGERAK":"1","HARTABERGERAK":"1","HARTABERGERAK2":"1","SURATBERHARGA":"1","KAS":"1","HARTALAINNYA":"1","HUTANG":"1","PENERIMAANKAS":"1","PENGELUARANKAS":"1","PELEPASANHARTA":"1","PENERIMAANHIBAH":"1","PENERIMAANFASILITAS":"1","SURATKUASAMENGUMUMKAN":"1"},"MSG":{"DATAPRIBADI":"","JABATAN":"","KELUARGA":"","HARTATIDAKBERGERAK":"","HARTABERGERAK":"","HARTABERGERAK2":"","SURATBERHARGA":"","KAS":"","HARTALAINNYA":"","HUTANG":"","PENERIMAANKAS":"","PENGELUARANKAS":"","PELEPASANHARTA":"","PENERIMAANHIBAH":"","PENERIMAANFASILITAS":"","SURATKUASAMENGUMUMKAN":""}}';

        $msg_verif_ins = "Daftar kekurangan kelengkapan yang harus diisi dan dilengkapi oleh Sdr. $full_name, $jab_name $ins_name :";

        $simpan = @$this->input->post('simpan', TRUE);

        if($simpan == "draft"){
            $data = array(
                'TANGGAL' => date('Y-m-d'),
                'HASIL_VERIFIKASI' => $hasil_verif_lengkap,
                'MSG_VERIFIKASI_INSTANSI' => $msg_verif_ins,
                'STATUS_VERIFIKASI' => '0',
                'IS_ACTIVE' => '1'
            );

        }else{
            $data = array(
                'TANGGAL' => date('Y-m-d'),
                'HASIL_VERIFIKASI' => $hasil_verif_lengkap,
                'MSG_VERIFIKASI' => $msg_ver ? strip_tags($msg_ver) : null,
                'MSG_VERIFIKASI_INSTANSI' => $msg_verif_ins,
                'STATUS_VERIFIKASI' => '1',
                'IS_ACTIVE' => '1',
             );

        }

        $CountData = $this->mglobal->count_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN]);

        if ($CountData == 0) {
            $data['ID_LHKPN'] = $ID_LHKPN;
            $data['CREATED_TIME'] = time();
            $data['CREATED_BY'] = $this->session->userdata('USR');
            $data['CREATED_IP'] = $_SERVER["REMOTE_ADDR"];
            $this->db->insert('T_VERIFICATION', $data);

            /*
             * add by eko
             * insert status proses verifikasi ke history status lhkpn
             */
            $history = [
                'ID_LHKPN' => $ID_LHKPN,
                'ID_STATUS' => 6,
                'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                'USERNAME_PENERIMA' => '',
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            $do_act = $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
        }else{
            $do_act = $this->db->update('T_VERIFICATION', $data, ['ID_LHKPN' => $ID_LHKPN]);
        }

        if($simpan == 'draft'){
            if($do_act){
                $this->db->trans_commit();
                echo '1';
            }else{
                $this->db->trans_rollback();
                echo '0';
            }
        }

         $this->mglobal->update('T_VERIFICATION_ITEM', ['IS_EDITABLE' => '0'], ['ID_LHKPN' => $ID_LHKPN, 'HASIL' => '1']);

         if ($simpan !== 'draft') {

            if ($final == '1'){ //lengkap
                $status_verif = 17;
            } else if ($final == '3'){ //tidak lengkap
                $status_verif = 18;
            }


             $history = [
                'ID_LHKPN' => $ID_LHKPN,
                'ID_STATUS' => $status_verif,
                'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                'USERNAME_PENERIMA' => '',
                'DATE_INSERT' => date('Y-m-d H:i:s'),
                'CREATED_IP' => $this->input->ip_address()
            ];

            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);

            if ($final == '1') {
                $status = '3';
                $id_status_history = '11';
                $penerima = 'koordinator_announcement';
            }

            $res = [];
            $res['STATUS'] = $status;
            $result = $this->db->update('T_LHKPN', $res, ['ID_LHKPN' => $ID_LHKPN]);

            if ($result) {
                // History
                $history = [
                    'ID_LHKPN' => $ID_LHKPN,
                    'ID_STATUS' => $id_status_history,
                    'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                    'USERNAME_PENERIMA' => $penerima,
                    'DATE_INSERT' => date('Y-m-d H:i:s'),
                    'CREATED_IP' => $this->input->ip_address()
                ];

                $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                $this->mglobal->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', ['IS_ACTIVE' => '0'], ['ID_LHKPN' => $ID_LHKPN]);

                $this->db->trans_commit();

                $this->pesan_pdf_verif_cepat($ID_LHKPN, $TGL_VER, $msg_verif_ins);
                
                
            } else {
                $this->db->trans_rollback();
                $this->session->set_userdata('msg_verifikasi_cepat','Verif Cepat gagal !');

                redirect('index.php?dpg=c8763ad09e4afa1445e98bee98524fb3#index.php/ever/verification/index/lhkpn?sessVerif');

            }
         }
    }

    public function getDataToVerif($id){

        unset($_SESSION['msg_verifikasi_cepat']); //hapus session alert sukses verif cepat

        //data pribadi
        $cek = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, 'KD_ISO3_NEGARA', "ID_LHKPN = '$id'")[0];
        $joinDATA_PRIBADI = [];
        $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
        if (@$cek->KD_ISO3_NEGARA == '') {
            $joinDATA_PRIBADI = [
                ['table' => 'M_AREA', 'on' => 'M_AREA.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI AND M_AREA.IDKOT = CAST(T_LHKPN_DATA_PRIBADI.KABKOT AS UNSIGNED) AND M_AREA.IDKEC = T_LHKPN_DATA_PRIBADI.KECAMATAN AND M_AREA.IDKEL = ""'],
                ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
            ];
            $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
        } else {
            $joinDATA_PRIBADI = [
                ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.KODE_ISO3 = T_LHKPN_DATA_PRIBADI.KD_ISO3_NEGARA'],
                ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
            ];
            $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*,M_NEGARA.NAMA_NEGARA as KD_ISO3_NEGARA, T_LHKPN.*';
        }

       $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', $joinDATA_PRIBADI, NULL, $selectDATA_PRIBADI, "T_LHKPN_DATA_PRIBADI.ID_LHKPN = '$id'")[0];

       $this->load->model('mlhkpnkeluarga');
       $this->data['lhkpn_ver'] = $this->mlhkpnkeluarga->get_lhkpn_version($id);

       // data keluarga
       $all_keluarga =  $this->Verification_model->get_keluarga_by_id_lhkpn($id);
       $data_keluarga = array();
       foreach($all_keluarga as $item) {
           if($item->NIK){
               $cek_pn = $this->db->query("SELECT COUNT(id_pn) AS numrows, id_pn FROM t_pn WHERE nik = '".$item->NIK."'");
               if($cek_pn->result()[0]->numrows > 0){$pn = 1;}
               else{$pn = 0;}
           }
           else{$pn = 0;}
           $new_array = (object) [
               "ID_KELUARGA"=> $item->ID_KELUARGA,
               "NAMA"=> $item->NAMA,
               "HUBUNGAN"=> $item->HUBUNGAN,
               "FLAG_SK"=> $item->FLAG_SK,
               "PN"=> $pn,
               "umur_lapor"=> $item->umur_lapor
           ];
           array_push( $data_keluarga,$new_array);
       }
        $this->data['data_keluarga'] = $data_keluarga;

       //data jabatan
        $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
        $joinJabatan = [
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
            ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
            ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
        ];
        $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id' ", ['IS_PRIMARY', 'DESC']);

        //data harta
        $this->load->model('mlhkpn');
        $summary_harta = $this->get_summary_harta($id);
        $this->data = array_merge($summary_harta, $this->data);

        $this->config->load('harta');
        $this->data["jenis_penerimaan_kas_pn"] = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $this->data["jenis_pengeluaran_kas_pn"] = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $this->data['PENERIMAAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$id'");
        $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$id'");
        $this->data['getPemka'] = current($this->data['PENERIMAAN_KASS']);
        $this->data['getPenka'] = current($this->data['PENGELUARAN_KASS']);

        //data verifikasi
        $this->data['VERIFICATIONS'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$id'");

        echo json_encode($this->data);
        exit;

    }


    public function lhkpn($type = '', $id = '') {

        $currentUrl = $_SERVER['REQUEST_URI'];
        $url_components = parse_url($currentUrl);
        $haveSessionVerif = isset($url_components["query"])?$url_components["query"]:null;

        if(!$haveSessionVerif){
            unset($_SESSION['msg_verifikasi_cepat']);
        }

        $this->load->model('mlhkpnkeluarga');
        $this->data['tbl'] = 'T_LHKPN';
        $this->data['pk'] = 'ID_LHKPN';
        $this->data['role'] = urlencode($this->config->item('LHKPNOFFLINE_PAGE_ROLE')['verification']);
        $my_where = [];
        $my_where_find = [];

        $usr = $this->session->userdata('USR');
        $roles = $this->session->userdata('ID_ROLE');

        $this->data['batas_akhir_respon'] = '-';

        if ($type == 'list') {

            $this->CARI = @$this->input->post('CARI');
            $this->load->model('Mlhkpn');
            list($this->items, $this->total_rows) = $this->Mlhkpn->list_verifikasi_lhkpn($usr, $this->CARI, $this->limit, $this->offset,$roles);
            $this->end = count($this->items);
            $this->data['title'] = 'LHKPN Masuk';
            
        }

        /* IF TYPE == REFF */
        else if ($type == 'reff') {
            if ($this->display == 'verifikasi') {

                $this->data['icon'] = 'fa-book';
                $this->data['title'] = 'LHKPN';
                $upperli = $this->input->get('upperli');
                $bottomli = $this->input->get('bottomli');
                $this->data['upperli'] = $upperli ? $upperli : 'li1';
                $this->data['bottomli'] = $bottomli ? $bottomli : FALSE;
                unset($upperli, $bootomli);
                $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
                $breadcrumbitem[] = [ucwords(strtolower(__CLASS__)) => $this->segmentTo[2]];
                $breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
                $breadcrumbdata = [];
                foreach ($breadcrumbitem as $list) {
                    $breadcrumbdata = array_merge($breadcrumbdata, $list);
                }
                $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);

                $joinMATA_UANG = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
                ];

                $joinHUTANG = [
                    ['table' => 'm_jenis_hutang', 'on' => 'KODE_JENIS  = ID_JENIS_HUTANG']
                ];

                $this->load->model('mlhkpn', '', TRUE);
                $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID_LHKPN = '$id' and IS_ACTIVE =1");
                $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'], ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'T_LHKPN_DATA_PRIBADI.ID_LHKPN']], NULL, '*,T_PN.NIK as NIK_PN', "T_LHKPN.ID_LHKPN = '$id'")[0];
                $this->data['tmpData'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN =' => $id])[0]->HASIL_VERIFIKASI);
                $user = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', null, ['IS_ACTIVE' => '1', 'ID_LHKPN =' => $id], 'USERNAME, UPDATED_BY')[0];
                $this->data['pic1'] = @$this->mglobal->get_data_all('T_USER', null, ['IS_ACTIVE' => '1', 'USERNAME' => $user->USERNAME], 'NAMA')[0];
                $this->data['pic2'] = @$this->mglobal->get_data_all('T_USER', null, ['IS_ACTIVE' => '1', 'USERNAME' => $user->UPDATED_BY], 'NAMA')[0];

                $cek = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, 'KD_ISO3_NEGARA', "ID_LHKPN = '$id'")[0];
                $joinDATA_PRIBADI = [];
                $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
                if (@$cek->KD_ISO3_NEGARA == '') {
                    $joinDATA_PRIBADI = [
                        ['table' => 'M_AREA', 'on' => 'M_AREA.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI AND M_AREA.IDKOT = CAST(T_LHKPN_DATA_PRIBADI.KABKOT AS UNSIGNED) AND M_AREA.IDKEC = T_LHKPN_DATA_PRIBADI.KECAMATAN AND M_AREA.IDKEL = ""'],
                        ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
                    ];
                    $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
                } else {
                    $joinDATA_PRIBADI = [
                        ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.KODE_ISO3 = T_LHKPN_DATA_PRIBADI.KD_ISO3_NEGARA'],
                        ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
                    ];
                    $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*,M_NEGARA.NAMA_NEGARA as KD_ISO3_NEGARA, T_LHKPN.*';
                }

                $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', $joinDATA_PRIBADI, NULL, $selectDATA_PRIBADI, "T_LHKPN_DATA_PRIBADI.ID_LHKPN = '$id'")[0];
                $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
                $joinJabatan = [
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
                    ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
                ];
                $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id' ", ['IS_PRIMARY', 'DESC']);
                $this->data['tmpAPI'] = $this->mglobal->get_data_all('T_LHKPN_AI',NULL,"ID_LHKPN = '$id' ");
                $this->data['JABATANS_P'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id' AND IS_PRIMARY = '1' ", ['IS_PRIMARY', 'DESC']);
                $this->data['ID_LHKPN'] = $id;
                $sql_jabatan_lhkpn = "SELECT NAMA_JABATAN FROM M_JABATAN JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN WHERE T_LHKPN_JABATAN.ID_LHKPN = ( SELECT ID_LHKPN FROM T_LHKPN WHERE T_LHKPN.ID_LHKPN = '$id' )";
                $this->data['JABATAN_LHKPN'] = $this->db->query($sql_jabatan_lhkpn)->result();
                $id_pn = $this->data['LHKPN']->ID_PN;
                //////sampai sini//////////////////
                $this->data['log_file_bukti_sk'] = $this->mglobal->get_data_all('T_LHKPN', NULL, NULL, '*', "ID_PN = '$id_pn' and IS_ACTIVE =1");
                //////////////////////////////
                $joinJabatan = [
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
                    ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                ];
                $this->data['JABATANSPN'] = $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "T_PN_JABATAN.ID_PN = '$id_pn' AND IS_CURRENT = 1");
                $this->data['ID_PN'] = $id_pn;

                // SELECT hasil verifikasi
                $this->data['hasilVerifikasiitem'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1'], 'HASIL_VERIFIKASI', "ID_LHKPN = '$id'")[0]->HASIL_VERIFIKASI);
                $this->data['hasilAI'] = $this->Verification_model->get_check_ai($id);
                $this->data['STATUS_VERIFIKASI'] = @($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1'], 'STATUS_VERIFIKASI', "ID_LHKPN = '$id'")[0]->STATUS_VERIFIKASI);

                //jenis bukti
                $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, ['ID_JENIS_BUKTI', 'DESC']);
                $list_bukti = [];
                foreach ($jenis_bukti as $key) {
                    $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
                }

                $this->data['list_bukti'] = $list_bukti;
                $entry_via_new = '';
                if ($this->data['LHKPN']->entry_via == '0'){
                    $entry_via_new = null;
                }
                else{
                    $entry_via_new = 1;
                }
                $this->data['list_bukti_alat_transportasi'] = $this->get_list_bukti('2', $entry_via_new, ['ID_JENIS_BUKTI', 'ASC']);

                $agenda = date('Y', strtotime($this->data['LHKPN']->tgl_lapor)) . '/' . ($this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['LHKPN']->NIK . '/' . $this->data['LHKPN']->ID_LHKPN;
                $nama = (@$this->data['DATA_PRIBADI']->NAMA_LENGKAP != '' ? $this->data['DATA_PRIBADI']->NAMA_LENGKAP : $this->data['LHKPN']->NAMA);
                $nik = (@$this->data['DATA_PRIBADI']->NIK != '' ? $this->data['DATA_PRIBADI']->NIK : $this->data['LHKPN']->NIK);

                /////////////////////////////////// DATA FROM ELO ///////////////////////////////////
                $this->load->helper('elo_helper');
                $key = $this->config->item('KPKkey');
                $LoginAs = '';
                $ELOuser = $this->config->item('ELOUser');
                $ELOpass = $this->config->item('ELOPass');
                $IXURL = $this->config->item('IXURL');
                $ELOws = $this->config->item('ELOws');
                $result_elo = FindELODoc('Basic Entry', $nik, "", "", "","", $LoginAs,$ELOuser,$ELOpass,$IXURL,$ELOws);
                $put_elo = [];
                foreach($result_elo as $r){
                    $r['URL'] = GetDocURL($r['ID'],$LoginAs,$ELOuser,$ELOpass,$IXURL,$ELOws);
                    array_push($put_elo,$r);
                }
                $this->data['data_sk_elo'] = json_encode($put_elo);
                /////////////////////////////////// DATA FROM ELO ///////////////////////////////////

                $jen_lap = $this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus';
                $tgl_lap = $this->data['LHKPN']->JENIS_LAPORAN == '4' ? substr($this->data['LHKPN']->tgl_lapor, 0,4) : tgl_format($this->data['LHKPN']->tgl_lapor);
                $this->data['tampil'] = 'LHKPN : <b>' . $nama . '</b> (' . $nik . ') - ' . $agenda;
                $this->data['tampil3'] = 'Jenis Laporan : ' . $jen_lap . ', Tanggal/Tahun Laporan : ' . $tgl_lap ;
                $this->data['tampil2'] = $nama . ' (' . $nik . ')';

                $diff = (count($this->data['JABATANSPN']) == count($this->data['JABATANSPN']) ? true : false);
                if ($diff) {
                    $diff = pembanding($this->data['JABATANSPN'], $this->data['JABATANS']);
                }

                $tmp = [
                    'status' => $diff
                ];

                if ($diff == false) {
                    $idLembaga = implode("','", array_unique(array_merge(array_column(json_decode(json_encode($this->data['JABATANSPN']), true), 'LEMBAGA'), array_column(json_decode(json_encode($this->data['JABATANS']), true), 'LEMBAGA'))));
                    $lembaga = array_unique(array_merge(array_column(json_decode(json_encode($this->data['JABATANSPN']), true), 'INST_NAMA'), array_column(json_decode(json_encode($this->data['JABATANS']), true), 'INST_NAMA')));
                    $tmp['lembaga'] = $lembaga;
                    $email = $this->mglobal->get_data_all(
                            'T_USER', [
                        ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE']
                            ], NULL, 'ID_USER, EMAIL', "INST_SATKERKD IN ('$idLembaga') AND (IS_INSTANSI = '1' OR IS_USER_INSTANSI = '1')");
                    $tmp['email'] = array_column(json_decode(json_encode($email), true), 'EMAIL');
                    $tmp['id'] = array_column(json_decode(json_encode($email), true), 'ID_USER');
                }

                $this->data['diffJabatan'] = $tmp;
                $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                $this->data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
                $this->data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);

//                jenis bukti
                $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, ['ID_JENIS_BUKTI', 'DESC']);
                $list_bukti = [];
                foreach ($jenis_bukti as $key) {
                    $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
                }
                //jenis Harta
                $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA', NULL, ['ID_JENIS_HARTA', 'DESC']);
                $list_harta = [];
                foreach ($jenis_HARTA as $key) {
                    $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
                }
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
                $this->data['list_harta'] = $list_harta;
                $this->data['list_bukti'] = $list_bukti;
                $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian2("ID_LHKPN = '$id'");
                $this->data['lhkpn_ver'] = $this->mlhkpnkeluarga->get_lhkpn_version($id);
                $this->data['KELUARGAS'] = $this->Verification_model->get_keluarga_by_id_lhkpn($id);// $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*', "ID_LHKPN = '$id'");

                $all_keluarga =  $this->Verification_model->get_keluarga_by_id_lhkpn($id);
                $data_keluarga = array();
                if(is_array($all_keluarga) && count($all_keluarga) > 0) {
                    foreach($all_keluarga as $item) {
                        if($item->NIK){
                            $cek_pn = $this->db->query("SELECT COUNT(id_pn) AS numrows, id_pn FROM t_pn WHERE nik = '".$item->NIK."'");
                            if($cek_pn->result()[0]->numrows > 0){$pn = 1;}
                            else{$pn = 0;}
                        }
                        else{$pn = 0;}
                        $new_array = (object) [
                            "ID_KELUARGA"=> $item->ID_KELUARGA,
                            "NAMA"=> $item->NAMA,
                            "HUBUNGAN"=> $item->HUBUNGAN,
                            "FLAG_SK"=> $item->FLAG_SK,
                            "PN"=> $pn,
                            "umur_lapor"=> $item->umur_lapor
                        ];
                        array_push( $data_keluarga,$new_array);
                    }
                }
                $this->data['data_keluarga'] = $data_keluarga;
                $joinHARTA_TIDAK_BERGERAK = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                    //['table' => 'M_AREA as area', 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV', 'join' => 'left']
                    ['table' => 'M_AREA_KAB as kabkot', 'on' => 'kabkot.NAME_KAB = data.KAB_KOT', 'join' => 'left'],
                    ['table' => 'M_AREA_PROV as provinsi', 'on' => 'provinsi.NAME = data.PROV', 'join' => 'left']
                ];
                $KABKOT = "(SELECT NAME_KAB FROM M_AREA_KAB as area WHERE data.KAB_KOT = area.NAME_KAB AND area.IS_ACTIVE = 1) as KAB_KOT";
                $PROV = "(SELECT NAME FROM M_AREA_PROV as area WHERE data.PROV = area.NAME) as PROV";
                $selectHARTA_TIDAK_BERGERAK = 'DISTINCT IS_CHECKED, data.NEGARA AS ID_NEGARA, data.PASANGAN_ANAK, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL, KAB_KOT, PROV, data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
                $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], "ID_LHKPN = '$id' and  data.IS_ACTIVE =1 order by ID desc");
                $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_BERGERAK.IS_ACTIVE = 1 order by ID desc");
                $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_BERGERAK_LAIN.IS_ACTIVE = 1 ");
                $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'  and T_LHKPN_HARTA_SURAT_BERHARGA.IS_ACTIVE = 1 order by ID desc ");
                $this->data['SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 't_lhkpn_data_pribadi.ID_LHKPN  = T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN']
                        ], NULL, '*', "T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN = '$id'");
//                display($this->db->last_query());
                $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_KAS.IS_ACTIVE = 1 order by ID desc ");
                $this->data['HARTA_SETARA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 't_lhkpn_data_pribadi.ID_LHKPN  = T_LHKPN_HARTA_KAS.ID_LHKPN']
                        ], NULL, '*', "T_LHKPN_HARTA_KAS.ID_LHKPN = '$id'");
                $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'  and T_LHKPN_HARTA_LAINNYA.IS_ACTIVE = 1 ");
                $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', $joinHUTANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HUTANG.IS_ACTIVE = 1 order by ID_HUTANG desc");
                $this->data['PENERIMAAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$id'");

                $this->data['getPemka'] = current($this->data['PENERIMAAN_KASS']);
                $this->data['getPenka'] = current($this->data['PENGELUARAN_KASS']);

                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'jabatan');
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'keluarga'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartatidakbergerak'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartabergerak'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartabergerakperabot'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'suratberharga'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'kas'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartalainnya'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hutang'));
                $this->data['hasilVerifikasi'] = array_merge($this->data['hasilVerifikasi'], $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'penerimaanfasilitas'));
                $this->data['lampiran_hibah'] = $this->_lampiran_hibah($this->data['LHKPN']->ID_LHKPN);
                $this->data['lampiran_pelepasan'] = $this->_lampiran_pelepasan($this->data['LHKPN']->ID_LHKPN);
                $this->config->load('harta');
                $this->data["jenis_penerimaan_kas_pn"] = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
        $this->data["golongan_penerimaan_kas_pn"] = $this->config->item('golongan_penerimaan_kas_pn', 'harta');
        $this->data["jenis_pengeluaran_kas_pn"] = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
        $this->data["golongan_pengeluaran_kas_pn"] = $this->config->item('golongan_pengeluaran_kas_pn', 'harta');
        $this->data["lhkpn_offline_melalui"] = array_flip($this->config->item('lhkpn_offline_melalui', 'harta'));



            ///////////////////////////////////evan summary///////////////////////////////////
            $summary_harta = $this->get_summary_harta($id);
            $this->data = array_merge($summary_harta, $this->data);


            }
            if ($this->display == 'history') {
                $this->data['icon'] = 'fa-book';
                $this->data['title'] = 'LHKPN PN 123';
                $this->data['VERIFICATIONS'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$id'");
                // echo $this->db->last_query();
            }
            if ($this->display == 'cetaksurat') {
                $this->data['id'] = $id;
            }
            if ($this->display == 'suratpn') {
                $this->data['id'] = $id;
                $this->data['VERIFICATIONS'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$id'", ["ID", "DESC"]);
            }
            if ($this->display == 'suratinstansi') {
                $this->data['id'] = $id;
                $this->data['VERIFICATIONS'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$id'", ["ID", "DESC"]);
            }
        }

        /* end if reff */

        else if ($type == 'edit') {
            $this->makses->check_is_write();
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, NULL, '*', "ID_LHKPN = '$id'")[0] : '';
        }

        /* IF TYPE == SAVE */
        else if ($type == 'save') {

            $this->makses->check_is_write();

            /* IF ACT == DOVERIFY */
            if ($this->act == 'doverify') {
                $simpan = @$_POST['simpan'];
                $ID_LHKPN = $this->input->post('ID_LHKPN', true);
                $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
                $joinJabatan = [
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
                    ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
                ];
                $jabatan = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, ['ID_LHKPN' => $ID_LHKPN], $selectJabatan);
                $lhkpn_dt = $this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_LHKPN' => $ID_LHKPN]);
                $countJab = count($jabatan);
                $iscekJab = $this->mglobal->get_data_all('T_LHKPN_JABATAN', NULL, ['ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '0']);
                $countJIC = count($iscekJab);

                /* IF SIMPAN == DRAFT */
                if ($countJab != $countJIC || $simpan == 'draft') {
                    $this->db->trans_begin();
                    $ID_LHKPN = $this->input->post('ID_LHKPN', TRUE);
                    $TGL_VER = $this->input->post('tgl_ver_', TRUE);
                    $this->load->model('mglobal');
                    $final = @$this->input->post('final');

                    // prevent final
                    if ($final != NULL){
                        if ($final == '1')
                            $MSG_VER = $this->input->post('MSG_VERIFIKASI_TRUE', TRUE);
                        elseif ($final == '2')
                            // $MSG_VER = $this->input->post('MSG_VERIFIKASI', TRUE);
                            $MSG_VER = 
                            '<table>
                                <tr>
                                    <td>
                                    Yth. Sdr. ' . $lhkpn_dt[0]->USERNAME_ENTRI . '<br/>
                                    ' . $jabatan[0]->INST_NAMA . '<br/>
                                    di Tempat<br/><br/>
                                    </td>
                                </tr>
                            </table>
                            <table>
                                <tr>
                                    <td>
                                    Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal ' . $TGL_VER . '.<br><br>
                                    Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK.<br><br>
                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br><br>
                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>
                                    </td>
                                </tr>
                            </table>
                            <table>
                                <tr>
                                    <td>
                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                    --------------------------------------------------------------<br/>
                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                    </td>
                                </tr>
                            </table>';
                        else if ($final == '3')
                            $MSG_VER = $this->input->post('MSG_VERIFIKASI_TIDAK_LENGKAP', TRUE);
                        else
                            $MSG_VER = $this->input->post('MSG_VERIFIKASI_DITOLAK', TRUE);
                    }

                    $CountData = $this->mglobal->count_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN]);

                    if ($simpan == 'draft') {
                        $data = array(
                            'TANGGAL' => date('Y-m-d'),
                            'HASIL_VERIFIKASI' => json_encode($this->input->post('VER', TRUE)),
                            'MSG_VERIFIKASI_INSTANSI' => str_replace('width:', 'width="', str_replace('[removed]', '', $this->input->post('MSG_VERIFIKASI_INSTANSI', TRUE))),
                            'STATUS_VERIFIKASI' => ($simpan == 'draft') ? '0' : '1',
                            'IS_ACTIVE' => '1',
                         );
                    }else{
                        $data = array(
                            'TANGGAL' => date('Y-m-d'),
                            'HASIL_VERIFIKASI' => json_encode($this->input->post('VER', TRUE)),
                            'MSG_VERIFIKASI' => str_replace('[removed]', '', $MSG_VER),
                            'MSG_VERIFIKASI_INSTANSI' => str_replace('width:', 'width="', str_replace('[removed]', '', $this->input->post('MSG_VERIFIKASI_INSTANSI', TRUE))),
                            'STATUS_VERIFIKASI' => ($simpan == 'draft') ? '0' : '1',
                            'IS_ACTIVE' => '1',
                         );
                    }

                    if ($CountData == 0) {
                        $data['ID_LHKPN'] = $this->input->post('ID_LHKPN', TRUE);
                        $data['CREATED_TIME'] = time();
                        $data['CREATED_BY'] = $this->session->userdata('USR');
                        $data['CREATED_IP'] = $_SERVER["REMOTE_ADDR"];
                        $this->db->insert('T_VERIFICATION', $data);
                        /*
                         * add by eko
                         * insert status proses verifikasi ke history status lhkpn
                         */
                        $history = [
                            'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                            'ID_STATUS' => 6,
                            'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                            'USERNAME_PENERIMA' => '',
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                    }
                     else {
                        $this->db->update('T_VERIFICATION', $data, ['ID_LHKPN' => $ID_LHKPN]);
                    }

                    $this->mglobal->update('T_VERIFICATION_ITEM', ['IS_EDITABLE' => '0'], ['ID_LHKPN' => $ID_LHKPN, 'HASIL' => '1']);

                    /* START IF !DRAFT */
                    if ($simpan !== 'draft') {
                        /*
                         * add by eko
                         * insert status selesai verifikasi ke history status lhkpn
                         */
                        if ($final == '1'){
                            $status_verif = 17;
                        } else if ($final == '3'){
                            $status_verif = 18;
                        }else{
                            $status_verif = 9;
                        }

                        $history = [
                            'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                            'ID_STATUS' => $status_verif,
                            'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                            'USERNAME_PENERIMA' => '',
                            'DATE_INSERT' => date('Y-m-d H:i:s'),
                            'CREATED_IP' => $this->input->ip_address()
                        ];

                        $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);

                        if ($final != NULL){
                            if ($final == '1') {
                                $status = '3';
                                $id_status_history = '11';
                                $penerima = 'koordinator_announcement';
                            } else if ($final == '3') {
                                $status = '5';
                                $id_status_history = '11';
                                $penerima = 'koordinator_announcement';
                            } else if ($final == '2') {
                                if (($lhkpn_dt[0]->STATUS == '1' || $lhkpn_dt[0]->STATUS == '2') && ($lhkpn_dt[0]->ALASAN == '1' || $lhkpn_dt[0]->ALASAN == '2')) {
                                    $status = '7';
                                    $id_status_history = '10';
                                    $penerima = '';
                                } else {
                                    $status = '2';
                                    $id_status_history = '7';
                                    $penerima = '';
                                }
                            }
                        }


                        $res = [];
                        $res['STATUS'] = $status;
                        if ($status == '2') {
                            $res['ALASAN'] = $this->input->post('alasan');
                        } else if ($status == '7') {
                            // $res['ALASAN'] = NULL;
                            $res['DIKEMBALIKAN'] = $this->input->post('DIKEMBALIKAN') + 1;
                        }

                        $result = $this->db->update('T_LHKPN', $res, ['ID_LHKPN' => $this->input->post('ID_LHKPN')]);
                        if ($result) {
                            // History
                            $history = [
                                'ID_LHKPN' => $this->input->post('ID_LHKPN'),
                                'ID_STATUS' => $id_status_history,
                                'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                                'USERNAME_PENERIMA' => $penerima,
                                'DATE_INSERT' => date('Y-m-d H:i:s'),
                                'CREATED_IP' => $this->input->ip_address()
                            ];

                            $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                            $this->mglobal->update('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', ['IS_ACTIVE' => '0'], ['ID_LHKPN' => $this->input->post('ID_LHKPN')]);

                            $this->db->trans_commit();
                        } else {
                            $this->db->trans_rollback();
                        }
                    }
                    /* END IF !DRAFT */

                    echo '0';
                }
                /* END IF SIMPAN == DRAFT */

                else {
                    echo '1';
                }
                intval($this->db->trans_status());
            }
            /* END IF ACT == DOVERIFY */
            else if ($this->act == 'doinsert') {
                // 	$data = array(
                // 		'ID_AGAMA'         => $this->input->post('ID_AGAMA', TRUE),
                // 		'AGAMA'         => $this->input->post('AGAMA', TRUE),
                // 		'IS_ACTIVE'         => 1,
                // 		'CREATED_TIME'     => time(),
                // 		'CREATED_BY'     => $this->session->userdata('USR'),
                // 		'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 	);
                // 	$this->db->insert($this->data['tbl'], $data);
            }
            else if ($this->act == 'doupdate') {
                // 	$data = array(
                // 		'ID_AGAMA'         => $this->input->post('ID_AGAMA', TRUE),
                // 		'AGAMA'         => $this->input->post('AGAMA', TRUE),
                // 		'UPDATED_TIME'     => time(),
                // 		'UPDATED_BY'     => $this->session->userdata('USR'),
                // 		'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 	);
                // 	$data[$this->data['pk']]    = $this->input->post($this->data['pk'], TRUE);
                // 	$this->db->where($this->data['pk'], $data[$this->data['pk']]);
                // 	$this->db->update($this->data['tbl'], $data);
            }
            else if ($this->act == 'dodelete') {
                // 	$data = array(
                // 		'IS_ACTIVE'        => -1,
                // 		'UPDATED_TIME'     => time(),
                // 		'UPDATED_BY'     => $this->session->userdata('USR'),
                // 		'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 	);
                // 	$data[$this->data['pk']]    = $this->input->post($this->data['pk'], TRUE);
                // 	$this->db->where($this->data['pk'], $data[$this->data['pk']]);
                // 	$this->db->update($this->data['tbl'], $data);
            }
        }
        /* END IF TYPE == SAVE */
    }

    private function get_summary_harta($id_lhkpn = FALSE) {
        $summary_harta = [];

        if ($id_lhkpn) {
            $summary_harta['hartirak'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_harta_tidak_bergerak', 'NILAI_PELAPORAN', 'sum_hartirak', 'ID_LHKPN');
            $summary_harta['harger'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_harta_bergerak', 'NILAI_PELAPORAN', 'sum_harger', 'ID_LHKPN');
            $summary_harta['harger2'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_harta_bergerak_lain', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harger2', 'ID_LHKPN');
            $summary_harta['suberga'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_harta_surat_berharga', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_suberga', 'ID_LHKPN');
            $summary_harta['kaseka'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_harta_kas', "NILAI_EQUIVALEN", 'sum_kaseka', 'ID_LHKPN');
            $summary_harta['harlin'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_harta_lainnya', "REPLACE(NILAI_PELAPORAN,'.','')", 'sum_harlin', 'ID_LHKPN');
            $summary_harta['_hutang'] = $this->mlhkpn->summaryHarta($id_lhkpn, 't_lhkpn_hutang', 'SALDO_HUTANG', 'sum_hutang', 'ID_LHKPN');
        }
        return $summary_harta;
    }


    function pdf_detail($param, $entry_via)
    {
        $data['entry_via'] = $entry_via;
        $data['VERIFICATIONS'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$param'", ["ID", "DESC"]);
        $data['datapn'] = @$this->mglobal->get_detail_pn_lhkpn($data['VERIFICATIONS'][0]->ID_LHKPN, TRUE, TRUE);
        if ($entry_via == '1'){
            $data['datapn'] = @$this->mglobal->get_detail_pn_lhkpn_excel($data['VERIFICATIONS'][0]->ID_LHKPN, TRUE, TRUE);
        }
        $this->load->view('verification/verification_lhkpn_perbaikan', $data);
    }

    function pesan_pdf() {
        $this->db->trans_begin();

        $ID_LHKPN = $this->input->post('id_lhkpn');
        $TGL_VER = $this->input->post('tgl_ver');
        $entry_via = $this->input->post('entry_via');
        $MSG_VERIFIKASI_ALASAN = $this->input->post('MSG_VERIFIKASI_INSTANSI', TRUE);
//        $ID_LHKPN = 29;
        // Kirim pesan
        /* $datapn = @$this->mglobal->get_data_all('T_USER', [
          ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
          ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
          ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
          ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
          ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
          ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
          ], ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '1'], 'ID_USER, T_PN.NIK, T_USER.NAMA, T_PN.NO_HP, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_NAMA, T_LHKPN.STATUS, T_LHKPN.TGL_LAPOR, T_PN.EMAIL')[0]; */
        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
        $usernames = $datapn->USERNAME;
        $idRoles = $datapn->ID_ROLE;
        if ($entry_via == '1'){
            $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
        }
        $history = $this->Verification_model->get_history_verification($ID_LHKPN)->DATE_INSERT;
        $penugas = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', NULL, ['ID_LHKPN' => $ID_LHKPN], 'UPDATED_BY')[0];
        $petugas = @$this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $penugas->UPDATED_BY], 'NAMA, ID_ROLE')[0];
        $role = @$this->mglobal->get_data_all('t_user_role', NULL, ['ID_ROLE' => $petugas->ID_ROLE], 'ROLE, DESCRIPTION')[0];
        $verif = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN])[0]->HASIL_VERIFIKASI);

        $arr_condition_verif_isian = array(
            "DATAPRIBADI",
            "JABATAN",
            "KELUARGA",
            "HARTATIDAKBERGERAK",
            "HARTABERGERAK",
            "HARTABERGERAK2",
            "SURATBERHARGA",
            "KAS",
            "HARTALAINNYA",
            "HUTANG",
            "PENERIMAANKAS",
            "PENGELUARANKAS",
            "PELEPASANHARTA",
            "PENERIMAANHIBAH",
            "PENERIMAANFASILITAS"
        );

        $verif_isian_ok = FALSE;
        foreach ($arr_condition_verif_isian as $val_property) {
            if ($verif->VAL->{$val_property} == "-1") {
                $verif_isian_ok = TRUE;
            }
        }

        if ($datapn->STATUS == 2) {
            $isi_pesan = $MSG_VERIFIKASI_ALASAN;
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara belum lengkap, hubungi call center 198", "idModem":6}';
//            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
            //CallURLPage('http://ip_node:3333/?SEND={"idOutbox":20,"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara belum lengkap, hubungi call center 198", "idModem":1, "jmlPesan":1}&a=2');
        } else if ($datapn->STATUS == 7) {
            $isi_pesan = 'Yth. Sdr. ' . $datapn->NAMA . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa pelaporan LHKPN atas nama Saudara setelah dilakukan verifikasi administratif dinyatakan ditolak dikarenakan tidak memenuhi kriteria yang telah ditetapkan dalam pelaporan LHKPN.<br><br>Sehubungan dengan hal tersebut silakan mengisi dan menyampaikan LHKPN sesuai petunjuk pengisian kepada Komisi Pemberantasan Korupsi dalam waktu tidak melampaui tanggal ' . $TGL_VER . '.<br><br>Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198';
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara ditolak, hubungi call center 198 untuk keterangan lebih lanjut", "idModem":6}';
//            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
        } else if ($datapn->STATUS == 5) {
            $isi_pesan = '<center><b>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</b><br>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12920<br><br><b>TANDA TERIMA PENYERAHAN FORMULIR LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</b></center><br><br><table style="width: 100%;"><tr><td width="105px">Atas Nama</td><td width="10px">:</td><td>' . $datapn->NAMA . '</td></tr><tr><td>Jabatan</td><td>:</td><td>' . $datapn->NAMA_JABATAN . '</td></tr><tr><td>Bidang</td><td>:</td><td>' . $datapn->BDG_NAMA . '</td></tr><tr><td>Lembaga</td><td>:</td><td>' . $datapn->INST_NAMA . '</td></tr><tr><td>Tahun Pelaporan</td><td>:</td><td>' . date('Y', strtotime($datapn->tgl_lapor)) . '</td></tr></table><br><br><div style="text-align: right;">Jakarta, ' . date('d F Y') . '</div>';
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara Terverifikasi Tidak Lengkap dan segera diumumkan", "idModem":6}';
//            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
        } else {
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara telah Lengkap dan segera diumumkan", "idModem":6}';
            //CallURLPage('http://ip_node:3333/?SEND={"idOutbox":20,"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara telah Lengkap dan segera diumumkan", "idModem":1, "jmlPesan":1}&a=2');
//            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
            $isi_pesan = '<center><b>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</b><br>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12920<br><br><b>TANDA TERIMA PENYERAHAN FORMULIR LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</b></center><br><br><table style="width: 100%;"><tr><td width="105px">Atas Nama</td><td width="10px">:</td><td>' . $datapn->NAMA . '</td></tr><tr><td>Jabatan</td><td>:</td><td>' . $datapn->NAMA_JABATAN . '</td></tr><tr><td>Bidang</td><td>:</td><td>' . $datapn->BDG_NAMA . '</td></tr><tr><td>Lembaga</td><td>:</td><td>' . $datapn->INST_NAMA . '</td></tr><tr><td>Tahun Pelaporan</td><td>:</td><td>' . date('Y', strtotime($datapn->tgl_lapor)) . '</td></tr></table><br><br><div style="text-align: right;">Jakarta, ' . date('d F Y') . '</div>';
        }

        if ($datapn->STATUS == 2) {
            $subjek = 'Daftar Kekurangan LHKPN';
        }else{
            $subjek = 'Tanda Terima ( Verifikasi )';
        }

        $pengirim = array(
            'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
            'ID_PENERIMA' => $datapn->ID_USER,
            'SUBJEK' => $subjek,
            'PESAN' => $isi_pesan,
            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
            'IS_ACTIVE' => '1',
            'ID_LHKPN' => $ID_LHKPN
        );
        $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);

        if ($kirim) {

            $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".pdf";
            if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y') . ".pdf";
            }

            $penerima = array(
                'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $datapn->ID_USER,
                'SUBJEK' => $subjek,
                'PESAN' => $isi_pesan,
                'FILE' => "../../../uploads/pdf/" . $datapn->NIK . '/' . $output_filename,
                'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1',
                'ID_LHKPN' => $ID_LHKPN
            );
            $this->mglobal->insert('T_PESAN_MASUK', $penerima);

            // create file
            $time = time();
            $dataPDF = array(
                'NAMA' => $datapn->NAMA,
                'JABATAN' => $datapn->NAMA_JABATAN,
                'BDG_NAMA' => $datapn->BDG_NAMA,
                'LEMBAGA' => $datapn->INST_NAMA,
                'STATUS' => $datapn->STATUS,
                'LAPOR' => date('Y', strtotime($datapn->TGL_LAPOR)),
                'PETUGAS' => $petugas->NAMA,
                'TUGAS_PETUGAS' => $role->ROLE
            );

//            $th = date('Y');

            $filename = 'uploads/pdf/' . $datapn->NIK . "/$output_filename";

            if (!file_exists($filename)) {
                $dir = './uploads/pdf/' . $datapn->NIK . '/';

                if (is_dir($dir) === false) {
                    mkdir($dir);
                }else{
                    chmod($dir, 0755);
                    chown($dir, "apache");
                    chgrp($dir, "apache");
                }
            }

            //bakal dicomment jika pengerjaan pdf merata//
            // $this->load->library('lwphpword/lwphpword', array(
            //     "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
            //     "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
            //     "base_root" => base_url(),
            // ));


            // $template_file = "../file/template/FormatTandaTerima.docx";
            // if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
            //     $template_file = "../file/template/LampiranKekurangan.docx";
            // }
            //tutup comment

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "LK-ELHKPN-" : "TT-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            // $this->load->library('ey_barcode');

            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
                            (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
                            (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
                            (object) ["tipe" => '1', "judul" => "Unit Kerja", "isi" => $datapn->UK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Sub Unit Kerja", "isi" => $datapn->SUK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor))],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                        ],
                        "encrypt_data" => $ID_LHKPN . "tt",
                        "id_lhkpn" => $ID_LHKPN,
                        "judul" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "Lampiran Kekurangan E-LHKPN" : "Tanda Terima E-LHKPN",
                        "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $ID_LHKPN . ".png");

            $get_nik = $datapn->NIK;
            $get_nama = $datapn->NAMA;
//             $get_lembaga = $datapn->INST_NAMA;
//             $get_tgl_lapor = $datapn->tgl_lapor;

            // $show_barcode = "'".$get_nik.chr(9).$get_nama;

            // $show_barcode = $PartNumber."\t".$UIN;

            // $bc_image_location = $this->ey_barcode->generate($show_barcode, "tes_bc2-" . $ID_LHKPN . ".jpg");

            $show_qr2 = "'".$get_nik.chr(9).$get_nama;
            $qr2_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr2_image_location = $this->lws_qr->create($show_qr2, $qr2_file);

            //bakal dicomment jika pengerjaan pdf merata//
            // 7 ditolak
            // 5 ngirim perbaikan

            if ($datapn->STATUS == "7") {
                // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? array("image1.png" => $bc_image_location) : array("image2.jpeg" => $qr_image_location));

                // $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

                // $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
                // $this->lwphpword->set_value("LKP", $datapn->STATUS == "3" ? "v" : " ");
                // $this->lwphpword->set_value("TLKP", $datapn->STATUS == "3" ? " " : "v");
                // if ($datapn->STATUS == "5") {
                //     $this->lwphpword->set_value("IS", !$verif_isian_ok ? " " : "v");
                //     $this->lwphpword->set_value("LA", $verif->VAL->SURATKUASAMENGUMUMKAN == "1" ? " " : "v");
                //     $this->lwphpword->set_value("DK", $verif->VAL->DOKUMENPENDUKUNG == "1" ? " " : "v");
                // } else {
                //     $this->lwphpword->set_value("IS", " ");
                //     $this->lwphpword->set_value("LA", " ");
                //     $this->lwphpword->set_value("DK", " ");
                // }
                // $this->lwphpword->set_value("NIK", $datapn->NIK);
                // $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
                // $this->lwphpword->set_value("UK_NAMA", $datapn->UK_NAMA);
                // $this->lwphpword->set_value("SUK_NAMA", $datapn->SUK_NAMA);
                // $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
                // $this->lwphpword->set_value("TGL_VER", $TGL_VER);
                // $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
                // $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)));
                // $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));

                // if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                //     $this->set_msg_kekurangan($verif, $this->lwphpword);
                // }

                // $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
            }else{

                $data = array(
                    "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                    "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                    "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                    "NIK" => $datapn->NIK,
                    "LEMBAGA" => $datapn->INST_NAMA,
                    "UNIT_KERJA" => $datapn->UK_NAMA,
                    "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                    "JABATAN" => $datapn->NAMA_JABATAN,
                    "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                    "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                    "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                    "qr_code" => $qr_image_location,
                    "TGL_VERIFIKASI" => $history
                );

                $this->load->library('pdfgenerator');

                if (($datapn->STATUS == "2" || $datapn->STATUS == "7") && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                    $data = array(
                        "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                        "NIK" => $datapn->NIK,
                        "LEMBAGA" => $datapn->INST_NAMA,
                        "JABATAN" => $datapn->NAMA_JABATAN,
                        // "BC_CODE" =>  $bc_image_location,
                        "QR_IMAGE_LOCATION" =>  $qr2_image_location,
                        "msg_verifikasi" => $verif,
                    );
                    $html = $this->load->view('export_pdf/perlu_perbaikan', $data, true);
                    $filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y');
                }else{
                    
                    $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
                    $thn_kirim = $exp_tgl_kirim[0];
                    
                    $data = array(
                        "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                        "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                        "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                        "NIK" => $datapn->NIK,
                        "LEMBAGA" => $datapn->INST_NAMA,
                        "UNIT_KERJA" => $datapn->UK_NAMA,
                        "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                        "JABATAN" => $datapn->NAMA_JABATAN,
                        "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                        "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                        "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                        "qr_code" => $qr_image_location,
                        "TGL_VERIFIKASI" => $history,
                        "TAHUN_KIRIM" => $thn_kirim
                    );
                    $html = $this->load->view('export_pdf/tanda_terima', $data, true);
                    $filename = "Tanda_Terima_LHKPN_" . date('d-F-Y');
                }

                $method = "store";
                $path_pdf = 'uploads/pdf/' . $datapn->NIK . '/';
                $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
                $output_filename = $filename . ".pdf";

                $temp_dir = APPPATH."../images/qrcode/";
                $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
                unlink($temp_dir.$qr_image);
                unlink($temp_dir.$qr2_file);
                // $temp_dir_br = APPPATH."../uploads/barcode/";
                // $br_image = "tes_bc2-" . $ID_LHKPN . ".jpg";
                // unlink($temp_dir_br.$br_image);
            }
            //tutup comment

            $message_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

            $message_tidak_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Tidak Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

            $message_perbaikan = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   di Tempat<br/><br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal ' . $TGL_VER . '.<br><br>
                                Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK.<br><br>
                                Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br><br>
                                Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>
                            </tr>
                        </table>
                        <table>
                         <tr>
                             <td>
                                Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                --------------------------------------------------------------<br/>
                                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
                                &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                             </td>
                        </tr>
                        </table>';

            $admin = $this->mglobal->get_data_all('T_USER', null, ['USERNAME = ' => 'admin_kpk'], 'ID_USER, NAMA,EMAIL')[0];

            $this->db->trans_commit();
             
            if ($datapn->STATUS == "7") {
                ng::mail_send($datapn->EMAIL, 'Dikembalikan ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/async/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Status+e-LHKPN+Saudara+Ditolak,+Silahkan+melaporkan+e-LHKPN+kembali+melalui+elhkpn.kpk.go.id+%0aInfo+:+elhkpn@kpk.go.id/02125578396&msisdn='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Bapak/Ibu%20Dikembalikan%20ke%20Draft,%20silahkan%20melaporkan%20e-LHKPN%20kembali%20melalui%20elhkpn.kpk.go.id%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles,$datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Dikembalikan ke Draft, silahkan melaporkan e-LHKPN kembali melalui elhkpn.kpk.go.id Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');            
            } else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                ng::mail_send($datapn->EMAIL, 'Daftar Kekurangan LHKPN', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=e-LHKPN+Saudara+Memerlukan+Perbaikan,+silahkan+cek+rincian+perbaikan+yang+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->NO_HP.'');
//                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=kpk2019&SMSText=e-LHKPN%20Saudara%20Memerlukan%20Perbaikan,%20silahkan%20cek%20rincian%20perbaikan%20yang%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=e-LHKPN%20Saudara%20Memerlukan%20Perbaikan,%20silahkan%20cek%20rincian%20perbaikan%20yang%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles, $datapn->NO_HP, 'e-LHKPN Bapak/Ibu Memerlukan Perbaikan, silahkan cek rincian perbaikan yang dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');
            } else if ($datapn->STATUS == "5") {
                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Status+e-LHKPN+Saudara+Terverifikasi+Tidak+Lengkap,+Tanda+Terima+telah+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->NO_HP.'');
//                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=kpk2019&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Tidak%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Tidak%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles,$datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Terverifikasi Tidak Lengkap, Tanda Terima telah dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');
            } else {
//                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', 'Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara unggah telah diverifikasi, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK.', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, $admin->EMAIL);
                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Status+e-LHKPN+Saudara+Terverifikasi+Lengkap,+Tanda+Terima+telah+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->NO_HP.'');
//                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=kpk2019&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
		CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles,$datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Terverifikasi Lengkap, Tanda Terima telah dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');
            }
            //ng::mail_send($datapn->EMAIL, $message, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $th . '.pdf', $admin->EMAIL);
//            ng::mail_send($datapn->EMAIL, $message, NULL, 'uploads/pdf/' . $time . '-Verification-fileTT.pdf', 'cahyana.yogi@gmail.com');
            unlink($path_pdf.$output_filename);
            

//            $this->db->trans_commit();

/*
            if ($datapn->STATUS == "7") {
                ng::mail_send($datapn->EMAIL, 'Dikembalikan ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198');
//		CallURLPage('http://sms.citrahost.com/citra-sms.api.php?action=send&outhkey=f9f26aac2547d8831638643c0f4471da&secret=30212ddf7cb857d67e08e131aa9670e0&pesan=Status%20e-LHKPN%20Bapak/Ibu%20Dikembalikan%20ke%20Draft,%20silahkan%20melaporkan%20e-LHKPN%20kembali%20melalui%20elhkpn.kpk.go.id%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$datapn->NO_HP.'');
		CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Bapak/Ibu%20Dikembalikan%20ke%20Draft,%20silahkan%20melaporkan%20e-LHKPN%20kembali%20melalui%20elhkpn.kpk.go.id%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&GSM='.$datapn->NO_HP.'');
            } else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                ng::mail_send($datapn->EMAIL, 'Daftar Kekurangan LHKPN', $message_perbaikan, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, NULL, NULL, NULL, NULL, TRUE);
//		CallURLPage('http://sms.citrahost.com/citra-sms.api.php?action=send&outhkey=f9f26aac2547d8831638643c0f4471da&secret=30212ddf7cb857d67e08e131aa9670e0&pesan=e-LHKPN%20Bapak/Ibu%20Memerlukan%20Perbaikan,%20silahkan%20cek%20rincian%20perbaikan%20yang%20dikirim%20ke%20email%20Saudara%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=e-LHKPN%20Saudara%20Memerlukan%20Perbaikan,%20silahkan%20cek%20rincian%20perbaikan%20yang%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
            } else if ($datapn->STATUS == "5") {
                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, NULL, NULL, NULL, NULL, TRUE);
//		CallURLPage('http://sms.citrahost.com/citra-sms.api.php?action=send&outhkey=f9f26aac2547d8831638643c0f4471da&secret=30212ddf7cb857d67e08e131aa9670e0&pesan=Status%20e-LHKPN%20Bapak/Ibu%20Terverifikasi%20Tidak%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Tidak%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
            } else {
                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, NULL, NULL, NULL, NULL, TRUE);
//		CallURLPage('http://sms.citrahost.com/citra-sms.api.php?action=send&outhkey=f9f26aac2547d8831638643c0f4471da&secret=30212ddf7cb857d67e08e131aa9670e0&pesan=Status%20e-LHKPN%20Bapak/Ibu%20Terverifikasi%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
            }
*/
            echo '1';

        } else {
            $this->db->trans_rollback();
            echo '0';
        }
        intval($this->db->trans_status());
    }

//
    function daftar_pemanfaatan($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $pemanfaatan = $this->mlhkpnharta->get_pemanfaatan($gol);
        foreach ($pemanfaatan as $key) {
            $data[$key->ID_PEMANFAATAN] = $key->PEMANFAATAN;
        }
        return $data;
    }

    private function _lampiran_pelepasan($id_lhkpn, $where = NULL) {
        //jenis bukti
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI');
        $list_bukti = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        //jenis Harta
        $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA');
        $list_harta = [];
        foreach ($jenis_HARTA as $key) {
            $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
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
            '4' => 'Sewa Jangaka Panjang Dibayar Dimuka',
            '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
        ];
        //select lampiran pelepasan
        $selectlampiranpelepasan = 'A.JENIS_PELEPASAN_HARTA, A.URAIAN_HARTA AS KETERANGAN, A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
        $selectpelepasanhartatidakbergerak = ', B.ATAS_NAMA as ATAS_NAMA, B.LUAS_TANAH as LUAS_TANAH, B.LUAS_BANGUNAN as LUAS_BANGUNAN, B.NOMOR_BUKTI as NOMOR_BUKTI, B.JENIS_BUKTI as JENIS_BUKTI ';
        $selectpelepasanhartabergerak = ', B.TAHUN_PEMBUATAN, B.MODEL, B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA as ATAS_NAMA, B.MEREK as MEREK, B.NOPOL_REGISTRASI as NOPOL_REGISTRASI, B.NOMOR_BUKTI as NOMOR_BUKTI';
        $selectpelepasanhartabergeraklain = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, ATAS_NAMA as ATAS_NAMA';
        $selectpelepasansuratberharga = ', B.KODE_JENIS as KODE_JENIS, B.NAMA_SURAT_BERHARGA as NAMA_SURAT,  B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, B.ATAS_NAMA as ATAS_NAMA';
        $selectpelepasankas = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA_REKENING as ATAS_NAMA, B.NAMA_BANK as NAMA_BANK, B.NOMOR_REKENING as NOMOR_REKENING';
        $selectpelepasanhartalainnya = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.ATAS_NAMA as ATAS_NAMA';

        // call data lampiran pelepasan
        $pelepasanhartatidakbergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_TIDAK_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartatidakbergerak, "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasanhartabergerak = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK as A', [['table' => 'T_LHKPN_HARTA_BERGERAK as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartabergerak, "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasanhartabergeraklain = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN as A', [['table' => 'T_LHKPN_HARTA_BERGERAK_LAIN as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartabergeraklain, "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasansuratberharga = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA as A', [['table' => 'T_LHKPN_HARTA_SURAT_BERHARGA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasansuratberharga, "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasankas = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS as A', [['table' => 'T_LHKPN_HARTA_KAS as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasankas, "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasanhartalainnya = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA as A', [['table' => 'T_LHKPN_HARTA_LAINNYA as B', 'on' => 'A.ID_HARTA   = ' . 'B.ID']], NULL, $selectlampiranpelepasan . $selectpelepasanhartalainnya, "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasanmanual = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_MANUAL as A', NULL, NULL, '*', "A.ID_LHKPN = '$id_lhkpn'");
        $pelepasan = [];
        $masterpelepasan = $this->mglobal->get_data_all('m_jenis_pelepasan_harta', NULL, NULL, '*');

        //packing hasil calling data lampiran pelepasan
        if (!empty($pelepasanhartatidakbergerak)) {
            foreach ($pelepasanhartatidakbergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Tanah/Bangunan , Atas Nama " . @$key->ATAS_NAMA . " dengan luas tanah " . @$key->LUAS_TANAH . " dan luas bangunan " . @$key->LUAS_BANGUNAN . " dengan bukti berupa " . $list_bukti[$key->JENIS_BUKTI] . " dengan nomor bukti " . @$key->NOMOR_BUKTI,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartabergerak)) {
            foreach ($pelepasanhartabergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Sebuah " . $list_harta[@$key->KODE_JENIS] . " , Atas Nama " . @$key->ATAS_NAMA . " , merek " . @$key->MEREK . " dengan nomor registrasi " . $key->NOPOL_REGISTRASI . " dan nomor bukti " . @$key->NOMOR_BUKTI,
//                    'URAIAN_HARTA' => "<b>Jenis : </b>  " . $list_harta[@$key->KODE_JENIS]  . "<br/><b> Merek : </b> " . @$key->MEREK  . "<br/><b> Model : </b> " . @$key->MODEL . "<br/><b> Tahun Pembuatan : </b> " . @$key->TAHUN_PEMBUATAN . " <br/><b>No Pol / Registrasi : </b> " . $key->NOPOL_REGISTRASI ,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartabergeraklain)) {
            foreach ($pelepasanhartabergeraklain as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $list_harta_berhenti[@$key->KODE_JENIS] . " bernama " . @$key->NAMA_HARTA . " , Atas nama " . @$key->ATAS_NAMA . " dengan jumlah " . @$key->JUMLAH . ' ' . @$key->SATUAN,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasansuratberharga)) {
            foreach ($pelepasansuratberharga as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $list_harta_surat[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' berupa surat ' . @$key->NAMA_SURAT . ' dengan jumlah ' . @$key->JUMLAH . ' ' . @$key->SATUAN,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasankas)) {
            foreach ($pelepasankas as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "KAS berupa " . $list_harta_kas[@$key->KODE_JENIS] . ', Atas nama ' . @$key->ATAS_NAMA . ' pada bank ' . @$key->NAMA_BANK . ' dengan nomor rekening ' . @$key->NOMOR_REKENING,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartalainnya)) {
            foreach ($pelepasanhartalainnya as $key) {
                $pelepasan[] = [
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => "Harta lain berupa " . $list_harta_lain[@$key->KODE_JENIS] . ' dengan nama harta ' . @$key->NAMA_HARTA . ' atas nama ' . @$key->ATAS_NAMA,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }

        if (!empty($pelepasanmanual)) {
            foreach ($pelepasanmanual as $key) {
                $pelepasan[] = [
                    'ID' => $key->ID,
                    'KODE_JENIS' => $this->cekMasterPelepasan($masterpelepasan,$key->JENIS_PELEPASAN_HARTA),
                    'TGL_TRANSAKSI' => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA' => $key->URAIAN_HARTA,
                    'KETERANGAN' => $key->KETERANGAN,
                    'ALAMAT' => $key->ALAMAT,
                    'NILAI' => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA' => $key->NAMA,
                ];
            }
        }

        return $pelepasan;
    }


    private function cekMasterPelepasan($data = null, $id_pelepasan = null){
        foreach($data as $d){
            if($d->ID == $id_pelepasan){
                return $d->JENIS_PELEPASAN_HARTA;
            }
        }
        return 'null';
    }


    private function _lampiran_hibah($id_lhkpn, $where = NULL) {
        if (is_null($where)) {
            $where = '';
        }
        $result = $this->db->query("
	      SELECT
	        'Tanah / Bangunan' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT('Tanah/Bangunan , Atas Nama ',ATAS_NAMA,' dengan luas tanah ',LUAS_TANAH,' dan luas bangunan ',LUAS_BANGUNAN,' dengan bukti berupa ',
	        C.JENIS_BUKTI,' dengan nomor bukti ',NOMOR_BUKTI) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_TIDAK_BERGERAK A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_BUKTI C ON A.JENIS_BUKTI=C.ID_JENIS_BUKTI
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Mesin / Alat Transport' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT('Sebuah ',C.NAMA,' , Atas Nama ',ATAS_NAMA,' , merek ',MEREK,' dengan nomor registrasi ',NOPOL_REGISTRASI,' dan nomor bukti ',NOMOR_BUKTI) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_BERGERAK A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Harta bergerak' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT(
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Perabotan Rumah Tangga'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Barang Elektronik'
	            WHEN KODE_JENIS LIKE '%3%' THEN 'Perhiasan & Logam / Batu Mulia'
	            WHEN KODE_JENIS LIKE '%4%' THEN 'Persediaan'
	            WHEN KODE_JENIS LIKE '%5%' THEN 'Harta Bergerak Lainnya'
	          END,
	          ' bernama ',A.NAMA,' , Atas nama ',ATAS_NAMA,' dengan jumlah ',JUMLAH,' ',SATUAN) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_BERGERAK_LAIN A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Surat Berharga' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT(
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Penyertaan Modal pada Badan Hukum'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Investasi'
	          END,
	          ', Atas nama ',ATAS_NAMA,' berupa surat ',NAMA_SURAT_BERHARGA,' dengan jumlah ',JUMLAH,' ',SATUAN) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_SURAT_BERHARGA A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Kas / Setara Kas' as kode,
	        '' as tgl,
	        CONCAT('KAS berupa ',
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Uang Tunai'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Deposite'
	            WHEN KODE_JENIS LIKE '%3%' THEN 'Giro'
	            WHEN KODE_JENIS LIKE '%4%' THEN 'Tabungan'
	            WHEN KODE_JENIS LIKE '%5%' THEN 'Lainnya'
	          END,
	          ', Atas nama ',ATAS_NAMA_REKENING,' pada bank ',NAMA_BANK,' dengan nomor rekening ',NOMOR_REKENING) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_KAS A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_KAS B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn'
	        UNION

	      SELECT
	        'Harta Lainnya' as kode,
	        TANGGAL_TRANSAKSI as tgl,
	        CONCAT('Harta lain berupa ',
	          CASE
	            WHEN KODE_JENIS LIKE '%1%' THEN 'Piutang'
	            WHEN KODE_JENIS LIKE '%2%' THEN 'Kerjasama Usaha yang Tidak Berbadan Hukum'
	            WHEN KODE_JENIS LIKE '%3%' THEN 'Hak Kekayaan Intelektual'
	            WHEN KODE_JENIS LIKE '%4%' THEN 'Sewa Jangaka Panjang Dibayar Dimuka'
	            WHEN KODE_JENIS LIKE '%5%' THEN 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan'
	          END,
	        ' dengan nama harta ',A.NAMA,' atas nama ',ATAS_NAMA) as uraian,
	        NILAI_PELEPASAN as nilai,
	        D.ASAL_USUL as jenis,
	        B.ALAMAT as almat,
	        B.NAMA as nama

	        from T_LHKPN_HARTA_LAINNYA A
	        INNER JOIN T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA B ON A.ID=B.ID_HARTA
	        INNER JOIN M_JENIS_HARTA C ON A.KODE_JENIS=C.ID_JENIS_HARTA
	        INNER JOIN M_ASAL_USUL D ON B.ID_ASAL_USUL=D.ID_ASAL_USUL
	        WHERE ID_LHKPN = '$id_lhkpn' AND B.NAMA LIKE '%$where%'")->result();

        return $result;
    }

    public function verupload($type = '', $id = '', $viaa = '') {
        $defulttable = 'tabel_';

        $joinMATA_UANG = [
            ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
            ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
        ];

        $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);

        switch ($type) {
            case 'suratberharga':
                $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'  and T_LHKPN_HARTA_SURAT_BERHARGA.IS_ACTIVE = 1 ");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'suratberharga');
                $this->data['viaa'] = $viaa;
                break;
            case 'kas':
                $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'  and T_LHKPN_HARTA_KAS.IS_ACTIVE = 1 ");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'kas');
                $this->data['viaa'] = $viaa;
                break;
            case 'hartalainnya':
                $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_LAINNYA.IS_ACTIVE = 1 ");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartalainnya');
                $this->data['viaa'] = $viaa;
                break;
            case 'skm':
                $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'], ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'T_LHKPN_DATA_PRIBADI.ID_LHKPN']], NULL, '*', "T_LHKPN.ID_LHKPN = '$id'")[0];
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'lhkpn');
                break;
            case 'sk':
                $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'], ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'T_LHKPN_DATA_PRIBADI.ID_LHKPN']], NULL, '*', "T_LHKPN.ID_LHKPN = '$id'")[0];
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'lhkpn');
                break;
            default:
                # code...
                break;
        }

        if ($type == 'sk') {
            $type = 'skm';
        }

        $this->load->view('verification/verification_' . $defulttable . $type . '', $this->data);
    }

    public function vertable($type = '', $id = '') {
        $this->load->model('mlhkpnkeluarga');
        $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id'")[0];
        $defulttable = 'tabel_';
        $joinMATA_UANG = [
            ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ];

        //jenis bukti
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, ['ID_JENIS_BUKTI', 'DESC']);
        $list_bukti = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        //jenis Harta
        $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA', NULL, ['ID_JENIS_HARTA', 'DESC']);
        $list_harta = [];
        foreach ($jenis_HARTA as $key) {
            $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
        }

        $this->data['list_harta'] = $list_harta;
        $this->data['list_bukti'] = $list_bukti;
        $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
        $this->data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
        $this->data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);

        switch ($type) {
            case 'jabatan':

                $joinJabatan = [
                    ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                ];
                $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, '*', "T_LHKPN_JABATAN.ID_LHKPN = '$id'");

                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'jabatan');
                $this->data['ID_PN'] = $this->data['LHKPN']->ID_PN;
                $this->data['ID_LHKPN'] = $id;
                $id_pn = $this->data['LHKPN']->ID_PN;
                $joinJabatan = [
                    ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_PN_JABATAN.UNIT_KERJA'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN'],
                ];
                $this->data['JABATANSPN'] = $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "T_PN_JABATAN.ID_PN = '$id_pn'");

                $sql_jabatan_lhkpn = "SELECT NAMA_JABATAN FROM M_JABATAN JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN WHERE T_LHKPN_JABATAN.ID_LHKPN = ( SELECT ID_LHKPN FROM T_LHKPN WHERE T_LHKPN.ID_LHKPN = '$id' )";
                $this->data['JABATAN_LHKPN'] = $this->db->query($sql_jabatan_lhkpn)->result();

                $cek = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, 'KD_ISO3_NEGARA', "ID_LHKPN = '$id'")[0];
                $joinDATA_PRIBADI = [];
                $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*';
                if (@$cek->KD_ISO3_NEGARA == '') {
                    $joinDATA_PRIBADI = [
                        // ['table' => 'M_PROVINSI', 'on' => 'M_PROVINSI.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI'],
                        // ['table' => 'M_KABKOT', 'on' => 'M_KABKOT.IDKOT = T_LHKPN_DATA_PRIBADI.KABKOT AND M_AREA.IDPROV = M_KABKOT.IDPROV'],
                        ['table' => 'M_AREA', 'on' => 'M_AREA.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI AND M_AREA.IDKOT = CAST(T_LHKPN_DATA_PRIBADI.KABKOT AS UNSIGNED) AND M_AREA.IDKEC = T_LHKPN_DATA_PRIBADI.KECAMATAN AND M_AREA.IDKEL = ""'],
                    ];
                    $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*';
                } else {
                    $joinDATA_PRIBADI = [
                        ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.KODE_ISO3 = T_LHKPN_DATA_PRIBADI.KD_ISO3_NEGARA'],
                    ];
                    $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*,M_NEGARA.NAMA_NEGARA as KD_ISO3_NEGARA';
                }

                $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', $joinDATA_PRIBADI, NULL, $selectDATA_PRIBADI, "ID_LHKPN = '$id'")[0];

                $diff = (count($this->data['JABATANSPN']) == count($this->data['JABATANSPN']) ? true : false);
                if ($diff) {
                    $diff = pembanding($this->data['JABATANSPN'], $this->data['JABATANS']);
                }

                $tmp = [
                    'status' => $diff
                ];

                if ($diff == false) {
                    $idLembaga = implode("','", array_unique(array_merge(array_column(json_decode(json_encode($this->data['JABATANSPN']), true), 'LEMBAGA'), array_column(json_decode(json_encode($this->data['JABATANS']), true), 'LEMBAGA'))));
                    $lembaga = array_unique(array_merge(array_column(json_decode(json_encode($this->data['JABATANSPN']), true), 'INST_NAMA'), array_column(json_decode(json_encode($this->data['JABATANS']), true), 'INST_NAMA')));
                    $tmp['lembaga'] = $lembaga;
                    $email = $this->mglobal->get_data_all(
                            'T_USER', [
                        ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE']
                            ], NULL, 'ID_USER, EMAIL', "INST_SATKERKD IN ('$idLembaga') AND (IS_INSTANSI = '1' OR IS_USER_INSTANSI = '1')");
                    $tmp['email'] = array_column(json_decode(json_encode($email), true), 'EMAIL');
                    $tmp['id'] = array_column(json_decode(json_encode($email), true), 'ID_USER');
                }

                $this->data['diffJabatan'] = $tmp;
                break;
            case 'keluarga':
                $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian2("ID_LHKPN = '$id'");
                $this->data['KELUARGAS'] = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'keluarga');
                break;
            case 'hartatidakbergerak':
                $joinHARTA_TIDAK_BERGERAK = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                    //['table' => 'M_AREA as area', 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV', 'join' => 'left']
                    ['table' => 'M_AREA_KAB as kabkot', 'on' => 'kabkot.NAME_KAB   = data.KAB_KOT', 'join' => 'left'],
                    ['table' => 'M_AREA_PROV as provinsi', 'on' => 'provinsi.PROV = data.PROV', 'join' => 'left']
                ];

                $KABKOT = "(SELECT NAME_KAB FROM M_AREA_KAB as area WHERE data.KAB_KOT = area.NAME_KAB) as KAB_KOT";
                $PROV = "(SELECT NAME FROM M_AREA_PROV as area WHERE data.PROV = area.NAME) as PROV";
                //$KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
                //$PROV = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND '' = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as PROV";
                $selectHARTA_TIDAK_BERGERAK = 'IS_CHECKED, data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL,' . $KABKOT . ',' . $PROV . ', data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

                $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartatidakbergerak');
                break;
            case 'hartabergerak':
                $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartabergerak');
                break;
            case 'hartabergerakperabot':
                $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartabergerakperabot');
                break;
            case 'suratberharga':
                $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'suratberharga');
                break;
            case 'kas':
                $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'kas');
                break;
            case 'hartalainnya':
                $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hartalainnya');
                break;
            case 'hutang':
                $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'hutang');
                break;
            case 'penerimaanfasilitas':
                $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['LHKPN']->ID_LHKPN, 'penerimaanfasilitas');
                $type = 'lampiran_2';
                $defulttable = 'table_';
                break;

            default:
                # code...
                break;
        }
        // print_r($this->data['KELUARGAS']);

        $this->load->view('verification/verification_' . $defulttable . $type . '', $this->data);
    }

    public function hasilVerifikasi($id, $ITEMVER = '', $history = false) {
        $this->db->select('*');
        $this->db->from('T_VERIFICATION_ITEM');
        $this->db->where('ID_LHKPN', $id);
        $this->db->where('ITEMVER', $ITEMVER);

        $rsVer = $this->db->get('')->result();
        if ($history) {
            return $rsVer;
        }

        $hasil = [];
        $catatan = [];
        $editable = [];
        foreach ($rsVer as $ver) {
            $hasil[$ver->ID] = $ver->HASIL;
            $catatan[$ver->ID] = $ver->CATATAN;
            $editable[$ver->ID] = $ver->IS_EDITABLE;
        }
        return [$ITEMVER => ['hasil' => $hasil, 'catatan' => $catatan, 'editable' => $editable]];
    }

    function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }

    public function veritem($type = '', $id = '') {
        if ($type == 'reff') {
            // echo $this->display;
            $this->data['ITEMVER'] = $this->display;
            $this->data['ID'] = $id;

            switch ($this->display) {
                case 'jabatan':
                    // echo 'ID KELUARGA : '.$id.'<br>';
                    // echo 'detail keluarga';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_JABATAN', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#jabatan';
                    // display($this->data);
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'keluarga':
                    // echo 'ID KELUARGA : '.$id.'<br>';
                    // echo 'detail keluarga';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_KELUARGA', NULL, NULL, '*', "ID_KELUARGA = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#keluarga';
                    // display($this->data);
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'hartatidakbergerak':
                    // echo 'ID HARTA TIDAK BERGERAK : '.$id.'<br>';
                    // echo 'detail Harta Tidak Bergerak';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hartatidakbergerak';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'hartabergerak':
                    // echo 'ID HARTA BERGERAK : '.$id.'<br>';
                    // echo 'detail Harta Bergerak';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_BERGERAK', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hartabergerak';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'hartabergerakperabot':
                    // echo 'ID HARTA BERGERAK Perabot : '.$id.'<br>';
                    // echo 'detail Harta Bergerak Perabot';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hartabergerakperabot';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'suratberharga':
                    // echo 'ID SURAT BERHARGA : '.$id.'<br>';
                    // echo 'detail surat berharga';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratberharga';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'kas':
                    // echo 'ID KAS : '.$id.'<br>';
                    // echo 'detail KAS';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_KAS', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#kas';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'hartalainnya':
                    // echo 'ID HARTA LAIN : '.$id.'<br>';
                    // echo 'detail HARTA LAIN';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_LAINNYA', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hartalainnya';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'hutang':
                    // echo 'ID HUTANG : '.$id.'<br>';
                    // echo 'detail HUTANG';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HUTANG', NULL, NULL, '*', "ID_HUTANG = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hutang';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'penerimaanfasilitas':
                    // echo 'ID HUTANG : '.$id.'<br>';
                    // echo 'detail HUTANG';
                    // echo $this->display.'<br>';
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#penerimaanfasilitas';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    // display($this->data['item']);
                    // echo 'History Verifikasi<br>';
                    break;
                case 'value':
                    break;

                default:
                    break;
            }
        } else if ($type == 'save') {
            if ($this->act == 'doverify') {
                $data = $this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE), 'ITEMVER' => $this->input->post('ITEMVER', TRUE), 'ID' => $this->input->post('ID', TRUE)])[0];
                if ($data) {
                    $veritem = array(
                        'HASIL' => $this->input->post('HASIL', TRUE),
                        'CATATAN' => $this->input->post('CATATAN', TRUE),
                        'UPDATED_TIME' => time(),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );
                    $this->db->where('ID_VERIFICATION_ITEM', $data->ID_VERIFICATION_ITEM);
                    $this->db->update('T_VERIFICATION_ITEM', $veritem);
                } else {
                    $veritem = array(
                        'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                        'ITEMVER' => $this->input->post('ITEMVER', TRUE),
                        'ID' => $this->input->post('ID', TRUE),
                        'HASIL' => $this->input->post('HASIL', TRUE),
                        'CATATAN' => $this->input->post('CATATAN', TRUE),
                        'CREATED_TIME' => time(),
                        'CREATED_BY' => $this->session->userdata('USR'),
                        'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    );

                    $this->db->insert('T_VERIFICATION_ITEM', $veritem);
                    $this->db->insert_id();
                }
            }
        }
    }


    public function image($type = '', $id = '', $image ='') {
        $this->data['currImage'] =  $image;
        if ($type == 'reff') {
            // echo $this->display;
            $this->data['ITEMVER'] = $this->display;
            $this->data['ID'] = $id;
            $this->data['ID_LHKPN'] = $id_lhkpn;
            $this->data['idImage'] = $this->data['tgl_klarifikasi'];

            switch ($this->display) {
                case 'suratberharga':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratberharga';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];

                    break;
                case 'kas':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_KAS', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#kas';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    break;
                case 'hartalainnya':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_LAINNYA', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hartalainnya';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];

                    break;
                case 'skm':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratkuasamengumumkan';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];

                    break;
                case 'sk':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratkuasamengumumkan';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    break;
                case 'value':
                    break;

                default:
                    break;
            }
        } else if ($type == 'save') {
            $FILE_BUKTI = NULL;
            $ID = $this->input->post('ID', TRUE);
            $ID_LHKPN = $this->input->post('ID_LHKPN', TRUE);
            $ITEMVER = $this->input->post('ITEMVER', TRUE);
            $CURR_IMAGE = $this->input->post('CURR_IMAGE', TRUE);

            if ($ITEMVER == 'suratberharga') {
                $this->data['item'] = $ID ? $this->mglobal->get_data_all_array('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, NULL, '*', "ID = '$ID'")[0] : '';
                $TABLE = 't_lhkpn_harta_surat_berharga';
            } else if ($ITEMVER == 'kas') {
                $this->data['item'] = $ID ? $this->mglobal->get_data_all_array('T_LHKPN_HARTA_KAS', NULL, NULL, '*', "ID = '$ID'")[0] : '';
                $TABLE = 't_lhkpn_harta_kas';
            } else if ($ITEMVER == 'skm') {
                $this->data['item'] = $ID ? $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$ID'")[0] : '';
                $TABLE = 't_lhkpn';
            } else if ($ITEMVER == 'sk') {
                $this->data['item'] = $ID ? $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$ID'")[0] : '';
                $TABLE = 't_lhkpn';
            } else {
                $this->data['item'] = $ID ? $this->mglobal->get_data_all_array('T_LHKPN_HARTA_LAINNYA', NULL, NULL, '*', "ID = '$ID'")[0] : '';
                $TABLE = 't_lhkpn_harta_lainnya';
            }
            $filelist = explode(',', $this->data['item']['FILE_BUKTI']);

            $dir = null;
            $img = '';
            $list = count($filelist);

            foreach ($filelist as $key => $tmp_name) {
                if ($key==0) {
                    $dt = explode("/", $tmp_name);
                    $c = count($dt);
                    for($i=0; $i<$c-1; $i++) {
                        $dir = $dir . $dt[$i] . "/";
                    }
                    $tmp_name = $dt[$i++];
                }
                if ($CURR_IMAGE!=$tmp_name && $tmp_name!='') {
                    $FILE_BUKTI = $FILE_BUKTI . $tmp_name . ',';
                }
            }
            $FILE_BUKTI = $dir . $FILE_BUKTI;
            $PK = $this->pk($TABLE);

            $data = array(
                'UPDATED_TIME' => date("Y-m-d H:i:s"),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                'FILE_BUKTI' => $FILE_BUKTI);

            $this->db->where('ID', $ID);
            $this->db->update($TABLE, $data);
        }
    }

    public function upload($type = '', $id = '') {
        if ($type == 'reff') {
            // echo $this->display;
            $this->data['ITEMVER'] = $this->display;
            $this->data['ID'] = $id;
            $this->data['ID_LHKPN'] = $id_lhkpn;
            switch ($this->display) {
                case 'suratberharga':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratberharga';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    $id_lhkpn = $this->data['item']->ID_LHKPN;
                    $this->data['newItem'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0] : '';
                    $this->data['viaa'] = $this->data['newItem']->entry_via;
                    break;
                case 'kas':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_KAS', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#kas';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    $id_lhkpn = $this->data['item']->ID_LHKPN;
                    $this->data['newItem'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0] : '';
                    $this->data['viaa'] = $this->data['newItem']->entry_via;
                    break;
                case 'hartalainnya':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN_HARTA_LAINNYA', NULL, NULL, '*', "ID = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#hartalainnya';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    $id_lhkpn = $this->data['item']->ID_LHKPN;
                    $this->data['newItem'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id_lhkpn'")[0] : '';
                    $this->data['viaa'] = $this->data['newItem']->entry_via;
                    break;
                case 'skm':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratkuasamengumumkan';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];

                    break;
                case 'sk':
                    $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array('T_LHKPN', NULL, NULL, '*', "ID_LHKPN = '$id'")[0] : '';
                    $this->data['hasilVerifikasi'] = $this->hasilVerifikasi($this->data['item']->ID_LHKPN, $this->display, true);
                    $this->data['thisTab'] = '#suratkuasamengumumkan';
                    $this->data['veritem'] = @$this->mglobal->get_data_all('T_VERIFICATION_ITEM', NULL, ['ID_LHKPN' => $this->data['item']->ID_LHKPN, 'ITEMVER' => $this->data['ITEMVER'], 'ID' => $this->data['ID']])[0];
                    break;
                case 'value':
                    break;

                default:
                    break;
            }
        } else if ($type == 'save') {
            if ($this->act == 'doupload') {
                $FILE_BUKTI = NULL;
                $ID = $this->input->post('ID', TRUE);
                $ID_LHKPN = $this->input->post('ID_LHKPN', TRUE);
                $ITEMVER = $this->input->post('ITEMVER', TRUE);

                if ($ITEMVER == 'suratberharga') {
                    $func_upload = $this->upload_surat_berharga($ID_LHKPN);
                    $TABLE = 't_lhkpn_harta_surat_berharga';
                } else if ($ITEMVER == 'kas') {
                    $func_upload = $this->upload_kas($ID_LHKPN);
                    $TABLE = 't_lhkpn_harta_kas';
                } else if ($ITEMVER == 'skm') {
                    $func_upload = $this->upload_skm($ID_LHKPN);
                    $TABLE = 't_lhkpn';
                } else if ($ITEMVER == 'sk') {
                    $func_upload = $this->upload_sk($ID_LHKPN);
                    $TABLE = 't_lhkpn';
                } else {
                    $func_upload = $this->upload_harta_lainnya($ID_LHKPN);
                    $TABLE = 't_lhkpn_harta_lainnya';
                }

                $PK = $this->pk($TABLE);
                $storage_minio = NULL;

                if ($ID || $ID_LHKPN) {
                    $this->db->where($PK, $ID);
                    $temp = $this->db->get($TABLE)->row();
                    $upload = $func_upload;
                    if ($upload['upload']) {
                        if ($temp) {
                            if ($temp->FILE_BUKTI) {
                                unlink($temp->FILE_BUKTI);
                                $FILE_BUKTI = $upload['url'];
                                $storage_minio = $upload['storage'];
                            } else {
                                $FILE_BUKTI = $upload['url'];
                                $storage_minio = $upload['storage'];
                            }
                        }
                    } else {
                        $FILE_BUKTI = $temp->FILE_BUKTI;
                    }
                } else {
                    $upload = $func_upload();
                    if ($upload['upload']) {
                        $FILE_BUKTI = $upload['url'];
                        $storage_minio = $upload['storage'];
                    }
                }

                if ($ID || $ID_LHKPN) {
                    $data = array(
                        'UPDATED_TIME' => date("Y-m-d H:i:s"),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                        'FILE_BUKTI' => $FILE_BUKTI,
                        'STORAGE_MINIO' => $storage_minio,
                    );

                    $data_skm = array(
                        'FILE_BUKTI_SKM' => $FILE_BUKTI
                    );

                    $data_sk = array(
                        'FILE_BUKTI_SK' => $FILE_BUKTI
                    );

                    if ($ITEMVER == 'skm') {
                        $this->db->where('ID_LHKPN', $ID);
                        $this->db->update($TABLE, $data_skm);
                    } else if ($ITEMVER == 'sk') {
                        $this->db->where('ID_LHKPN', $ID);
                        $this->db->update($TABLE, $data_sk);
                    } else {
                        $this->db->where('ID', $ID);
                        $this->db->update($TABLE, $data);
                    }
                }
            }
        }
    }

    function encrypt($string, $action = 'e') {
        $secret_key = 'R@|-|a5iaKPK|-|@rTa';
        $secret_iv = 'R@|-|a5ia|/|394124|-|@rTa';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
        exit;
    }


    function upload_surat_berharga($ID_LHKPN=null) {
        $result = array();

        //////////SISTEM KEAMANAN///////////
        $post_nama_file = 'file1';
        $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff");
        $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan);
        if($check_protect){
            $this->db->trans_rollback();
            $method = __METHOD__;
            $this->load->model('mglobal');
            $this->mglobal->recordLogAttacker($check_protect,$method);
            echo 'INGAT DOSA WAHAI PARA HACKER';
            exit;
        }
        //////////SISTEM KEAMANAN///////////



        $query_nik = 'SELECT t_lhkpn.id_pn,t_lhkpn.id_lhkpn,t_pn.nik FROM t_lhkpn
                        LEFT JOIN t_pn ON t_pn.id_pn = t_lhkpn.id_pn
                        WHERE id_lhkpn = '.$ID_LHKPN;
        $query_result =  $this->db->query($query_nik)->result_array();
        if($query_result){
            $folder = $query_result[0]['nik'];
        }else{
            // $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
            $folder = $this->session->userdata('USERNAME');
        }
        if (!file_exists('uploads/data_suratberharga/' . $folder)) {
            mkdir('uploads/data_suratberharga/' . $folder);
        }
        $folder = $folder.'/'.$ID_LHKPN;

        if (!file_exists('uploads/data_suratberharga/' . $folder)) {
            mkdir('uploads/data_suratberharga/' . $folder);
            $content = "Bukti Surat Berharga Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_suratberharga/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }

        if(switchMinio()){
            ///////////////check Path MINIO//////////////////
            $jenis_path = 'surat_berharga';
            $checkPath = checkPathMinio('uploads/data_suratberharga',1,$ID_LHKPN,$jenis_path);
            $sub_path_minio = $checkPath['path'].'/';
            $storage_minio = $checkPath['storage'];
            ///////////////check Path MINIO//////////////////
        }else{
            $sub_path_minio = '';
            $storage_minio = null;
        }

        $rst = false;
        $urllist = 'uploads/data_suratberharga/' .$sub_path_minio.''. $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_suratberharga/' .$sub_path_minio.''. $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);
            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {

                if(switchMinio()){
                    // upload to minio
                    $uploadDirMinio = 'uploads/data_suratberharga/' .$sub_path_minio.''. $folder . '/';
                    $resultMinio = uploadMultipleToMinio($_FILES['file1']['tmp_name'][$key],$_FILES['file1']['type'][$key],$file_name,$uploadDirMinio,$storage_minio );
                    $rst = false;
                    if($resultMinio){
                      $rst = true;
                    }
                }else{
                    //upload to local
                    $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
                }
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist, 'storage'=>$storage_minio);
        return $result;
    }

//     function upload_surat_berharga() {
//         $result = array();
//         $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
//         if (!file_exists('uploads/data_suratberharga/' . $folder)) {
//             mkdir('uploads/data_suratberharga/' . $folder);
//             $content = "Bukti Surat Berharga Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
//             $fp = fopen(FCPATH . "/uploads/data_suratberharga/" . $folder . "/readme.txt", "wb");
//             fwrite($fp, $content);
//             fclose($fp);
//             /* IBO UPDATE */

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* IBO UPDATE */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
//         /* header('Content-Type: application/json');
//           echo json_encode($result); */
//         return $result;
//     }


    function upload_skm() {
        $result = array();

        $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
        if (!file_exists('uploads/data_skm/' . $folder)) {
            mkdir('uploads/data_skm/' . $folder);
            $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_skm/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }

        $rst = false;
        $urllist = 'uploads/data_sk/' . $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);
            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);
        return $result;
    }


//     function upload_skm() {
//         $result = array();
//         $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

//         if (!file_exists('uploads/data_skm/' . $folder)) {
//             mkdir('uploads/data_skm/' . $folder);
//             $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
//             $fp = fopen(FCPATH . "/uploads/data_skm/" . $folder . "/readme.txt", "wb");
//             fwrite($fp, $content);
//             fclose($fp);

//             /* --- IBO ADD -- */

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* ---End IBO ADD -- */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
//         /* header('Content-Type: application/json');
//           echo json_encode($result); */
//         return $result;
//     }



    function upload_sk() {
        $result = array();

        $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
        if (!file_exists('uploads/data_sk/' . $folder)) {
            mkdir('uploads/data_sk/' . $folder);
            $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_sk/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }

        $rst = false;
        $urllist = 'uploads/data_sk/' . $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);
            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);
        return $result;
    }


//     function upload_sk() {
//         $result = array();
//         $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

//         if (!file_exists('uploads/data_sk/' . $folder)) {
//             mkdir('uploads/data_sk/' . $folder);
//             $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
//             $fp = fopen(FCPATH . "/uploads/data_sk/" . $folder . "/readme.txt", "wb");
//             fwrite($fp, $content);
//             fclose($fp);

//             /* --- IBO ADD -- */

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* ---End IBO ADD -- */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
//         /* header('Content-Type: application/json');
//           echo json_encode($result); */
//         return $result;
//     }



    function upload_kas($ID_LHKPN=null) {
        $result = array();

        $query_nik = 'SELECT t_lhkpn.id_pn,t_lhkpn.id_lhkpn,t_pn.nik FROM t_lhkpn
                        LEFT JOIN t_pn ON t_pn.id_pn = t_lhkpn.id_pn
                        WHERE id_lhkpn = '.$ID_LHKPN;
        $query_result =  $this->db->query($query_nik)->result_array();
        if($query_result){
            $folder = $query_result[0]['nik'];
        }else{
            // $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
            $folder = $this->session->userdata('USERNAME');
        }
        if (!file_exists('uploads/data_kas/' . $folder)) {
            mkdir('uploads/data_kas/' . $folder);
        }
        $folder = $folder.'/'.$ID_LHKPN;

        if (!file_exists('uploads/data_kas/' . $folder)) {
            mkdir('uploads/data_kas/' . $folder);
            $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_kas/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* --- IBO ADD -- */
        }

        //////////SISTEM KEAMANAN///////////
        $post_nama_file = 'file1';
        $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff");
        $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan);
        if($check_protect){
            $this->db->trans_rollback();
            $method = __METHOD__;
            $this->load->model('mglobal');
            $this->mglobal->recordLogAttacker($check_protect,$method);
            echo 'INGAT DOSA WAHAI PARA HACKER';
            exit;
        }
        //////////SISTEM KEAMANAN///////////

        if(switchMinio()){
            ///////////////check Path MINIO//////////////////
            $jenis_path = 'surat_berharga';
            $checkPath = checkPathMinio('uploads/data_kas',1,$ID_LHKPN,$jenis_path);
            $sub_path_minio = $checkPath['path'].'/';
            $storage_minio = $checkPath['storage'];
            ///////////////check Path MINIO//////////////////
        }else{
            $sub_path_minio = '';
            $storage_minio = null;
        }

        $rst = false;
        $urllist = 'uploads/data_kas/' .$sub_path_minio.''. $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_kas/'.$sub_path_minio.''. $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);
            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                if(switchMinio()){
                    // upload to minio
                    $uploadDirMinio = 'uploads/data_kas/' .$sub_path_minio.''. $folder . '/';
                    $resultMinio = uploadMultipleToMinio($_FILES['file1']['tmp_name'][$key],$_FILES['file1']['type'][$key],$file_name,$uploadDirMinio,$storage_minio );
                    $rst = false;
                    if($resultMinio){
                      $rst = true;
                    }
                }else{
                    //upload to local
                    $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
                }
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist, 'storage'=>$storage_minio);

        return $result;
    }

//     function upload_kas() {
//         $result = array();
//         $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

//         if (!file_exists('uploads/data_kas/' . $folder)) {
//             mkdir('uploads/data_kas/' . $folder);
//             $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
//             $fp = fopen(FCPATH . "/uploads/data_kas/" . $folder . "/readme.txt", "wb");
//             fwrite($fp, $content);
//             fclose($fp);

//             /* --- IBO ADD -- */

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* ---End IBO ADD -- */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
//         /* header('Content-Type: application/json');
//           echo json_encode($result); */
//         return $result;
//     }


    function upload_harta_lainnya($ID_LHKPN=null) {
        $result = array();

        $query_nik = 'SELECT t_lhkpn.id_pn,t_lhkpn.id_lhkpn,t_pn.nik FROM t_lhkpn
                        LEFT JOIN t_pn ON t_pn.id_pn = t_lhkpn.id_pn
                        WHERE id_lhkpn = '.$ID_LHKPN;
        $query_result =  $this->db->query($query_nik)->result_array();
        if($query_result){
            $folder = $query_result[0]['nik'];
        }else{
            // $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
            $folder = $this->session->userdata('USERNAME');
        }
        if (!file_exists('uploads/data_hartalainnya/' . $folder)) {
            mkdir('uploads/data_hartalainnya/' . $folder);
        }
        $folder = $folder.'/'.$ID_LHKPN;

        if (!file_exists('uploads/data_hartalainnya/' . $folder)) {
            mkdir('uploads/data_hartalainnya/' . $folder);
            $content = "Bukti Harta Lainnya Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_hartalainnya/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }

        if(switchMinio()){
            ///////////////check Path MINIO//////////////////
            $jenis_path = 'harta_lainnya';
            $checkPath = checkPathMinio('uploads/data_hartalainnya',1,$ID_LHKPN,$jenis_path);
            $sub_path_minio = $checkPath['path'].'/';
            $storage_minio = $checkPath['storage'];
            ///////////////check Path MINIO//////////////////
        }else{
            $sub_path_minio = '';
            $storage_minio = null;
        }

        $rst = false;
        // $urllist = 'uploads/data_hartalainnya/' . $folder . '/';
        $urllist = 'uploads/data_hartalainnya/' .$sub_path_minio.''. $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            // $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
            $uploaddir = 'uploads/data_hartalainnya/' .$sub_path_minio.''. $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);
            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {

                if(switchMinio()){
                    // upload to minio
                    $uploadDirMinio = 'uploads/data_hartalainnya/' .$sub_path_minio.''. $folder . '/';
                    $resultMinio = uploadMultipleToMinio($_FILES['file1']['tmp_name'][$key],$_FILES['file1']['type'][$key],$file_name,$uploadDirMinio,$storage_minio );
                    $rst = false;
                    if($resultMinio){
                      $rst = true;
                    }
                }else{
                    //upload to local
                    $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
                }
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist, 'storage'=>$storage_minio);

        return $result;
    }

//     function upload_harta_lainnya() {
//         $result = array();
//         $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
//         if (!file_exists('uploads/data_hartalainnya/' . $folder)) {
//             mkdir('uploads/data_hartalainnya/' . $folder);
//             $content = "Bukti Harta Lainnya Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
//             $fp = fopen(FCPATH . "/uploads/data_hartalainnya/" . $folder . "/readme.txt", "wb");
//             fwrite($fp, $content);
//             fclose($fp);
//             /* IBO UPDATE */

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* IBO UPDATE */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
//         /* header('Content-Type: application/json');
//           echo json_encode($result); */
//         return $result;
//     }

    //show file
    public function show_file($folder = 'unknown', $nik = 'unknown', $namafile = '') {
        $data = array(
            'FOLDER' => $folder,
            'NIK' => $nik,
            'FILE' => $namafile,
        );
        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_modal_file', $data);
    }

    function getInfoPn($id) {
        $this->load->model('mglobal');

        $joinpnwl = [['table' => 'T_PN', 'on' => 'T_USER.USERNAME = T_PN.NIK']];
        $wherepnwl = [
            'T_USER.USERNAME' => $this->session->userdata('USERNAME'),
            'T_PN.ID_PN' => $id,
        ];
        $joininst = [
            ['table' => 'T_PN', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
            ['table' => 'T_PN_JABATAN', 'on' => 'T_PN.ID_PN = T_PN_JABATAN.ID_PN']
        ];
        $id_lembaga = $this->mglobal->get_data_all('T_USER', $joininst, ['T_USER.USERNAME' => $this->session->userdata('USERNAME'), 'T_PN_JABATAN.IS_CURRENT' => '1'], 'T_PN_JABATAN.LEMBAGA');

        $where_e = '1=1';
        if (count($id_lembaga) > 0) {
            $where_e = '( ';
            foreach ($id_lembaga as $key) {
                $where_e .= 'T_PN_JABATAN.LEMBAGA = "' . $key->LEMBAGA . '" OR ';
            }
            $where_e = substr($where_e, 0, -4) . ')';
        }
        $whereinst = [
            'IS_CURRENT' => '1'
        ];
        $rolesession = explode(',', $this->session->userdata('ID_ROLE'));

        $IS_KPK = 'no';
        $IS_INSTANSI = 'no';
        foreach ($rolesession as $key) {
            $role = $this->mglobal->get_data_all('T_USER_ROLE', NULL, NULL, 'IS_KPK,IS_INSTANSI,IS_USER_INSTANSI', "ID_ROLE= '" . $key . "'")[0];
            if ($role->IS_KPK == '1') {
                $IS_KPK = 'yes';
            }
            if ($role->IS_INSTANSI == '1' || $role->IS_USER_INSTANSI == '1') {
                $IS_INSTANSI = 'yes';
            }
        }

        $pnwl = @$this->mglobal->get_data_all('T_USER', $joinpnwl, $wherepnwl, 'T_USER.USERNAME')[0];
        $instansi = @$this->mglobal->get_data_all('T_USER', $joininst, $whereinst, 'T_PN.ID_PN', $where_e);

        //$execute no atau yes , adalah untuk menjalankan atau tidak menjalankan script nya , biar gak banyak banyak bikin script $temp , $data , sama load nya
        $execute = 'no';

        if ($IS_KPK == 'yes') {
            $execute = 'yes';
        } else {
            if (count($pnwl) > 0) {
                $execute = 'yes';
            }
            if (@in_array($id, @$this->objact(@$instansi)) && $IS_INSTANSI == 'yes') {
                $execute = 'yes';
            }
        }

        if ($execute == 'yes') {
            $tmp = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id], 'FOTO, NAMA, NIK, JNS_KEL, TEMPAT_LAHIR, TGL_LAHIR, NPWP, ALAMAT_TINGGAL, EMAIL, NO_HP')[0];

            $data['data'] = $tmp;
            $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_detail', $data);
        } else {
            echo 'Mohon Maaf , anda tidak memiliki izin untuk melihat data ini !!';
        }
    }

    function ConfrStatusBerkas($id_lhkpn) {
        $data['Judul'] = 'Konfirmasi';
        $data['id_lhkpn'] = $id_lhkpn;
        $this->load->view(strtolower(__CLASS__) . '/' . 'confirmasi_lhkpn', $data);
    }

    function saveChangeStatus() {
        $id_lhkpn = $this->input->post('id_lhkpn');
        $sts = $this->input->post('status');
        $data = array(
            'STATUS' => $sts
        );
        $update = $this->mglobal->update('t_lhkpn', $data, NULL, "ID_LHKPN = '$id_lhkpn'");
        if ($update)
            redirect('#index.php/ever/verification/index/lhkpn');
    }

    public function objact($arrays) {
        $array = '';
        foreach ($arrays as $key => $value) {
            $array[] = $value->ID_PN;
        }
        return $array;
    }

    function primary() {

        $this->db->trans_begin();

        // $ID_PN 		= $this->input->post('pn');
        $ID_JABATAN = $this->input->post('lhkpn');
        $check = $this->input->post('idpn');
        $jabatan = $this->mglobal->get_data_all('T_LHKPN_JABATAN', NULL, NULL, 'ID', "T_LHKPN_JABATAN.ID_LHKPN = '" . $ID_JABATAN . "'");
        // $jabatanpn 	= $this->mglobal->get_data_all('T_PN_JABATAN', NULL, NULL, '*', "T_PN_JABATAN.ID_PN = '".$ID_PN."'");
        // echo $this->db->last_query();
        // echo "<pre>";
        // print_r ($jabatan);
        // echo $check;exit();
        foreach ($jabatan as $key) {
            $data = '';
            if ($key->ID == $check) {
                $data['IS_PRIMARY'] = '1';
            } else {
                $data['IS_PRIMARY'] = '0';
            }

            $result = $this->mglobal->update('T_LHKPN_JABATAN', $data, ['ID' => $key->ID]);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    public function editorialText($id) {
        $this->db->trans_begin();
        $this->load->model('mglobal');

        $data['TEXT_JABATAN_PUBLISH'] = $this->input->post('id_jabatan');
        $result = $this->mglobal->update('T_LHKPN_JABATAN', $data, ['ID = ' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    private function set_msg_kekurangan($hasil_verif, $obj) {
        $array_message = array_filter((array) $hasil_verif->MSG);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('JENISS', 0);
        } else {
            $obj->clone_row('JENISS', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {
                $jenis_name = array_search($key, array_flip($this->jenis_data_lhkpn));

                $template_string_JENIS = 'JENISS#' . $i;

                $template_string_URAIAN = 'URAIAN#' . $i;

                $obj->set_value($template_string_JENIS, $jenis_name);
                $obj->set_value($template_string_URAIAN, $row);
                $i++;
            }
        }
        return FALSE;
    }

    public function previewmsg() {
        $verif = $this->input->post('verif');
        $id = $this->input->post('id_lhkpn');
        $tgl = $this->input->post('tgl_ver');
        $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
        $joinJabatan = [
            ['table' => 'M_INST_SATKER', 'on' => 'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
            ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
        ];
        $data = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN']], NULL, '*', "ID_LHKPN = '$id'")[0];
        $jabatan = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id' AND IS_PRIMARY = '1' ", ['IS_PRIMARY', 'DESC'])[0];

        $hasil_verif = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $id])[0]->HASIL_VERIFIKASI);

        $this->load->library('lwphpword/lwphpword', array(
            "base_path" => APPPATH . "../file/wrd_gen/",
            "base_url" => base_url() . "file/wrd_gen/",
            "base_root" => base_url(),
        ));

        // $this->load->library('ey_barcode');
        // $bc_image_location = $this->ey_barcode->generate($data->NIK, "tes_bc2-" . $id . ".jpg");

        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "callable_model_function" => "insert_cqrcode_with_filename",
            "temp_dir"=>APPPATH."../images/qrcode/"  //hanya untuk production
        ]);

        $show_qr = "'".$data->NIK;
        $qr_file = "tes_qr_new-" . $id . "-" . date('Y-m-d_H-i-s') . ".png";
        $qr_image_location = $this->lws_qr->create($show_qr, $qr_file);


        if ($verif == 'ditolak') {
            $template_file = "../file/template/SuratPenolakan.docx";
            $output_filename = "Surat_Tanda_Terima_LHKPN_" . date('d-F-Y') . ".docx";
        } else if ($verif == 'perbaikan') {
            $template_file = "../file/template/SuratPengantarKelengkapan.docx";
            $output_filename = "Surat_Pengantar_Kelengkapan_LHKPN_" . date('d-F-Y') . ".docx";
        } else if ($verif == 'kekurangan') {
            $template_file = "../file/template/LampiranKekurangan.docx";
            $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y') . ".docx";
        } else {
            $template_file = "../file/template/SuratPengantarTandaTerima.docx";
            $output_filename = "Surat_Tanda_Terima_LHKPN_" . date('d-F-Y') . ".docx";
        }

        if($verif=='kekurangan'){
            /////////////////////////////PDF GENERATOR///////////////////////////
            $data = array(
                "NAMA_LENGKAP" => $data->NAMA,
                "NIK" => $data->NIK,
                "LEMBAGA" => $jabatan->INST_NAMA,
                "JABATAN" => $jabatan->NAMA_JABATAN,
                // "BC_CODE" =>  $bc_image_location,
                "QR_IMAGE_LOCATION"=>$qr_image_location,
                "msg_verifikasi" => $hasil_verif,
             );

            $this->load->library('pdfgenerator');


            $html = $this->load->view('export_pdf/perlu_perbaikan', $data, true);
            $filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y_H-i-s');
            $method = "stream";
            $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');

        }else{
            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.png" => $qr_image_location));
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            $this->lwphpword->set_value("VERIF", $verif == 'lengkap' ? 'lengkap' : 'tidak lengkap');
            $this->lwphpword->set_value("NAMA_LENGKAP", $data->NAMA);
            $this->lwphpword->set_value("NIK", $data->NIK);
            $this->lwphpword->set_value("LEMBAGA", $jabatan->INST_NAMA);
            $this->lwphpword->set_value("JABATAN", $jabatan->NAMA_JABATAN);
            $this->lwphpword->set_value("BIDANG", $jabatan->BDG_NAMA);
            $this->lwphpword->set_value("TGL_VER", $tgl);
            $this->lwphpword->set_value("TANGGAL", $data->JENIS_LAPORAN == '4' ? substr($data->tgl_kirim_final, 0, 4) : tgl_format($data->tgl_kirim_final));

            if ($verif == 'kekurangan') {
                $this->set_msg_kekurangan($hasil_verif, $this->lwphpword);
            }

            $save_document_success = $this->lwphpword->save_document();

            if ($save_document_success) {
                $this->lwphpword->download($save_document_success, $output_filename);
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        }
        // $temp_dir_br = APPPATH."../uploads/barcode/";
        // unlink($temp_dir_br."tes_bc2-" . $id . ".jpg");
        $temp_dir = APPPATH."../images/qrcode/";
        unlink($temp_dir.$qr_file);
    }

    public function getTableCatatan($id) {
        $this->data['tmpData'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $id])[0]->HASIL_VERIFIKASI);
        $veritemnoks = $this->mglobal->get_data_all("T_VERIFICATION_ITEM", NULL, ['ID_LHKPN' => $id, 'HASIL' => '-1']);

        $veritemnoktext = [];
        foreach ($veritemnoks as $veritemnok) {
            if ($veritemnok->ITEMVER == 'hartabergerakperabot') {
                $veritemnok->ITEMVER = 'HARTABERGERAK2';
            }
            $veritemnoktext[strtoupper($veritemnok->ITEMVER)][] = $veritemnok->CATATAN;
        }
        $this->data['veritemnoktext'] = $veritemnoktext;
        $this->data['display'] = 'table';

        echo $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_lhkpn_form', $this->data);
    }

    public function removeFileLampiran() {
        $fnm = $this->input->post('fnm');
        $id_lhkpn = $this->input->get('inpkhl');
        $nik = $this->input->get("ikin");
        $upl = $this->input->get("upl");

        $dir = FALSE;

        if ($nik && $id_lhkpn && $this->input->get("upl") && $this->input->get("upl") == "skm") {
            $dir = self::DIR_SKM_UPLOAD . "$nik/$id_lhkpn/";
        } elseif ($nik && $id_lhkpn && $this->input->get("upl") && $this->input->get("upl") == "sk") {
            $dir = self::DIR_SKUASA_UPLOAD . "$nik/$id_lhkpn/";
        }

        $fpath = $dir.$fnm;

        $response = "0";
        if($dir && is_dir($dir) && file_exists($fpath)){
            $response = unlink($fpath) ? "1" : "0";
        }
        echo $response;
    }

    public function uploadLampiran() {
        $arr_sk = $this->input->post('sk');
        $arr_skm = $this->input->post('skm');
        $id_lhkpn = $this->input->post('inpkhl');

        $data_update = array();

        if ($id_lhkpn) {

//            if ($arr_sk && !is_null($arr_sk)) {
                $data_update["FILE_BUKTI_SK"] = json_encode($arr_sk);
//            }

//            if ($arr_skm && !is_null($arr_skm)) {
                $data_update["FILE_BUKTI_SKM"] = json_encode($arr_skm);
//            }

            if (!is_null($data_update)) {
                $this->mglobal->update('t_lhkpn', $data_update, "id_lhkpn = '" . $id_lhkpn . "'");
            }

            if (!is_NULL($_POST['id_pn'])) {
                $dataFlagSKPN = ['FLAG_SK' => '1'];
                $this->mglobal->update('t_lhkpn_data_pribadi', $dataFlagSKPN, "id_lhkpn = '" . $id_lhkpn . "'");
            } else {
                $dataFlagSKPN = ['FLAG_SK' => '0'];
                $this->mglobal->update('t_lhkpn_data_pribadi', $dataFlagSKPN, "id_lhkpn = '" . $id_lhkpn . "'");
            }

            foreach($_POST['id_kel'] as $lekid) {
                $dataFlagSKKel = ['FLAG_SK' => '1'];
                $this->mglobal->update('t_lhkpn_keluarga', $dataFlagSKKel, "id_keluarga = '" . $lekid . "'", "id_lhkpn = '" . $id_lhkpn . "'");
            }

            if (isset($_POST['id_kel2']) && (is_array($_POST['id_kel2']) || is_object($_POST['id_kel2']))){
                foreach($_POST['id_kel2'] as $lekid2) {
                    $dataFlagSKKel = ['FLAG_SK' => '0'];
                    $this->mglobal->update('t_lhkpn_keluarga', $dataFlagSKKel, "id_keluarga = '" . $lekid2 . "'", "id_lhkpn = '" . $id_lhkpn . "'");
                }
            } 

        }
        echo "1";
        exit;
    }

    public function test_kirim_email($param, $entry_via, $emailCc = NULL)
    {
        $ID_LHKPN = $param;
        $VERIFICATIONS = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$param'", ["ID", "DESC"]);
        $verif = @json_decode($VERIFICATIONS[0]->HASIL_VERIFIKASI);

        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
        if ($entry_via == '1'){
            $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
        }

        $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y_H-i-s') . ".pdf";
        $filename = 'uploads/pdf/' . $datapn->NIK . "/$output_filename";

        if (!file_exists($filename)) {
            $dir = './uploads/pdf/' . $datapn->NIK . '/';

            if (is_dir($dir) === false) {
                mkdir($dir);
            }else{
                chmod($dir, 0755);
                chown($dir, "apache");
                chgrp($dir, "apache");
            }
        }
        //bakal di comment
        // $this->load->library('lwphpword/lwphpword', array(
        //     "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
        //     "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
        //     "base_root" => base_url(),
        // ));

        // $template_file = "../file/template/LampiranKekurangan.docx";
        //tutup comment
        // $this->load->library('ey_barcode');

        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "callable_model_function" => "insert_cqrcode_with_filename",
            "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
        ]);

        $get_nik = $datapn->NIK;
        $get_nama = $datapn->NAMA;
        $get_lembaga = $datapn->INST_NAMA;
        $get_tgl_lapor = $datapn->tgl_lapor;

        // $show_barcode = "'".$get_nik.chr(9).$get_nama;
        // // $show_barcode = $PartNumber."\t".$UIN;

        // $br_image = "tes_bc2-" . $ID_LHKPN . "-" . date('d-F-Y_H-i-s') . ".jpg";
        // $bc_image_location = $this->ey_barcode->generate($show_barcode, $br_image);

        $show_qr = "'".$get_nik.chr(9).$get_nama;
        $qr_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
        $qr_image_location = $this->lws_qr->create($show_qr, $qr_file);

        //bakal di comment
        // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? array("image1.png" => $bc_image_location) : array("image2.jpeg" => $qr_image_location));

        // $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

        // $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA);
        // $this->lwphpword->set_value("NIK", $datapn->NIK);
        // $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
        // $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
        // $this->set_msg_kekurangan($verif, $this->lwphpword);

        // $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
        //tutup comment

        /////////////////////////////PDF GENERATOR///////////////////////////
            $data = array(
                "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                "NIK" => $datapn->NIK,
                "LEMBAGA" => $datapn->INST_NAMA,
                "JABATAN" => $datapn->NAMA_JABATAN,
                // "BC_CODE" =>  $bc_image_location,
                "QR_IMAGE_LOCATION"=>$qr_image_location,
                "msg_verifikasi" => $verif,
             );

            $this->load->library('pdfgenerator');


            $html = $this->load->view('export_pdf/perlu_perbaikan', $data, true);
            $filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y_H-i-s');
            $method = "store";
            $path_pdf = 'uploads/pdf/' . $datapn->NIK . '/';
            $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
            $output_filename = $filename . ".pdf";
            /////////////////////////////TUTUP PDF GENERATOR///////////////////////////

        $message_perbaikan = $VERIFICATIONS[0]->MSG_VERIFIKASI;

        ng::mail_send($datapn->EMAIL, 'Resend : Daftar Kekurangan LHKPN', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, $emailCc);
        $temp_dir = APPPATH."../images/qrcode/";
        unlink($temp_dir.$qr_file);

        // $path_qrcode = 'images/qrcode/'.$qr_file;
        // ng::mail_send_queue($datapn->EMAIL, 'Resend : Daftar Kekurangan LHKPN', $message_perbaikan, NULL, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, $emailCc, NULL, $path_qrcode, NULL, FALSE, FALSE, TRUE);

        echo json_decode(1);
    }

    public function returntovalidation($id_lhkpn)
    {
        $this->load->model('mverification');
        $a = $this->mverification->update_status_lhkpn($id_lhkpn);
        if ($a) {
            $this->mverification->update_status_penerimaan($id_lhkpn);
            $id_imp_xl_lhkpn = $this->mverification->get_id_imp_xl_lhkpn($id_lhkpn);
            $this->mverification->update_status_imp_xl_lhkpn($id_imp_xl_lhkpn);
            $array_response = array(
                            "success" => 1,
                            "msg" => "Berhasil dikembalikan ke Validator"
                        );

        }
        else{
            $array_response = array(
                            "success" => 0,
                            "msg" => "GAGAL dikembalikan ke Validator"
                        );
        }
        $this->to_json($array_response);
    }

    public function preview_tandaterima($ID_LHKPN,$entry_via){
        $this->load->model('Mlhkpn');

        $ID_PN_SESS = $this->session->userdata('ID_PN');
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $ID_PN_NEW = $this->db->get('t_lhkpn')->row()->ID_PN;

        if ($ID_PN_SESS){
            $ID_PN = $ID_PN_SESS;
        }else{
            $ID_PN = $ID_PN_NEW;
        }

        $check = $this->Mlhkpn->get_detail_by_pn($ID_LHKPN, $ID_PN);
        if ($check) {
            $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
            if ($entry_via == '1'){
                $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
            }
            $history = $this->Verification_model->get_history_verification($ID_LHKPN)->DATE_INSERT;

            $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".pdf";
            //$output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".docx";

            $filename_tt = 'uploads/pdf/' . $datapn->NIK;

            if (!is_dir($filename_tt)) {
                    $dir_tt = './uploads/pdf/' . $datapn->NIK . '/';

                    if (is_dir($dir_tt) === false) {
                        mkdir($dir_tt);
                    }else{
                        chmod($dir_tt, 0755);
                        chown($dir_tt, "apache");
                        chgrp($dir_tt, "apache");
                    }
                }

                // $this->load->library('lwphpword/lwphpword', array(
                //     "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
                //     "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
                //     "base_root" => base_url(),
                // ));

                // $template_file = "../file/template/FormatTandaTerima.docx";

                $this->load->library('lws_qr', [
                    "model_qr" => "Cqrcode",
                    "model_qr_prefix_nomor" => "TT-ELHKPN-",
                    "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

                $qr_content_data = json_encode((object) [
                            "data" => [
                                (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
                                (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
                                (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
                                (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
                                (object) ["tipe" => '1', "judul" => "Unit Kerja", "isi" => $datapn->UK_NAMA],
                                (object) ["tipe" => '1', "judul" => "Sub Unit Kerja", "isi" => $datapn->SUK_NAMA],
                                (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor))],
                                (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                                (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                            ],
                            "encrypt_data" => $ID_LHKPN . "tt",
                            "id_lhkpn" => $ID_LHKPN,
                            "judul" => "Tanda Terima E-LHKPN",
                            "tgl_surat" => date('Y-m-d H:i:s'),
                ]);

                $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $ID_LHKPN . ".png");

                // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image2.jpeg" => $qr_image_location));

                // $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

                // $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
                // $this->lwphpword->set_value("LKP", $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ");
                // $this->lwphpword->set_value("TLKP", $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v");
                // $this->lwphpword->set_value("NIK", $datapn->NIK);
                // $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
                // $this->lwphpword->set_value("UK_NAMA", $datapn->UK_NAMA);
                // $this->lwphpword->set_value("SUK_NAMA", $datapn->SUK_NAMA);
                // $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
                // $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
                // $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)));
                // $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));

                // $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
                // $this->lwphpword->download($save_document_success->document_path, $output_filename);


                /////////////////////////////PDF GENERATOR///////////////////////////

                $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
                $thn_kirim = $exp_tgl_kirim[0];

                $data = array(
                    "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                    "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                    "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                    "NIK" => $datapn->NIK,
                    "LEMBAGA" => $datapn->INST_NAMA,
                    "UNIT_KERJA" => $datapn->UK_NAMA,
                    "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                    "JABATAN" => $datapn->NAMA_JABATAN,
                    "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                    "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                    "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                    "qr_code" => $qr_image_location,
                    "TGL_VERIFIKASI" => $history,
                    "TAHUN_KIRIM" => $thn_kirim
                );

                $this->load->library('pdfgenerator');

                $html = $this->load->view('export_pdf/tanda_terima', $data, true);
                $filename = "Tanda_Terima_LHKPN_" . date('d-F-Y');
                $method = "stream";
                $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait');
                /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
                $temp_dir = APPPATH."../images/qrcode/";
                $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
                unlink($temp_dir.$qr_image);
        } else {
            redirect('portal/filing');
        }

    }

    public function kirim_tandaterima($ID_LHKPN,$entry_via) {

//        $this->makses->check_is_write();
//        $this->load->model('muser', '', TRUE);
        $datapn = $this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);

        if ($entry_via == '1'){
            $datapn = $this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
        }

        $data = array(
            'form' => 'kirim_tandaterima',
            'item' => $datapn,
        );
        $data_form = $data;
        $namaform = 'kirim_tandaterima';
        $this->load->view(strtolower(__CLASS__) . '/form_' . $namaform, $data);
//        $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_form', $data);
    }

    public function dokirimtandaterima(){
        $id_lhkpn = $this->input->post('ID_LHKPN');
        $entry_via = $this->input->post('entry_via');
        $datapn = @$this->mglobal->get_detail_pn_lhkpn($id_lhkpn, TRUE, TRUE);

        if ($entry_via == '1'){
            $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($id_lhkpn, TRUE, TRUE);
        }

        $history = $this->Verification_model->get_history_verification($id_lhkpn)->DATE_INSERT;
        $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".pdf";

        $filename_tt = 'uploads/pdf/' . $datapn->NIK;

        if (!is_dir($filename_tt)) {
                $dir_tt = './uploads/pdf/' . $datapn->NIK . '/';

                if (is_dir($dir_tt) === false) {
                    mkdir($dir_tt);
                }else{
                    chmod($dir_tt, 0755);
                    chown($dir_tt, "apache");
                    chgrp($dir_tt, "apache");
                }
            }

            //bakal di comment
            // $this->load->library('lwphpword/lwphpword', array(
            //     "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
            //     "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
            //     "base_root" => base_url(),
            // ));

            // $template_file = "../file/template/FormatTandaTerima.docx";
            //tutup comment

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => "TT-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
                            (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
                            (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
                            (object) ["tipe" => '1', "judul" => "Unit Kerja", "isi" => $datapn->UK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Sub Unit Kerja", "isi" => $datapn->SUK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor))],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                        ],
                        "encrypt_data" => $ID_LHKPN . "tt",
                        "id_lhkpn" => $ID_LHKPN,
                        "judul" => "Tanda Terima E-LHKPN",
                        "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $ID_LHKPN . ".png");

            //bakal di comment
            // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image2.jpeg" => $qr_image_location));

            // $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

            // $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
            // $this->lwphpword->set_value("LKP", $datapn->STATUS == "3" ? "v" : " ");
            // $this->lwphpword->set_value("TLKP", $datapn->STATUS == "3" ? " " : "v");
            // $this->lwphpword->set_value("NIK", $datapn->NIK);
            // $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
            // $this->lwphpword->set_value("UK_NAMA", $datapn->UK_NAMA);
            // $this->lwphpword->set_value("SUK_NAMA", $datapn->SUK_NAMA);
            // $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
            // $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
            // $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)));
            // $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));

            // $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
            //tutup comment

            /////////////////////////////PDF GENERATOR///////////////////////////

            $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
            $thn_kirim = $exp_tgl_kirim[0];

            $data = array(
                "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                "NIK" => $datapn->NIK,
                "LEMBAGA" => $datapn->INST_NAMA,
                "UNIT_KERJA" => $datapn->UK_NAMA,
                "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                "JABATAN" => $datapn->NAMA_JABATAN,
                "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                "qr_code" => $qr_image_location,
                "TGL_VERIFIKASI" => $history,
                "TAHUN_KIRIM" => $thn_kirim
            );

            $this->load->library('pdfgenerator');

            $html = $this->load->view('export_pdf/tanda_terima', $data, true);
            $filename = "Tanda_Terima_LHKPN_" . date('d-F-Y');
            $method = "store";
            $path_pdf = 'uploads/pdf/' . $datapn->NIK . '/';
            $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
            /////////////////////////////TUTUP PDF GENERATOR///////////////////////////
            $temp_dir = APPPATH."../images/qrcode/";
            $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
            unlink($temp_dir.$qr_image);

        $message_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; ' . date('Y') . ' Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';
           $message_tidak_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Tidak Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau telepon nomor 021-2557 8396.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN (021) 2557 8396
                                                 </td>
                                            </tr>
                                            </table>';
        if ($save_document_success){
           if ($datapn->STATUS == "5"){
               ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
           }else{
               ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
           }
           unlink($path_pdf.$output_filename);
           echo '1';
        }

    }

    public function SKUncomplete($id_lhkpn){
        $this->load->model('mglobal', '', TRUE);
        $where = array('ID_LHKPN' => $id_lhkpn,'FLAG_SK' => '0');
        $sk = $this->mglobal->get_data_all('t_lhkpn_data_pribadi',null,$where,'id,nama_lengkap');
        $res = array();
        $no = 1;

        foreach ($sk as $row) {
            $res[] = array('no' => $no, 'id' => $row->id, 'name' => $row->nama_lengkap.' (Penyelenggara Negara)');
            $no++;
        }

        $select_keluarga = 'id_keluarga,nama,hubungan,TIMESTAMPDIFF(YEAR,tanggal_lahir,tgl_lapor) AS umur_lapor';
        $where_keluarga = array('t_lhkpn_keluarga.ID_LHKPN' => $id_lhkpn,'HUBUNGAN <' => '4','FLAG_SK' => '0');
        $sk_keluarga = $this->mglobal->get_data_all('t_lhkpn_keluarga',[['table' => 't_lhkpn', 'on' => 't_lhkpn_keluarga.id_lhkpn = t_lhkpn.id_lhkpn', 'join' => 'left']],$where_keluarga,[$select_keluarga,false]);
        foreach ($sk_keluarga as $row) {
            if ($row->umur_lapor < 17) { continue; }
            $arr_hubungan = ['1' => 'Istri', '2' => 'Suami', '3' => 'Anak Tanggungan'];
            $res[] = array('no' => $no, 'id' => $row->id_keluarga, 'name' => $row->nama." (". $arr_hubungan[$row->hubungan] .")");
            $no++;
        }
        echo json_encode($res);
    }

    public function SBUncomplete($id_lhkpn){
        $this->load->model('mglobal', '', TRUE);
        $where = array('t_lhkpn_harta_surat_berharga.ID_LHKPN' => $id_lhkpn, 't_lhkpn_harta_surat_berharga.IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0', 'FILE_BUKTI' => NULL);
        $join = [
            ['table' => 'T_LHKPN', 'on' => 't_lhkpn_harta_surat_berharga.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
        ];
        $sb = $this->mglobal->get_data_all('t_lhkpn_harta_surat_berharga',$join,$where,'nama_penerbit, custodian, nomor_rekening, atas_nama, pasangan_anak, ket_lainnya, T_PN.nama');
        $res = array();
        $no = 1;

        foreach ($sb as $row) {
            if (strlen($row->nomor_rekening) >= 32){
                $decrypt_norek = encrypt_username($row->nomor_rekening,'d');
            } else {
                $decrypt_norek = $row->nomor_rekening;
            }

            $get_atas_nama = $row->atas_nama;
            $atas_nama = '';
            $get_atas_nama = check_atas_nama($get_atas_nama);
            if(strstr($get_atas_nama, "5")){
                $atas_nama = substr($get_atas_nama,2);
            }
            if(strstr($get_atas_nama, "1")){
                $atas_nama = 'PN YANG BERSANGKUTAN ('.$row->nama.')';
            }
            if(strstr($get_atas_nama, "2")){
                $pasangan_array = explode(',', $row->pasangan_anak);
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
                    $atas_nama = $atas_nama.'LAINNYA ('.$row->ket_lainnya.')';
                }else{
                    $atas_nama = $atas_nama.', LAINNYA ('.$row->ket_lainnya.')' ;
                }
            }

            // $res[] = array('no' => $no, 'penerbit' => $row->nama_penerbit, 'custodian' => $row->custodian, 'norek' => $decrypt_norek, 'atas_nama' => map_data_atas_nama($row->atas_nama));
            $res[] = array('no' => $no, 'penerbit' => $row->nama_penerbit, 'custodian' => $row->custodian, 'norek' => $decrypt_norek, 'atas_nama' => $atas_nama);
            $no++;
        }
        echo json_encode($res);
    }

    public function KasUncomplete($id_lhkpn){
        $this->load->model('mglobal', '', TRUE);
        $where = array('a.ID_LHKPN' => $id_lhkpn, 'a.IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0', 'FILE_BUKTI' => NULL, 'KODE_JENIS <>' => '18');
        $join = [
            ['table' => 'm_jenis_harta b', 'on' => 'a.kode_jenis = b.id_jenis_harta'],
            ['table' => 'T_LHKPN', 'on' => 'a.ID_LHKPN = T_LHKPN.ID_LHKPN'],
            ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
        ];
        $sb = $this->mglobal->get_data_all('t_lhkpn_harta_kas a',$join,$where,'b.nama, nama_bank, nomor_rekening, atas_nama_rekening as atas_nama, pasangan_anak, atas_nama_lainnya as ket_lainnya, t_pn.nama as nama_lengkap');
        $res = array();
        $no = 1;

        foreach ($sb as $row) {
            if (strlen($row->nama_bank) >= 32){
                $decrypt_nama_bank = encrypt_username($row->nama_bank,'d');
            } else {
                $decrypt_nama_bank = $row->nama_bank;
            }
            if (strlen($row->nomor_rekening) >= 32){
                $decrypt_norek = encrypt_username($row->nomor_rekening,'d');
            } else {
                $decrypt_norek = $row->nomor_rekening;
            }

            $get_atas_nama = $row->atas_nama;
            $atas_nama = '';
            $get_atas_nama = check_atas_nama($get_atas_nama);
            if(strstr($get_atas_nama, "5")){
                $atas_nama = substr($get_atas_nama,2);
            }
            if(strstr($get_atas_nama, "1")){
                $atas_nama = 'PN YANG BERSANGKUTAN ('.$row->nama_lengkap.')';
            }
            if(strstr($get_atas_nama, "2")){
                $pasangan_array = explode(',', $row->pasangan_anak);
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
                    $atas_nama = $atas_nama.'LAINNYA ('.$row->ket_lainnya.')';
                }else{
                    $atas_nama = $atas_nama.', LAINNYA ('.$row->ket_lainnya.')' ;
                }
            }

            // $res[] = array('no' => $no, 'jenis' => $row->nama, 'nama_bank' => $decrypt_nama_bank, 'norek' => $decrypt_norek, 'atas_nama' => map_data_atas_nama($row->atas_nama_rekening));
            $res[] = array('no' => $no, 'jenis' => $row->nama, 'nama_bank' => $decrypt_nama_bank, 'norek' => $decrypt_norek, 'atas_nama' => $atas_nama);
            $no++;
        }
        echo json_encode($res);
    }

    public function infolhkpn($type = '', $id = '') {
        $this->data['tbl'] = 'T_LHKPN';
        $this->data['pk'] = 'ID_LHKPN';
        $this->data['role'] = urlencode($this->config->item('LHKPNOFFLINE_PAGE_ROLE')['verification']);

        if ($type == 'reff') {
            if ($this->display == 'infoLHKPN') {
                $this->load->model('mlhkpn', '', TRUE);
                $this->load->model('mlhkpnkeluarga');

                $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, NULL, '*', "ID_LHKPN = '$id'")[0] : '';
                $this->data['icon'] = 'fa-book';
                $this->data['title'] = 'LHKPN';
                $upperli = $this->input->get('upperli');
                $bottomli = $this->input->get('bottomli');
                $this->data['upperli'] = $upperli ? $upperli : 'li1';
                $this->data['bottomli'] = $bottomli ? $bottomli : FALSE;
                unset($upperli, $bootomli);
                $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
                $breadcrumbitem[] = [ucwords(strtolower(__CLASS__)) => $this->segmentTo[2]];
                $breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
                $breadcrumbdata = [];
                foreach ($breadcrumbitem as $list) {
                    $breadcrumbdata = array_merge($breadcrumbdata, $list);
                }
                $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);

                $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID_LHKPN = '$id' and IS_ACTIVE =1");
                $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'], ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'T_LHKPN_DATA_PRIBADI.ID_LHKPN']], NULL, '*,T_PN.NIK as NIK_PN', "T_LHKPN.ID_LHKPN = '$id'")[0];
                $this->data['tmpData'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN =' => $id])[0]->HASIL_VERIFIKASI);
                $user = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', null, ['IS_ACTIVE' => '1', 'ID_LHKPN =' => $id], 'USERNAME, UPDATED_BY')[0];
                $this->data['pic1'] = @$this->mglobal->get_data_all('T_USER', null, ['IS_ACTIVE' => '1', 'USERNAME' => $user->USERNAME], 'NAMA')[0];
                $this->data['pic2'] = @$this->mglobal->get_data_all('T_USER', null, ['IS_ACTIVE' => '1', 'USERNAME' => $user->UPDATED_BY], 'NAMA')[0];
                $this->data['tmpAPI'] = $this->mglobal->get_data_all('T_LHKPN_AI',NULL,"ID_LHKPN = '$id' ");
                $cek = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, 'KD_ISO3_NEGARA', "ID_LHKPN = '$id'")[0];
                $joinDATA_PRIBADI = [];
                $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
                if (@$cek->KD_ISO3_NEGARA == '') {
                    $joinDATA_PRIBADI = [
                        ['table' => 'M_AREA', 'on' => 'M_AREA.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI AND M_AREA.IDKOT = CAST(T_LHKPN_DATA_PRIBADI.KABKOT AS UNSIGNED) AND M_AREA.IDKEC = T_LHKPN_DATA_PRIBADI.KECAMATAN AND M_AREA.IDKEL = ""'],
                        ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
                    ];
                    $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
                } else {
                    $joinDATA_PRIBADI = [
                        ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.KODE_ISO3 = T_LHKPN_DATA_PRIBADI.KD_ISO3_NEGARA'],
                        ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_DATA_PRIBADI.ID_LHKPN'],
                    ];
                    $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*,M_NEGARA.NAMA_NEGARA as KD_ISO3_NEGARA, T_LHKPN.*';
                }

                $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', $joinDATA_PRIBADI, NULL, $selectDATA_PRIBADI, "T_LHKPN_DATA_PRIBADI.ID_LHKPN = '$id'")[0];
                $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
                $joinJabatan = [
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
                    ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
                    ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
                ];
                $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id' ", ['IS_PRIMARY', 'DESC']);
                $this->data['JABATANS_P'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id' AND IS_PRIMARY = '1' ", ['IS_PRIMARY', 'DESC']);
                $this->data['ID_LHKPN'] = $id;
                $sql_jabatan_lhkpn = "SELECT NAMA_JABATAN FROM M_JABATAN JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN WHERE T_LHKPN_JABATAN.ID_LHKPN = ( SELECT ID_LHKPN FROM T_LHKPN WHERE T_LHKPN.ID_LHKPN = '$id' )";
                $this->data['JABATAN_LHKPN'] = $this->db->query($sql_jabatan_lhkpn)->result();

                $id_pn = $this->data['LHKPN']->ID_PN;

                //////sampai sini//////////////////
                $this->data['log_file_bukti_sk'] = $this->mglobal->get_data_all('T_LHKPN', NULL, NULL, '*', "ID_PN = '$id_pn' and IS_ACTIVE =1", ['ID_LHKPN', 'ASC']);
                //////////////////////////////

                $joinJabatan = [
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
                    ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
                ];
                $this->data['JABATANSPN'] = $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "T_PN_JABATAN.ID_PN = '$id_pn' AND IS_CURRENT = 1");
                $this->data['ID_PN'] = $id_pn;

                //jenis bukti
                $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, ['ID_JENIS_BUKTI', 'DESC']);
                $list_bukti = [];
                foreach ($jenis_bukti as $key) {
                    $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
                }

                $this->data['list_bukti'] = $list_bukti;
                $entry_via_new = '';
                if ($this->data['LHKPN']->entry_via == '0'){
                    $entry_via_new = null;
                }
                else{
                    $entry_via_new = 1;
                }
                $this->data['list_bukti_alat_transportasi'] = $this->get_list_bukti('2', $entry_via_new, ['ID_JENIS_BUKTI', 'ASC']);

                $agenda = date('Y', strtotime($this->data['LHKPN']->tgl_lapor)) . '/' . ($this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['LHKPN']->NIK . '/' . $this->data['LHKPN']->ID_LHKPN;
                $nama = (@$this->data['DATA_PRIBADI']->NAMA_LENGKAP != '' ? $this->data['DATA_PRIBADI']->NAMA_LENGKAP : $this->data['LHKPN']->NAMA);
                $nik = (@$this->data['DATA_PRIBADI']->NIK != '' ? $this->data['DATA_PRIBADI']->NIK : $this->data['LHKPN']->NIK);

                /////////////////////////////////// DATA FROM ELO ///////////////////////////////////
                $this->load->helper('elo_helper');
                $key = $this->config->item('KPKkey');
                $LoginAs = '';
                $ELOuser = $this->config->item('ELOUser');
                $ELOpass = $this->config->item('ELOPass');
                $IXURL = $this->config->item('IXURL');
                $ELOws = $this->config->item('ELOws');
                $result_elo = FindELODoc('Basic Entry', $nik, "", "", "","", $LoginAs,$ELOuser,$ELOpass,$IXURL,$ELOws);
                $put_elo = [];
                foreach($result_elo as $r){
                    $r['URL'] = GetDocURL($r['ID'],$LoginAs,$ELOuser,$ELOpass,$IXURL,$ELOws);
                    array_push($put_elo,$r);
                }
                $this->data['data_sk_elo'] = json_encode($put_elo);
                /////////////////////////////////// DATA FROM ELO ///////////////////////////////////

                $jen_lap = $this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus';
                $tgl_lap = $this->data['LHKPN']->JENIS_LAPORAN == '4' ? substr($this->data['LHKPN']->tgl_lapor, 0,4) : tgl_format($this->data['LHKPN']->tgl_lapor);
                $this->data['tampil'] = 'LHKPN : <b>' . $nama . '</b> (' . $nik . ') - ' . $agenda;
                $this->data['tampil3'] = 'Jenis Laporan : ' . $jen_lap . ', Tanggal/Tahun Laporan : ' . $tgl_lap ;
                $this->data['tampil2'] = $nama . ' (' . $nik . ')';

                $diff = (count($this->data['JABATANSPN']) == count($this->data['JABATANSPN']) ? true : false);
                if ($diff) {
                    $diff = pembanding($this->data['JABATANSPN'], $this->data['JABATANS']);
                }

                $tmp = [
                    'status' => $diff
                ];

                if ($diff == false) {
                    $idLembaga = implode("','", array_unique(array_merge(array_column(json_decode(json_encode($this->data['JABATANSPN']), true), 'LEMBAGA'), array_column(json_decode(json_encode($this->data['JABATANS']), true), 'LEMBAGA'))));
                    $lembaga = array_unique(array_merge(array_column(json_decode(json_encode($this->data['JABATANSPN']), true), 'INST_NAMA'), array_column(json_decode(json_encode($this->data['JABATANS']), true), 'INST_NAMA')));
                    $tmp['lembaga'] = $lembaga;
                    $email = $this->mglobal->get_data_all(
                            'T_USER', [
                        ['table' => 'T_USER_ROLE', 'on' => 'T_USER.ID_ROLE=T_USER_ROLE.ID_ROLE']
                            ], NULL, 'ID_USER, EMAIL', "INST_SATKERKD IN ('$idLembaga') AND (IS_INSTANSI = '1' OR IS_USER_INSTANSI = '1')");
                    $tmp['email'] = array_column(json_decode(json_encode($email), true), 'EMAIL');
                    $tmp['id'] = array_column(json_decode(json_encode($email), true), 'ID_USER');
                }

                $this->data['diffJabatan'] = $tmp;
                $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
                $this->data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
                $this->data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);

//                jenis bukti
                $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI', NULL, ['ID_JENIS_BUKTI', 'DESC']);
                $list_bukti = [];
                foreach ($jenis_bukti as $key) {
                    $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
                }
                //jenis Harta
                $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA', NULL, ['ID_JENIS_HARTA', 'DESC']);
                $list_harta = [];
                foreach ($jenis_HARTA as $key) {
                    $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
                }
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
                $this->data['list_harta'] = $list_harta;
                $this->data['list_bukti'] = $list_bukti;
                $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian2("ID_LHKPN = '$id'");
                $this->data['lhkpn_ver'] = $this->mlhkpnkeluarga->get_lhkpn_version($id);
                $this->data['KELUARGAS'] = $this->Verification_model->get_keluarga_by_id_lhkpn($id);

                $all_keluarga =  $this->Verification_model->get_keluarga_by_id_lhkpn($id);
                $data_keluarga = array();
                foreach($all_keluarga as $item) {
                    if($item->NIK){
                        $cek_pn = $this->db->query("SELECT COUNT(id_pn) AS numrows, id_pn FROM t_pn WHERE nik = '".$item->NIK."'");
                        if($cek_pn->result()[0]->numrows > 0){$pn = 1;}
                        else{$pn = 0;}
                    }
                    else{$pn = 0;}
                    $new_array = (object) [
                        "ID_KELUARGA"=> $item->ID_KELUARGA,
                        "NAMA"=> $item->NAMA,
                        "HUBUNGAN"=> $item->HUBUNGAN,
                        "FLAG_SK"=> $item->FLAG_SK,
                        "PN"=> $pn,
                        "umur_lapor"=> $item->umur_lapor
                    ];
                    array_push( $data_keluarga,$new_array);
                }
                $this->data['data_keluarga'] = $data_keluarga;
                $joinHARTA_TIDAK_BERGERAK = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
                    ['table' => 'M_AREA_KAB as kabkot', 'on' => 'kabkot.NAME_KAB = data.KAB_KOT', 'join' => 'left'],
                    ['table' => 'M_AREA_PROV as provinsi', 'on' => 'provinsi.NAME = data.PROV', 'join' => 'left']
                ];
                $KABKOT = "(SELECT NAME_KAB FROM M_AREA_KAB as area WHERE data.KAB_KOT = area.NAME_KAB AND area.IS_ACTIVE = 1) as KAB_KOT";
                $PROV = "(SELECT NAME FROM M_AREA_PROV as area WHERE data.PROV = area.NAME) as PROV";
                $selectHARTA_TIDAK_BERGERAK = 'DISTINCT IS_CHECKED, data.NEGARA AS ID_NEGARA, data.PASANGAN_ANAK, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL, KAB_KOT, PROV, data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
                $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], "ID_LHKPN = '$id' and  data.IS_ACTIVE =1 order by ID desc");
                $joinMATA_UANG = [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
                ];
                $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_BERGERAK.IS_ACTIVE = 1 order by ID desc");
                $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_BERGERAK_LAIN.IS_ACTIVE = 1 ");
                $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'  and T_LHKPN_HARTA_SURAT_BERHARGA.IS_ACTIVE = 1 order by ID desc ");
                $this->data['SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 't_lhkpn_data_pribadi.ID_LHKPN  = T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN']
                ], NULL, '*', "T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN = '$id'");
                $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HARTA_KAS.IS_ACTIVE = 1 order by ID desc ");
                $this->data['HARTA_SETARA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', [
                    ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                    ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 't_lhkpn_data_pribadi.ID_LHKPN  = T_LHKPN_HARTA_KAS.ID_LHKPN']
                ], NULL, '*', "T_LHKPN_HARTA_KAS.ID_LHKPN = '$id'");
                $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$id'  and T_LHKPN_HARTA_LAINNYA.IS_ACTIVE = 1 ");
                $joinHUTANG = [
                    ['table' => 'm_jenis_hutang', 'on' => 'KODE_JENIS  = ID_JENIS_HUTANG']
                ];
                $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', $joinHUTANG, NULL, '*', "ID_LHKPN = '$id' and T_LHKPN_HUTANG.IS_ACTIVE = 1 order by ID_HUTANG desc");
                $this->data['PENERIMAAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$id'");
                $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$id'");

                $this->data['getPemka'] = current($this->data['PENERIMAAN_KASS']);
                $this->data['getPenka'] = current($this->data['PENGELUARAN_KASS']);

                $this->data['lampiran_hibah'] = $this->_lampiran_hibah($this->data['LHKPN']->ID_LHKPN);
                $this->data['lampiran_pelepasan'] = $this->_lampiran_pelepasan($this->data['LHKPN']->ID_LHKPN);
                $this->config->load('harta');
                $this->data["jenis_penerimaan_kas_pn"] = $this->config->item('jenis_penerimaan_kas_pn', 'harta');
                $this->data["golongan_penerimaan_kas_pn"] = $this->config->item('golongan_penerimaan_kas_pn', 'harta');
                $this->data["jenis_pengeluaran_kas_pn"] = $this->config->item('jenis_pengeluaran_kas_pn', 'harta');
                $this->data["golongan_pengeluaran_kas_pn"] = $this->config->item('golongan_pengeluaran_kas_pn', 'harta');
                $this->data["lhkpn_offline_melalui"] = array_flip($this->config->item('lhkpn_offline_melalui', 'harta'));

                ///////////////////////////////////evan summary///////////////////////////////////
                $summary_harta = $this->get_summary_harta($id);
                $this->data = array_merge($summary_harta, $this->data);

            }
        }
    }

    /*
    function pesan_pdf_verif_cepat($ID_LHKPN, $TGL_VER, $MSG_VERIFIKASI_ALASAN) {

        $this->db->trans_begin();

        $this->load->model('mlhkpn', '', TRUE);
        $entry_via = $this->mlhkpn->get_by_id($ID_LHKPN)->result()[0]->entry_via;

        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
        $usernames = $datapn->USERNAME;
        $idRoles = $datapn->ID_ROLE;
        if ($entry_via == '1'){
            $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
        }
        
        $history = $this->Verification_model->get_history_verification($ID_LHKPN)->DATE_INSERT;
        $penugas = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENUGASAN_VERIFIKASI', NULL, ['ID_LHKPN' => $ID_LHKPN], 'UPDATED_BY')[0];
        $petugas = @$this->mglobal->get_data_all('T_USER', NULL, ['USERNAME' => $penugas->UPDATED_BY], 'NAMA, ID_ROLE')[0];
        $role = @$this->mglobal->get_data_all('t_user_role', NULL, ['ID_ROLE' => $petugas->ID_ROLE], 'ROLE, DESCRIPTION')[0];
        $verif = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN])[0]->HASIL_VERIFIKASI);

        $arr_condition_verif_isian = array(
            "DATAPRIBADI",
            "JABATAN",
            "KELUARGA",
            "HARTATIDAKBERGERAK",
            "HARTABERGERAK",
            "HARTABERGERAK2",
            "SURATBERHARGA",
            "KAS",
            "HARTALAINNYA",
            "HUTANG",
            "PENERIMAANKAS",
            "PENGELUARANKAS",
            "PELEPASANHARTA",
            "PENERIMAANHIBAH",
            "PENERIMAANFASILITAS"
        );

        $verif_isian_ok = FALSE;
        foreach ($arr_condition_verif_isian as $val_property) {
            if ($verif->VAL->{$val_property} == "-1") {
                $verif_isian_ok = TRUE;
            }
        }

        if ($datapn->STATUS == 2) {
            $isi_pesan = $MSG_VERIFIKASI_ALASAN;
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara belum lengkap, hubungi call center 198", "idModem":6}';
        } else if ($datapn->STATUS == 7) {
            $isi_pesan = 'Yth. Sdr. ' . $datapn->NAMA . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa pelaporan LHKPN atas nama Saudara setelah dilakukan verifikasi administratif dinyatakan ditolak dikarenakan tidak memenuhi kriteria yang telah ditetapkan dalam pelaporan LHKPN.<br><br>Sehubungan dengan hal tersebut silakan mengisi dan menyampaikan LHKPN sesuai petunjuk pengisian kepada Komisi Pemberantasan Korupsi dalam waktu tidak melampaui tanggal ' . $TGL_VER . '.<br><br>Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198';
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara ditolak, hubungi call center 198 untuk keterangan lebih lanjut", "idModem":6}';
            //            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
        } else if ($datapn->STATUS == 5) {
            $isi_pesan = '<center><b>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</b><br>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12920<br><br><b>TANDA TERIMA PENYERAHAN FORMULIR LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</b></center><br><br><table style="width: 100%;"><tr><td width="105px">Atas Nama</td><td width="10px">:</td><td>' . $datapn->NAMA . '</td></tr><tr><td>Jabatan</td><td>:</td><td>' . $datapn->NAMA_JABATAN . '</td></tr><tr><td>Bidang</td><td>:</td><td>' . $datapn->BDG_NAMA . '</td></tr><tr><td>Lembaga</td><td>:</td><td>' . $datapn->INST_NAMA . '</td></tr><tr><td>Tahun Pelaporan</td><td>:</td><td>' . date('Y', strtotime($datapn->tgl_lapor)) . '</td></tr></table><br><br><div style="text-align: right;">Jakarta, ' . date('d F Y') . '</div>';
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara Terverifikasi Tidak Lengkap dan segera diumumkan", "idModem":6}';
            //            CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
        } else { 
            $curl_data = 'SEND={"tujuan":"' . $datapn->NO_HP . '","isiPesan":"LHKPN Saudara telah Lengkap dan segera diumumkan", "idModem":6}';
            $isi_pesan = '<center><b>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</b><br>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12920<br><br><b>TANDA TERIMA PENYERAHAN FORMULIR LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</b></center><br><br><table style="width: 100%;"><tr><td width="105px">Atas Nama</td><td width="10px">:</td><td>' . $datapn->NAMA . '</td></tr><tr><td>Jabatan</td><td>:</td><td>' . $datapn->NAMA_JABATAN . '</td></tr><tr><td>Bidang</td><td>:</td><td>' . $datapn->BDG_NAMA . '</td></tr><tr><td>Lembaga</td><td>:</td><td>' . $datapn->INST_NAMA . '</td></tr><tr><td>Tahun Pelaporan</td><td>:</td><td>' . date('Y', strtotime($datapn->tgl_lapor)) . '</td></tr></table><br><br><div style="text-align: right;">Jakarta, ' . date('d F Y') . '</div>';
        }

        if ($datapn->STATUS == 2) {
            $subjek = 'Daftar Kekurangan LHKPN';
        }else{
            $subjek = 'Tanda Terima ( Verifikasi )';
        }

        $pengirim = array(
            'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
            'ID_PENERIMA' => $datapn->ID_USER,
            'SUBJEK' => $subjek,
            'PESAN' => $isi_pesan,
            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
            'IS_ACTIVE' => '1',
            'ID_LHKPN' => $ID_LHKPN
        );

        $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);
        

        if ($kirim) {

            $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".pdf";
            if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y') . ".pdf";
            }

            $penerima = array(
                'ID_PENGIRIM' => 1, //$this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $datapn->ID_USER,
                'SUBJEK' => $subjek,
                'PESAN' => $isi_pesan,
                'FILE' => "../../../uploads/pdf/" . $datapn->NIK . '/' . $output_filename,
                'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1',
                'ID_LHKPN' => $ID_LHKPN
            );

            $this->mglobal->insert('T_PESAN_MASUK', $penerima);

            // create file
            $time = time();
            $dataPDF = array(
                'NAMA' => $datapn->NAMA,
                'JABATAN' => $datapn->NAMA_JABATAN,
                'BDG_NAMA' => $datapn->BDG_NAMA,
                'LEMBAGA' => $datapn->INST_NAMA,
                'STATUS' => $datapn->STATUS,
                'LAPOR' => date('Y', strtotime($datapn->TGL_LAPOR)),
                'PETUGAS' => $petugas->NAMA,
                'TUGAS_PETUGAS' => $role->ROLE
            );

//            $th = date('Y');

            $filename = 'uploads/pdf/' . $datapn->NIK . "/$output_filename";

            if (!file_exists($filename)) {
                $dir = './uploads/pdf/' . $datapn->NIK . '/';

                if (is_dir($dir) === false) {
                    mkdir($dir);
                }else{
                    chmod($dir, 0755);
                    chown($dir, "apache");
                    chgrp($dir, "apache");
                }
            }

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "model_qr_prefix_nomor" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "LK-ELHKPN-" : "TT-ELHKPN-",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
            ]);

            //$this->load->library('ey_barcode');

            
            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
                            (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
                            (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
                            (object) ["tipe" => '1', "judul" => "Unit Kerja", "isi" => $datapn->UK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Sub Unit Kerja", "isi" => $datapn->SUK_NAMA],
                            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor))],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                        ],
                        "encrypt_data" => $ID_LHKPN . "tt",
                        "id_lhkpn" => $ID_LHKPN,
                        "judul" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "Lampiran Kekurangan E-LHKPN" : "Tanda Terima E-LHKPN",
                        "tgl_surat" => date('Y-m-d'),
            ]);
            $qr_image_location = $this->lws_qr->create('$qr_content_data',"tes_qr2-" . $ID_LHKPN . ".png");
            
            $get_nik = $datapn->NIK;
            $get_nama = $datapn->NAMA;
        

            
            //$show_barcode = "'".$get_nik.chr(9).$get_nama;

            //$bc_image_location = $this->ey_barcode->generate($show_barcode, "tes_bc2-" . $ID_LHKPN . ".jpg");

            $show_qr2 = "'".$get_nik.chr(9).$get_nama;
            $qr2_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr2_image_location = $this->lws_qr->create($show_qr2, $qr2_file);
        
            if ($datapn->STATUS == "7") {
            }else{

                $data = array(
                    "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                    "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                    "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                    "NIK" => $datapn->NIK,
                    "LEMBAGA" => $datapn->INST_NAMA,
                    "UNIT_KERJA" => $datapn->UK_NAMA,
                    "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                    "JABATAN" => $datapn->NAMA_JABATAN,
                    "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                    "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                    "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                    // "qr_code" => $qr_image_location,
                    "TGL_VERIFIKASI" => $history,
                );

                $this->load->library('pdfgenerator');

                if (($datapn->STATUS == "2" || $datapn->STATUS == "7") && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                    $data = array(
                        "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                        "NIK" => $datapn->NIK,
                        "LEMBAGA" => $datapn->INST_NAMA,
                        "JABATAN" => $datapn->NAMA_JABATAN,
                        // "QR_IMAGE_LOCATION" =>  $qr2_image_location,
                        "msg_verifikasi" => $verif,
                    );
                    $html = $this->load->view('export_pdf/perlu_perbaikan', $data, true);
                    $filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y');
                }else{
                    $exp_tgl_kirim = explode('-', $datapn->tgl_kirim_final);
                    $thn_kirim = $exp_tgl_kirim[0];

                    $data = array(
                        "NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                        "LKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? "v" : " ",
                        "TLKP" => $datapn->STATUS == "3" || $datapn->STATUS == "4" ? " " : "v",
                        "NIK" => $datapn->NIK,
                        "LEMBAGA" => $datapn->INST_NAMA,
                        "UNIT_KERJA" => $datapn->UK_NAMA,
                        "SUB_UNIT_KERJA" => $datapn->SUK_NAMA,
                        "JABATAN" => $datapn->NAMA_JABATAN,
                        "JENIS" => $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus',
                        "KHUSUS" => show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_lapor, tgl_format($datapn->tgl_lapor)),
                        "TANGGAL" => tgl_format($datapn->tgl_kirim_final),
                        "qr_code" => $qr_image_location,
                        "TGL_VERIFIKASI" => $history,
                        "TAHUN_KIRIM" => $thn_kirim
                    );
                    $html = $this->load->view('export_pdf/tanda_terima', $data, true);
                    $filename = "Tanda_Terima_LHKPN_" . date('d-F-Y');
                }

                $method = "store";
                $path_pdf = 'uploads/pdf/' . $datapn->NIK . '/';
                $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'portrait',$path_pdf);
                $output_filename = $filename . ".pdf";

                $temp_dir = APPPATH."../images/qrcode/";
                $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
                unlink($temp_dir.$qr_image);
                unlink($temp_dir.$qr2_file);
                //$temp_dir_br = APPPATH."../uploads/barcode/";
                //$br_image = "tes_bc2-" . $ID_LHKPN . ".jpg";
                //unlink($temp_dir_br.$br_image);

                if(switchMinio()){
                    $resultMinio = uploadMultipleToMinio($path_pdf.$output_filename,'application/pdf',$output_filename,$path_pdf);
                    if($resultMinio){
                        // File asli masih dipakai untuk kirim email
                        // $deleteLocal = unlink($path_pdf.$output_filename);
                    }
                }
            }
            //tutup comment

            $message_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

            $message_tidak_lengkap = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   Di Tempat<br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                 <td>
                                Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan <b>Tidak Lengkap</b> dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                 </td>
                            </tr>
                        </table>
                        <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                            <tbody class="body-table">

                                            <tr>
                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->NAMA_LENGKAP . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                <td width="5%" valign="top"><b>:</td>
                                                <td >' . $datapn->NAMA_JABATAN . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->BDG_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . $datapn->INST_NAMA . '</td>
                                            </tr>
                                                                                                    <tr>
                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                <td width="5%" valign="top"><b>:</b></td>
                                                <td>' . substr($datapn->tgl_lapor, 0, 4) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                            <table>
                                             <tr>
                                                 <td>
                                                    Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br/>
                                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                                                    Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                    --------------------------------------------------------------<br/>
                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                    &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                 </td>
                                            </tr>
                                            </table>';

            $message_perbaikan = '<table>
                           <tr>
                                <td>
                                   Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br/>
                                   ' . $datapn->INST_NAMA . '<br/>
                                   di Tempat<br/><br/>
                                </td>
                           </tr>
                        </table>
                        <table>
                             <tr>
                                Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal ' . $TGL_VER . '.<br><br>
                                Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK.<br><br>
                                Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN.<br><br>
                                Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>
                            </tr>
                        </table>
                        <table>
                         <tr>
                             <td>
                                Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                --------------------------------------------------------------<br/>
                                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>
                                &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                             </td>
                        </tr>
                        </table>';

            $admin = $this->mglobal->get_data_all('T_USER', null, ['USERNAME = ' => 'admin_kpk'], 'ID_USER, NAMA,EMAIL')[0];

            //if ($datapn->STATUS == "7") {
            //    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //} else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
            //    ng::mail_send($datapn->EMAIL, 'Daftar Kekurangan LHKPN', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //} else if ($datapn->STATUS == "5") {
            //    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //} else {
            //    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            //}
            //unlink($path_pdf.$output_filename);
            $this->db->trans_commit();
            if ($datapn->STATUS == "7") {
                ng::mail_send($datapn->EMAIL, 'Dikembalikan ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/async/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Status+e-LHKPN+Saudara+Ditolak,+Silahkan+melaporkan+e-LHKPN+kembali+melalui+elhkpn.kpk.go.id+%0aInfo+:+elhkpn@kpk.go.id/02125578396&msisdn='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Bapak/Ibu%20Dikembalikan%20ke%20Draft,%20silahkan%20melaporkan%20e-LHKPN%20kembali%20melalui%20elhkpn.kpk.go.id%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles,$datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Dikembalikan ke Draft, silahkan melaporkan e-LHKPN kembali melalui elhkpn.kpk.go.id Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');            
            } else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
                ng::mail_send($datapn->EMAIL, 'Daftar Kekurangan LHKPN', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=e-LHKPN+Saudara+Memerlukan+Perbaikan,+silahkan+cek+rincian+perbaikan+yang+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->NO_HP.'');
//                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=kpk2019&SMSText=e-LHKPN%20Saudara%20Memerlukan%20Perbaikan,%20silahkan%20cek%20rincian%20perbaikan%20yang%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=e-LHKPN%20Saudara%20Memerlukan%20Perbaikan,%20silahkan%20cek%20rincian%20perbaikan%20yang%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles, $datapn->NO_HP, 'e-LHKPN Bapak/Ibu Memerlukan Perbaikan, silahkan cek rincian perbaikan yang dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');
            } else if ($datapn->STATUS == "5") {
                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Status+e-LHKPN+Saudara+Terverifikasi+Tidak+Lengkap,+Tanda+Terima+telah+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->NO_HP.'');
//                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=kpk2019&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Tidak%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Tidak%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles,$datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Terverifikasi Tidak Lengkap, Tanda Terima telah dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');
            } else {
//                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', 'Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara unggah telah diverifikasi, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK.', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, $admin->EMAIL);
                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
//                CallURLPage('http://api.multichannel.id:8088/sms/sync/sendsingle?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Status+e-LHKPN+Saudara+Terverifikasi+Lengkap,+Tanda+Terima+telah+dikirim+ke+email+Saudara+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->NO_HP.'');
//                CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=kpk2019&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
		CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpk_api&password=client2021&SMSText=Status%20e-LHKPN%20Saudara%20Terverifikasi%20Lengkap,%20Tanda%20Terima%20telah%20dikirim%20ke%20email%20Saudara%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->NO_HP.'');
                ng::logSmsActivity($usernames,$idRoles,$datapn->NO_HP, 'Status e-LHKPN Bapak/Ibu Terverifikasi Lengkap, Tanda Terima telah dikirim ke email Saudara. Info : elhkpn@kpk.go.id atau 198', 'Verifikasi');
            }
            //ng::mail_send($datapn->EMAIL, $message, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $th . '.pdf', $admin->EMAIL);
//            ng::mail_send($datapn->EMAIL, $message, NULL, 'uploads/pdf/' . $time . '-Verification-fileTT.pdf', 'cahyana.yogi@gmail.com');
            unlink($path_pdf.$output_filename);
            $this->session->set_userdata('msg_verifikasi_cepat','LHKPN Terverifikasi Lengkap ! Surat Tanda Terima (softcopy) berhasil dikirim ke '. $datapn->NAMA_LENGKAP.' ('.$datapn->NIK.')');    
        } else {
        
            $this->db->trans_rollback();

            $this->session->set_userdata('msg_verifikasi_cepat','LHKPN Terverifikasi Lengkap ! Surat Tanda Terima (softcopy) gagal dikirim ke '. $datapn->NAMA_LENGKAP.' ('.$datapn->NIK.')');
        }
        intval($this->db->trans_status());

        redirect('index.php?dpg=c8763ad09e4afa1445e98bee98524fb3#index.php/ever/verification/index/lhkpn?sessVerif');
        
    }
    */

    public function telusur() {
        if (!$this->session->userdata('IS_KPK')) {
            show_error('Anda tidak memiliki akses untuk melakukan tindakan ini!', 401);
            exit();
        }

        $this->load->library('telusur');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_lhkpn', 'id_lhkpn', 'trim|required|xss_clean');
        $this->form_validation->set_rules('jenis_harta', 'jenis_harta', 'trim|required|xss_clean|in_list[htb,hb]');
        $this->form_validation->set_rules('status', 'status', 'trim|required|xss_clean|in_list[pn,keluarga]');

        $id_lhkpn = $this->input->post('id_lhkpn', true);
        $jenis_harta = $this->input->post('jenis_harta', true);
        $status = $this->input->post('status', true);

        $data_array = [];

        if ($this->form_validation->run()) {
            if ($status == "pn") {
                $data_pribadi = $this->Verification_model->get_data_pribadi_by_id_lhkpn($id_lhkpn);
                $data = $this->telusur->set_nik($data_pribadi->NIK)->set_jenis_harta($jenis_harta)->get();
                $data_array = array_merge($data_array, $data);
            } else if ($status == "keluarga") {
                $data_keluarga_array = $this->Verification_model->get_keluarga_by_id_lhkpn($id_lhkpn);

                if (!empty($data_keluarga_array)) {
                    foreach ($data_keluarga_array as $data_keluarga) {
                        if (!empty($data_keluarga->NIK)) {
                            $lhkpn_version = $this->mlhkpnkeluarga->get_lhkpn_version($data_keluarga->ID_KELUARGA);
            
                            if (!$lhkpn_version) {
                                $lhkpn_version = 'old';
                            }
        
                            $hubungan = @get_arr_hubungan_keluarga($lhkpn_version)[$data_keluarga->HUBUNGAN];
                            $data = $this->telusur->set_nik($data_keluarga->NIK)->set_jenis_harta($jenis_harta)->set_hubungan($hubungan)->get();
                            $data_array = array_merge($data_array, $data);
                        }
                    }
                }
            }
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => $data_array
        ];

        echo json_encode($output);

        exit();
    }
}
