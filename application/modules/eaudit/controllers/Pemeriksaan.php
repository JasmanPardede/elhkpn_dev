<?php

/**
* Controller
*
* @author Ferry Ricardo Siagian - Komisi Pemberantasan Korupsi
* @package Eaudit/Controllers/Pemeriksaan
*/
?>
<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Pemeriksaan extends Nglibs {

  private $jenis_data_lhkpn = [
    'DATAPRIBADI' => 'Data Pribadi',
    'JABATAN' => 'Jabatan',
    'KELUARGA' => 'Keluarga',
    'HARTATIDAKBERGERAK' => 'Tanah / Bangunan',
    'HARTABERGERAK' => 'Mesin / Alat Transportasi',
    'HARTABERGERAK2' => 'Harta Bergerak Lainnya',
    'SURATBERHARGA' => 'Surat Berharga',
    'KAS' => 'Kas',
    'HARTALAINNYA' => 'Harta Lainnya',
    'HUTANG' => 'Hutang',
    'PENERIMAANKAS' => 'Penerimaan Kas',
    'PENGELUARANKAS' => 'Pengeluaran Kas',
    'PELEPASANHARTA' => 'Pelepasan Harta',
    'PENERIMAANHIBAH' => 'Penerimaan Hibah',
    'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas',
    'DOKUMENPENDUKUNG' => 'Dokumen Pendukung',
    'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];

    public function __construct() {
      parent::__construct();
      $this->load->model("Pemeriksaan_model");
      $this->load->model("Mcopylhkpntolhkpn");
      if ($this->input->get('show_profiler')) {
        $this->output->enable_profiler(TRUE);
      }
      $this->makses->initialize();
      // $this->makses->check_is_read();
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

    public function add($id) {
      $this->data['act'] = 'doklarifikasi';
      $this->data['id_lhkpn'] = $id;

      $this->load->view('eaudit/pemeriksaan/inputklarifikasi', $this->data);
    }

    // set status_pemeriksaan
    public function set_status_periksa($status, $id_lhkpn)
    {
      $this->Pemeriksaan_model->set_status_periksa($status, $id_lhkpn);
    }

    public function lhkpn($type = '', $id = '', $tgl_klarifikasi='') {
      $this->load->model('mlhkpnkeluarga');
      $this->data['tbl'] = 'T_LHKPN';
      $this->data['pk'] = 'ID_LHKPN';
      $my_where = [];
      $my_where_find = [];

      $id_usr = $this->session->userdata('ID_USER');
      $roles = $this->session->userdata('ID_ROLE');

      if ($type == 'list') {

        list($this->items, $this->total_rows) = $this->Pemeriksaan_model->list_pemeriksaan_lhkpn($id_usr, $this->CARI, $this->limit, $this->offset, $roles);

        $this->end = count($this->items);
        $this->data['title'] = 'Permintaan Pemeriksaan LHKPN';
      }

      /* IF TYPE == REFF */
      else if ($type == 'reff') {
        if ($this->display == 'pemeriksaan') {
          $this->data['icon'] = 'fa-book';
          $this->data['title'] = 'LHKPN';
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
          $tgl_klarifikasi = $tgl_klarifikasi;
          $is_riksa = $this->Pemeriksaan_model->is_riksa($id);

          if(!$is_riksa) {
            $new_idlhkpn = $this->Pemeriksaan_model->copy_to_lhkpn($id, $tgl_klarifikasi);
          } else {
            $idlhkpn = $this->Pemeriksaan_model->searching_lhkpn($id);
            $new_idlhkpn = $idlhkpn->ID_LHKPN;
          }

          $this->data['id_lhkpn'] = $new_idlhkpn;
          $this->load->model('mlhkpn', '', TRUE);
          // $this->data['golonganPenerimaan'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS', 'NAMA_GOLONGAN');
          // $this->data['golonganPengeluaran'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS', 'NAMA_GOLONGAN');
          // display($this->db->last_query());
          // die();
          $this->data['lamp2s'] = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
          //display($this->data['lamp2s']);die;
          $this->data['LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = ' . 'T_PN.ID_PN'], ['table' => 'T_LHKPN_DATA_PRIBADI', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'T_LHKPN_DATA_PRIBADI.ID_LHKPN']], NULL, '*,T_PN.NIK as NIK_PN', "T_LHKPN.ID_LHKPN = '$new_idlhkpn'")[0];
          // display($this->db->last_query());
          // die();
          /*tutup by ferry*/
          //$this->data['tmpData'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN =' => $id])[0]->HASIL_VERIFIKASI);
          $id_user = @$this->mglobal->get_data_all('T_LHKPN_AUDIT', null, ['IS_ACTIVE' => '1', 'ID_LHKPN =' => $new_idlhkpn], 'ID_PEMERIKSA, UPDATED_BY')[0];

          $this->data['pic1'] = @$this->mglobal->get_data_all('T_USER', null, ['IS_ACTIVE' => '1', 'ID_USER' => $id_user->ID_PEMERIKSA], 'NAMA')[0];
          $this->data['pic2'] = @$this->mglobal->get_data_all('T_USER', null, ['IS_ACTIVE' => '1', 'ID_USER' => $user->UPDATED_BY], 'NAMA')[0];

          $cek = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, 'KD_ISO3_NEGARA', "ID_LHKPN = '$new_idlhkpn'")[0];
          $joinDATA_PRIBADI = [];
          $selectDATA_PRIBADI = 'T_LHKPN_DATA_PRIBADI.*, T_LHKPN.*';
          if (@$cek->KD_ISO3_NEGARA == '') {
            $joinDATA_PRIBADI = [
              // ['table' => 'M_PROVINSI', 'on' => 'M_PROVINSI.IDPROV = T_LHKPN_DATA_PRIBADI.PROVINSI'],
              // ['table' => 'M_KABKOT', 'on' => 'M_KABKOT.IDKOT = T_LHKPN_DATA_PRIBADI.KABKOT AND M_AREA.IDPROV = M_KABKOT.IDPROV'],
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

          $this->data['DATA_PRIBADI'] = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', $joinDATA_PRIBADI, NULL, $selectDATA_PRIBADI, "T_LHKPN_DATA_PRIBADI.ID_LHKPN = '$new_idlhkpn'")[0];
          $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*,M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN, M_SUB_UNIT_KERJA.SUK_NAMA';
          $joinJabatan = [
            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
            ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
            ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
            ['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID'],
          ];
          $this->data['JABATANS'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$new_idlhkpn' ", ['IS_PRIMARY', 'DESC']);
          $this->data['JABATANS_P'] = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$new_idlhkpn' AND IS_PRIMARY = '1' ", ['IS_PRIMARY', 'DESC']);
          //                display($this->db->last_query());
          $this->data['ID_LHKPN'] = $new_idlhkpn;
          $sql_jabatan_lhkpn = "SELECT NAMA_JABATAN FROM M_JABATAN JOIN T_LHKPN_JABATAN ON T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN WHERE T_LHKPN_JABATAN.ID_LHKPN = ( SELECT ID_LHKPN FROM T_LHKPN WHERE T_LHKPN.ID_LHKPN = '$new_idlhkpn' )";
          $this->data['JABATAN_LHKPN'] = $this->db->query($sql_jabatan_lhkpn)->result();

          $id_pn = $this->data['LHKPN']->ID_PN;
          $joinJabatan = [
            // ['table'=>'M_INST_SATKER', 'on'=>'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']

            ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN'],
            ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
            ['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
          ];
          $this->data['JABATANSPN'] = $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "T_PN_JABATAN.ID_PN = '$id_pn' AND IS_CURRENT = 1");
          //                display($this->db->last_query());
          $this->data['ID_PN'] = $id_pn;

          // SELECT hasil verifikasi
          /*ditutup oleh ferry*/
          //$this->data['hasilVerifikasiitem'] = @json_decode($this->mglobal->get_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1'], 'HASIL_VERIFIKASI', "ID_LHKPN = '$id'")[0]->HASIL_VERIFIKASI);

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

          $agenda = date('Y', strtotime($this->data['LHKPN']->tgl_kirim_final)) . '/' . ($this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $this->data['LHKPN']->NIK . '/' . $this->data['LHKPN']->ID_LHKPN;
          $nama = (@$this->data['DATA_PRIBADI']->NAMA_LENGKAP != '' ? $this->data['DATA_PRIBADI']->NAMA_LENGKAP : $this->data['LHKPN']->NAMA);
          $nik = (@$this->data['DATA_PRIBADI']->NIK != '' ? $this->data['DATA_PRIBADI']->NIK : $this->data['LHKPN']->NIK);

          $jen_lap = $this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus';
          $tgl_lap = $this->data['LHKPN']->JENIS_LAPORAN == '4' ? substr($this->data['LHKPN']->tgl_lapor, 0,4) : tgl_format($this->data['LHKPN']->tgl_lapor);
          //$this->data['tgl_klarifikasi'] = tgl_format($this->data['LHKPN']->tgl_klarifikasi);
          $this->data['tampil'] = 'LHKPN : ' . $nama . ' (' . $nik . ') - ' . $agenda;
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
              '4' => 'Sewa Jangaka Panjang Dibayar Dimuka',
              '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
            ];
            $this->data['list_harta'] = $list_harta;
            $this->data['list_bukti'] = $list_bukti;
            $this->data['rinci_keluargas'] = $this->mlhkpnkeluarga->get_rincian2("ID_LHKPN = '$new_idlhkpn'");
            $this->data['lhkpn_ver'] = $this->mlhkpnkeluarga->get_lhkpn_version($new_idlhkpn);
            $this->data['KELUARGAS'] = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");

            $joinHARTA_TIDAK_BERGERAK = [
              ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
              ['table' => 'M_NEGARA', 'on' => 'M_NEGARA.ID = ID_NEGARA', 'join' => 'left'],
              //['table' => 'M_AREA as area', 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV', 'join' => 'left']
              ['table' => 'M_AREA_KAB as kabkot', 'on' => 'kabkot.NAME_KAB = data.KAB_KOT', 'join' => 'left'],
              ['table' => 'M_AREA_PROV as provinsi', 'on' => 'provinsi.NAME = data.PROV', 'join' => 'left']
            ];
            $KABKOT = "(SELECT NAME_KAB FROM M_AREA_KAB as area WHERE data.KAB_KOT = area.NAME_KAB AND area.IS_ACTIVE = 1) as KAB_KOT";
            $PROV = "(SELECT NAME FROM M_AREA_PROV as area WHERE data.PROV = area.NAME) as PROV";
            //                $selectHARTA_TIDAK_BERGERAK = 'IS_CHECKED, data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL,' . $KABKOT . ',' . $PROV . ', data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
            $selectHARTA_TIDAK_BERGERAK = 'DISTINCT IS_CHECKED, data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL, KAB_KOT, PROV, data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

            $this->data['HARTA_TIDAK_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL, [$selectHARTA_TIDAK_BERGERAK, FALSE], "ID_LHKPN = '$new_idlhkpn'");
            $this->data['HARTA_BERGERAKS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['HARTA_BERGERAK_LAINS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['HARTA_SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['SURAT_BERHARGAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', [
              ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
              ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA'],
              ['table' => 't_lhkpn_data_pribadi', 'on' => 't_lhkpn_data_pribadi.ID_LHKPN  = T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN']
            ], NULL, '*', "T_LHKPN_HARTA_SURAT_BERHARGA.ID_LHKPN = '$new_idlhkpn'");
            //                display($this->db->last_query());
            $this->data['HARTA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['HARTA_SETARA_KASS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', [
              ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
              ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA'],
              ['table' => 't_lhkpn_data_pribadi', 'on' => 't_lhkpn_data_pribadi.ID_LHKPN  = T_LHKPN_HARTA_KAS.ID_LHKPN']
            ], NULL, '*', "T_LHKPN_HARTA_KAS.ID_LHKPN = '$new_idlhkpn'");
            $this->data['HARTA_LAINNYAS'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMATA_UANG, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['HUTANGS'] = $this->mglobal->get_data_all('T_LHKPN_HUTANG', $joinHUTANG, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['PENERIMAAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");
            $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*', "ID_LHKPN = '$new_idlhkpn'");

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

          }
          if ($this->display == 'history') {
            $this->data['icon'] = 'fa-book';
            $this->data['title'] = 'LHKPN PN 123';
            // $this->data['breadcrumb'] = 'LHKPN Masuk';

            $this->data['VERIFICATIONS'] = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$id'");
            //echo $this->db->last_query();
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
            $jabatan = $this->mglobal->get_data_all('T_LHKPN_JABATAN', NULL, ['ID_LHKPN' => $ID_LHKPN]);
            $lhkpn_dt = $this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_LHKPN' => $ID_LHKPN]);
            $countJab = count($jabatan);
            $iscekJab = $this->mglobal->get_data_all('T_LHKPN_JABATAN', NULL, ['ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '0']);
            $countJIC = count($iscekJab);

            /* IF SIMPAN == DRAFT */
            if ($countJab != $countJIC || $simpan == 'draft') {
              $this->db->trans_begin();
              $ID_LHKPN = $this->input->post('ID_LHKPN', TRUE);
              $this->load->model('mglobal');
              $final = @$this->input->post('final');

              if ($final == '1')
              $MSG_VER = $this->input->post('MSG_VERIFIKASI_TRUE', TRUE);
              elseif ($final == '2')
              $MSG_VER = $this->input->post('MSG_VERIFIKASI', TRUE);
              else if ($final == '3')
              $MSG_VER = $this->input->post('MSG_VERIFIKASI_TIDAK_LENGKAP', TRUE);
              else
              $MSG_VER = $this->input->post('MSG_VERIFIKASI_DITOLAK', TRUE);

              $CountData = $this->mglobal->count_data_all('T_VERIFICATION', null, ['IS_ACTIVE' => '1', 'ID_LHKPN' => $ID_LHKPN]);
              $data = array(
                'TANGGAL' => date('Y-m-d'),
                'HASIL_VERIFIKASI' => json_encode($this->input->post('VER', TRUE)),
                'MSG_VERIFIKASI' => str_replace('[removed]', '', $MSG_VER),
                'MSG_VERIFIKASI_INSTANSI' => str_replace('width:', 'width="', str_replace('[removed]', '', $this->input->post('MSG_VERIFIKASI_INSTANSI', TRUE))),
                'STATUS_VERIFIKASI' => ($simpan == 'draft') ? '0' : '1',
                'IS_ACTIVE' => '1',
              );

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
                $history = [
                  'ID_LHKPN' => $this->input->post('ID_LHKPN', TRUE),
                  'ID_STATUS' => 9,
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
                } else if ($final == '3') {
                  $status = '5';
                  $id_status_history = '11';
                  $penerima = 'koordinator_announcement';
                } else {
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

                $res = [];
                $res['STATUS'] = $status;
                if ($status == '2') {
                  $res['ALASAN'] = $this->input->post('alasan');
                }
                display($res);die;

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
      if ($entry_via == '1'){
        $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
      }
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

      $pengirim = array(
        'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
        'ID_PENERIMA' => $datapn->ID_USER,
        'SUBJEK' => 'Tanda Terima ( Verifikasi )',
        'PESAN' => $isi_pesan,
        'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
        'IS_ACTIVE' => '1',
        'ID_LHKPN' => $ID_LHKPN
      );
      $kirim = $this->mglobal->insert('T_PESAN_KELUAR', $pengirim);

      if ($kirim) {

        $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".docx";
        if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
          $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y') . ".docx";
        }

        $penerima = array(
          'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
          'ID_PENERIMA' => $datapn->ID_USER,
          'SUBJEK' => 'Tanda Terima ( Verifikasi )',
          'PESAN' => $isi_pesan,
          'FILE' => "../../../uploads/pdf/" . $datapn->NIK . '/' . $output_filename,
          'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
          'IS_ACTIVE' => '1'
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
          }
        }

        $this->load->library('lwphpword/lwphpword', array(
          "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
          "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
          "base_root" => base_url(),
        ));

        $template_file = "../file/template/FormatTandaTerima.docx";
        if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
          $template_file = "../file/template/LampiranKekurangan.docx";
        }

        $this->load->library('lws_qr', [
          "model_qr" => "Cqrcode",
          "model_qr_prefix_nomor" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "LK-ELHKPN-" : "TT-ELHKPN-",
          "callable_model_function" => "insert_cqrcode_with_filename",
          //                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
        ]);

        $this->load->library('ey_barcode');

        $qr_content_data = json_encode((object) [
          "data" => [
            (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
            (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
            (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
            (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final))],
            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
            (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
          ],
          "encrypt_data" => $ID_LHKPN . "tt",
          "id_lhkpn" => $ID_LHKPN,
          "judul" => $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? "Lampiran Kekurangan E-LHKPN" : "Tanda Terima E-LHKPN",
          "tgl_surat" => date('Y-m-d'),
        ]);

        $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $ID_LHKPN . ".png");
        $bc_image_location = $this->ey_barcode->generate($datapn->NIK, "tes_bc2-" . $ID_LHKPN . ".jpg");

        $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? array("image1.png" => $bc_image_location) : array("image2.jpeg" => $qr_image_location));

        //            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);
        $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

        $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
        $this->lwphpword->set_value("LKP", $datapn->STATUS == "3" ? "v" : " ");
        $this->lwphpword->set_value("TLKP", $datapn->STATUS == "3" ? " " : "v");
        if ($datapn->STATUS == "5") {
          $this->lwphpword->set_value("IS", !$verif_isian_ok ? " " : "v");
          $this->lwphpword->set_value("LA", $verif->VAL->SURATKUASAMENGUMUMKAN == "1" ? " " : "v");
          $this->lwphpword->set_value("DK", $verif->VAL->DOKUMENPENDUKUNG == "1" ? " " : "v");
        } else {
          $this->lwphpword->set_value("IS", " ");
          $this->lwphpword->set_value("LA", " ");
          $this->lwphpword->set_value("DK", " ");
        }
        $this->lwphpword->set_value("NIK", $datapn->NIK);
        $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
        $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
        $this->lwphpword->set_value("TGL_VER", $TGL_VER);
        $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
        $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final)));
        $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));

        if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
          $this->set_msg_kekurangan($verif, $this->lwphpword);
        }

        $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);

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
          <td>' . substr($datapn->tgl_kirim_final, 0, 4) . '</td>
          </tr>
          </tbody>
          </table>

          <table>
          <tr>
          <td>
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
            <td>' . substr($datapn->tgl_kirim_final, 0, 4) . '</td>
            </tr>
            </tbody>
            </table>

            <table>
            <tr>
            <td>
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
            Di Tempat<br/><br/>
            </td>
            </tr>
            </table>
            <table>
            <tr>
            Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal ' . $TGL_VER . '<br><br>
            Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK.<br><br>
            Bakal Calon Kepala Daerah wajib menyampaikan kekurangan dokumen pendukung sebagaimana tercantum dalam lampiran email ini kepada KPK dalam waktu tidak melebihi tanggal tersebut di atas. Dalam hal Bakal Calon Kepala Daerah tidak memenuhi kewajiban tersebut, maka KPK hanya akan memberikan Tanda Terima dengan catatan hasil verifikasi Tidak Lengkap atau menyatakan LHKPN DITOLAK sesuai dengan pedoman yang berlaku di KPK.<br><br>
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

            if ($datapn->STATUS == "7") {
              ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', 'Yth. Sdr. ' . $datapn->NAMA_LENGKAP . '<br>' . $datapn->INST_NAMA . '<br> Di Tempat<br><br>Bersama ini kami sampaikan bahwa LHKPN Tanggal '. tgl_format($datapn->tgl_lapor) .' atas nama Saudara dinyatakan DITOLAK dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.<br><br>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.<br><br>Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>--------------------------------------------------------------<br>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br>&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198', NULL, NULL);
            } else if ($datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2")) {
              ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            } else if ($datapn->STATUS == "5") {
              ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_tidak_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            } else {
              //                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', 'Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara unggah telah diverifikasi, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK.', NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename, $admin->EMAIL);
              ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
            }
            //ng::mail_send($datapn->EMAIL, $message, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $th . '.pdf', $admin->EMAIL);
            //            ng::mail_send($datapn->EMAIL, $message, NULL, 'uploads/pdf/' . $time . '-Verification-fileTT.pdf', 'cahyana.yogi@gmail.com');

            $temp_dir = APPPATH."../images/qrcode/";
            $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
            unlink($temp_dir.$qr_image);
            $temp_dir_br = APPPATH."../uploads/barcode/";
            $br_image = "tes_bc2-" . $ID_LHKPN . ".jpg";
            unlink($temp_dir_br.$br_image);

            $this->db->trans_commit();
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

          //packing hasil calling data lampiran pelepasan
          if (!empty($pelepasanhartatidakbergerak)) {
            foreach ($pelepasanhartatidakbergerak as $key) {
              $pelepasan[] = [
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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
                'KODE_JENIS' => ($key->JENIS_PELEPASAN_HARTA == '1' ? ($key->JENIS_PELEPASAN_HARTA == '2' ? 'PELEPASAN HARTA' : 'PENJUALAN HARTA' ) : 'PEMBERIAN HIBAH'),
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

            public function verupload($type = '', $id = '') {
              $defulttable = 'tabel_';

              $joinMATA_UANG = [
                ['table' => 'M_MATA_UANG', 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                ['table' => 'm_jenis_harta', 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
              ];

              $this->data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);

              switch ($type) {
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
                  if ($this->act == 'doupload') {
                    $FILE_BUKTI = NULL;
                    $ID = $this->input->post('ID', TRUE);
                    $ID_LHKPN = $this->input->post('ID_LHKPN', TRUE);
                    $ITEMVER = $this->input->post('ITEMVER', TRUE);

                    if ($ITEMVER == 'suratberharga') {
                      $func_upload = $this->upload_surat_berharga();
                      $TABLE = 't_lhkpn_harta_surat_berharga';
                    } else if ($ITEMVER == 'kas') {
                      $func_upload = $this->upload_kas();
                      $TABLE = 't_lhkpn_harta_kas';
                    } else if ($ITEMVER == 'skm') {
                      $func_upload = $this->upload_skm();
                      $TABLE = 't_lhkpn';
                    } else if ($ITEMVER == 'sk') {
                      $func_upload = $this->upload_sk();
                      $TABLE = 't_lhkpn';
                    } else {
                      $func_upload = $this->upload_harta_lainnya();
                      $TABLE = 't_lhkpn_harta_lainnya';
                    }

                    $PK = $this->pk($TABLE);

                    if ($ID || $ID_LHKPN) {
                      $this->db->where($PK, $ID);
                      $temp = $this->db->get($TABLE)->row();
                      $upload = $func_upload;
                      if ($upload['upload']) {
                        if ($temp) {
                          if ($temp->FILE_BUKTI) {
                            unlink($temp->FILE_BUKTI);
                            $FILE_BUKTI = $upload['url'];
                          } else {
                            $FILE_BUKTI = $upload['url'];
                          }
                        }
                      } else {
                        $FILE_BUKTI = $temp->FILE_BUKTI;
                      }
                    } else {
                      $upload = $func_upload();
                      if ($upload['upload']) {
                        $FILE_BUKTI = $upload['url'];
                      }
                    }

                    if ($ID || $ID_LHKPN) {
                      $data = array(
                        'UPDATED_TIME' => date("Y-m-d H:i:s"),
                        'UPDATED_BY' => $this->session->userdata('USR'),
                        'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                        'FILE_BUKTI' => $FILE_BUKTI
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

              function upload_surat_berharga() {
                $result = array();
                $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
                if (!file_exists('uploads/data_suratberharga/' . $folder)) {
                  mkdir('uploads/data_suratberharga/' . $folder);
                  $content = "Bukti Surat Berharga Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
                  $fp = fopen(FCPATH . "/uploads/data_suratberharga/" . $folder . "/readme.txt", "wb");
                  fwrite($fp, $content);
                  fclose($fp);
                  /* IBO UPDATE */

                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }

                  /* IBO UPDATE */
                } else {
                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }
                }
                /* header('Content-Type: application/json');
                echo json_encode($result); */
                return $result;
              }

              function upload_skm() {
                $result = array();
                $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

                if (!file_exists('uploads/data_skm/' . $folder)) {
                  mkdir('uploads/data_skm/' . $folder);
                  $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
                  $fp = fopen(FCPATH . "/uploads/data_skm/" . $folder . "/readme.txt", "wb");
                  fwrite($fp, $content);
                  fclose($fp);

                  /* --- IBO ADD -- */

                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }

                  /* ---End IBO ADD -- */
                } else {
                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_skm/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }
                }
                /* header('Content-Type: application/json');
                echo json_encode($result); */
                return $result;
              }

              function upload_sk() {
                $result = array();
                $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

                if (!file_exists('uploads/data_sk/' . $folder)) {
                  mkdir('uploads/data_sk/' . $folder);
                  $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
                  $fp = fopen(FCPATH . "/uploads/data_sk/" . $folder . "/readme.txt", "wb");
                  fwrite($fp, $content);
                  fclose($fp);

                  /* --- IBO ADD -- */

                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }

                  /* ---End IBO ADD -- */
                } else {
                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_sk/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }
                }
                /* header('Content-Type: application/json');
                echo json_encode($result); */
                return $result;
              }

              function upload_kas() {
                $result = array();
                $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

                if (!file_exists('uploads/data_kas/' . $folder)) {
                  mkdir('uploads/data_kas/' . $folder);
                  $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
                  $fp = fopen(FCPATH . "/uploads/data_kas/" . $folder . "/readme.txt", "wb");
                  fwrite($fp, $content);
                  fclose($fp);

                  /* --- IBO ADD -- */

                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }

                  /* ---End IBO ADD -- */
                } else {
                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }
                }
                /* header('Content-Type: application/json');
                echo json_encode($result); */
                return $result;
              }

              function upload_harta_lainnya() {
                $result = array();
                $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
                if (!file_exists('uploads/data_hartalainnya/' . $folder)) {
                  mkdir('uploads/data_hartalainnya/' . $folder);
                  $content = "Bukti Harta Lainnya Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
                  $fp = fopen(FCPATH . "/uploads/data_hartalainnya/" . $folder . "/readme.txt", "wb");
                  fwrite($fp, $content);
                  fclose($fp);
                  /* IBO UPDATE */

                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }

                  /* IBO UPDATE */
                } else {
                  if (isset($_FILES["file1"])) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file1']['name'])));
                    $file_name = $time . '.' . $ext;
                    $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
                    $uploadext = '.' . strtolower($ext);
                    if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                      if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                      } else {
                        $result = array('upload' => false, 'url' => $uploaddir);
                      }
                    }
                  } else if (isset($_FILES["file2"])) {
                    foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                      $time = time();
                      $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                      $file_name = $key . '' . $time . '.' . $ext;
                      $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
                      $uploadext = '.' . strtolower($ext);
                      if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                        if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                          $result = array('upload' => true, 'url' => $uploaddir);
                        }
                      }
                    }
                  } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                  }
                }
                /* header('Content-Type: application/json');
                echo json_encode($result); */
                return $result;
              }

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

                $this->load->library('ey_barcode');

                $bc_image_location = $this->ey_barcode->generate($data->NIK, "tes_bc2-" . $id . ".jpg");

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

                $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image1.png" => $bc_image_location));
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
                }
                $temp_dir_br = APPPATH."../uploads/barcode/";
                $br_image = "tes_bc2-" . $id . ".jpg";
                unlink($temp_dir_br.$br_image);
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
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
                }
                echo "1";
                exit;
              }

              public function test_kirim_email($param, $entry_via)
              {
                $ID_LHKPN = $param;
                $VERIFICATIONS = $this->mglobal->get_data_all('T_VERIFICATION', NULL, NULL, '*', "ID_LHKPN = '$param'", ["ID", "DESC"]);
                $verif = @json_decode($VERIFICATIONS[0]->HASIL_VERIFIKASI);

                $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
                if ($entry_via == '1'){
                  $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
                }

                $output_filename = "Lampiran_Kekurangan_LHKPN_" . date('d-F-Y') . ".docx";
                $filename = 'uploads/pdf/' . $datapn->NIK . "/$output_filename";

                if (!file_exists($filename)) {
                  $dir = './uploads/pdf/' . $datapn->NIK . '/';

                  if (is_dir($dir) === false) {
                    mkdir($dir);
                  }
                }

                $this->load->library('lwphpword/lwphpword', array(
                  "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
                  "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
                  "base_root" => base_url(),
                ));

                $template_file = "../file/template/LampiranKekurangan.docx";

                $this->load->library('ey_barcode');

                $bc_image_location = $this->ey_barcode->generate($datapn->NIK, "tes_bc2-" . $ID_LHKPN . ".jpg");

                $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, $datapn->STATUS == "2" && ($datapn->ALASAN == "1" || $datapn->ALASAN == "2") ? array("image1.png" => $bc_image_location) : array("image2.jpeg" => $qr_image_location));

                $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

                $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA);
                $this->lwphpword->set_value("NIK", $datapn->NIK);
                $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
                $this->set_msg_kekurangan($verif, $this->lwphpword);

                $save_document_success = $this->lwphpword->save_document(1, '', FALSE, $output_filename);
                $message_perbaikan = $VERIFICATIONS[0]->MSG_VERIFIKASI;

                ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_perbaikan, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
                $temp_dir_br = APPPATH."../uploads/barcode/";
                $br_image = "tes_bc2-" . $ID_LHKPN . ".jpg";
                unlink($temp_dir_br.$br_image);
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
                $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);
                if ($entry_via == '1'){
                  $datapn = @$this->mglobal->get_detail_pn_lhkpn_excel($ID_LHKPN, TRUE, TRUE);
                }

                $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".docx";

                $filename_tt = 'uploads/pdf/' . $datapn->NIK;

                if (!is_dir($filename_tt)) {
                  $dir_tt = './uploads/pdf/' . $datapn->NIK . '/';

                  if (is_dir($dir_tt) === false) {
                    mkdir($dir_tt);
                  }
                }

                $this->load->library('lwphpword/lwphpword', array(
                  "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
                  "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
                  "base_root" => base_url(),
                ));

                $template_file = "../file/template/FormatTandaTerima.docx";

                $this->load->library('lws_qr', [
                  "model_qr" => "Cqrcode",
                  "model_qr_prefix_nomor" => "TT-ELHKPN-",
                  "callable_model_function" => "insert_cqrcode_with_filename",
                  //                "temp_dir"=>APPPATH."../images/qrcode/" //hanya untuk production
                ]);

                $qr_content_data = json_encode((object) [
                  "data" => [
                    (object) ["tipe" => '1', "judul" => "Atas Nama", "isi" => $datapn->NAMA_LENGKAP],
                    (object) ["tipe" => '1', "judul" => "NIK", "isi" => $datapn->NIK],
                    (object) ["tipe" => '1', "judul" => "Jabatan", "isi" => $datapn->NAMA_JABATAN],
                    (object) ["tipe" => '1', "judul" => "Lembaga", "isi" => $datapn->INST_NAMA],
                    (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final))],
                    (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                    (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                  ],
                  "encrypt_data" => $ID_LHKPN . "tt",
                  "id_lhkpn" => $ID_LHKPN,
                  "judul" => "Tanda Terima E-LHKPN",
                  "tgl_surat" => date('Y-m-d'),
                ]);

                $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $ID_LHKPN . ".png");

                $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image2.jpeg" => $qr_image_location));

                $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

                $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
                $this->lwphpword->set_value("LKP", $datapn->STATUS == "3" ? "v" : " ");
                $this->lwphpword->set_value("TLKP", $datapn->STATUS == "3" ? " " : "v");
                $this->lwphpword->set_value("NIK", $datapn->NIK);
                $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
                $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
                $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
                $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final)));
                $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));

                $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
                $this->lwphpword->download($save_document_success->document_path, $output_filename);

                $temp_dir = APPPATH."../images/qrcode/";
                $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
                unlink($temp_dir.$qr_image);
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

                $output_filename = "Tanda_Terima_LHKPN_" . date('d-F-Y') . ".docx";

                $filename_tt = 'uploads/pdf/' . $datapn->NIK;

                if (!is_dir($filename_tt)) {
                  $dir_tt = './uploads/pdf/' . $datapn->NIK . '/';

                  if (is_dir($dir_tt) === false) {
                    mkdir($dir_tt);
                  }
                }

                $this->load->library('lwphpword/lwphpword', array(
                  "base_path" => APPPATH . "../uploads/pdf/" . $datapn->NIK . "/",
                  "base_url" => base_url() . "../uploads/pdf/" . $datapn->NIK . "/",
                  "base_root" => base_url(),
                ));

                $template_file = "../file/template/FormatTandaTerima.docx";

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
                    (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus')." - ".show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final))],
                    (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($datapn->tgl_kirim_final)],
                    (object) ["tipe" => '1', "judul" => "Hasil Verifikasi", "isi" => $datapn->STATUS == "3" ? "Terverifikasi Lengkap" : "Terverifikasi Tidak Lengkap"],
                  ],
                  "encrypt_data" => $ID_LHKPN . "tt",
                  "id_lhkpn" => $ID_LHKPN,
                  "judul" => "Tanda Terima E-LHKPN",
                  "tgl_surat" => date('Y-m-d'),
                ]);

                $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $ID_LHKPN . ".png");

                $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image2.jpeg" => $qr_image_location));

                $this->lwphpword->save_path = APPPATH . "../uploads/pdf/" . $datapn->NIK . "/";

                $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
                $this->lwphpword->set_value("LKP", $datapn->STATUS == "3" ? "v" : " ");
                $this->lwphpword->set_value("TLKP", $datapn->STATUS == "3" ? " " : "v");
                $this->lwphpword->set_value("NIK", $datapn->NIK);
                $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
                $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
                $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
                $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final)));
                $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));

                $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);

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
                  <td>' . substr($datapn->tgl_kirim_final, 0, 4) . '</td>
                  </tr>
                  </tbody>
                  </table>

                  <table>
                  <tr>
                  <td>
                  Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198.<br/>
                  Atas kerjasama yang diberikan, Kami ucapkan terima kasih<br/>
                  Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                  --------------------------------------------------------------<br/>
                  Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                  &copy; ' . date('Y') . ' Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                  </td>
                  </tr>
                  </table>';
                  if ($save_document_success){
                    ng::mail_send($datapn->EMAIL, 'Tanda Terima ( Verifikasi )', $message_lengkap, NULL, 'uploads/pdf/' . $datapn->NIK . '/' . $output_filename);
                    echo '1';
                  }
                  $temp_dir = APPPATH."../images/qrcode/";
                  $qr_image = "tes_qr2-" . $ID_LHKPN . ".png";
                  unlink($temp_dir.$qr_image);
                }

              }
