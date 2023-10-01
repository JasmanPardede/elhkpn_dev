<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller LHKPN
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Efill/Controllers/Lhkpn_view
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lhkpn_view extends Nglibs {
	public function __construct()
	{
		parent::__construct();
		call_user_func('ng::islogin');
		$this->load->model('mglobal');
		$this->username = $this->session->userdata('USERNAME');
	}

	public function lhkpn($type='',$id='')
	{
		$this->data['icon'] = 'fa-book';
		$this->data['title'] = 'LHKPN';
		$breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
		$breadcrumbitem[] = [ucwords(strtolower(__CLASS__)) => $this->segmentTo[2]];
		$breadcrumbitem[] = [$this->data['title'] => @$this->segmentTo[4]];
		$breadcrumbdata = [];
		foreach ($breadcrumbitem as $list) {
			$breadcrumbdata = array_merge($breadcrumbdata,$list);
		}
		$this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);	
				
		$joinMATA_UANG = [
			['table' => 'M_MATA_UANG'      , 'on' => 'MATA_UANG  = ID_MATA_UANG'],
		];

		$this->load->model('mlhkpn', '', TRUE);
		$this->data['LHKPN']                    = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN'      , 'on' => 'T_LHKPN.ID_PN   = '.'T_PN.ID_PN']], NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'")[0];
		$this->data['id_lhkpn']                 = $this->data['LHKPN']->ID_LHKPN; 
		$id_lhkpn 								= $this->data['LHKPN']->ID_LHKPN; 
		$data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS','NAMA_GOLONGAN');
		$data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS','NAMA_GOLONGAN'); 
		$this->data['golonganPenerimaan'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS','NAMA_GOLONGAN');
		$this->data['golonganPengeluaran'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS','NAMA_GOLONGAN');

		$selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
        $joinJabatan = [
            ['table'=>'M_INST_SATKER', 'on'=>'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
            ['table'=>'M_UNIT_KERJA', 'on'=>'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
            ['table'=>'M_JABATAN', 'on'=>'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
        ];

        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI',NULL,['ID_JENIS_BUKTI','DESC']);
        $list_bukti  = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        //jenis Harta
        $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA',NULL,['ID_JENIS_HARTA','DESC']);
        $list_harta  = [];
        foreach ($jenis_HARTA as $key) {
            $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
		
        $joinHARTA_TIDAK_BERGERAK = [
                                        ['table' => 'M_MATA_UANG'             , 'on' => 'MATA_UANG  = ID_MATA_UANG'],
                                        ['table' => 'M_NEGARA'                , 'on' => 'M_NEGARA.ID = ID_NEGARA'       ,   'join'  =>  'left'],
                                        ['table' => 'M_AREA as area'          , 'on' => 'area.IDKOT = ID_NEGARA AND area.IDPROV = data.PROV'       ,   'join'  =>  'left'],
                                        // ['table' => 'M_KABKOT as kabkot'      , 'on' => 'kabkot.IDKOT   = data.KAB_KOT' ,   'join'  =>  'left'],
                                        // ['table' => 'M_PROVINSI as provinsi'  , 'on' => 'provinsi.IDPROV = data.PROV'   ,   'join'  =>  'left']
                                    ];
        $where_eHARTA_TIDAK_BERGERAK= "SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id'";
        // $where_eHARTA_TIDAK_BERGERAK= "(provinsi.IDPROV = kabkot.IDPROV OR data.NEGARA = '1') and SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
        $PROV = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND '' = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as PROV";
        $selectHARTA_TIDAK_BERGERAK = 'data.NEGARA AS ID_NEGARA, NAMA_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, data.KEC as KEC, data.KEL as KEL,'.$KABKOT.','.$PROV.', data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';
		$this->data['list_harta']               = $list_harta;
		$this->data['list_bukti'] 				= $list_bukti;
		$this->data['getGolongan1']             = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS','NAMA_GOLONGAN');
		$this->data['getGolongan2']             = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS','NAMA_GOLONGAN');
		$this->data['DATA_PRIBADI']     		= @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'")[0];
        $this->data['JABATANS']         		= $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$id_lhkpn'");
		$this->data['REKENINGS']                = @$this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
		$this->data['KELUARGAS']        		= $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['HARTA_TIDAK_BERGERAKS']    = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL,  [$selectHARTA_TIDAK_BERGERAK, FALSE], $where_eHARTA_TIDAK_BERGERAK);
		$this->data['HARTA_BERGERAKS']  		= $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['HARTA_BERGERAK_LAINS'] 	= $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMATA_UANG, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['HARTA_SURAT_BERHARGAS'] 	= $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN",  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['HARTA_KASS']       		= $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['HARTA_LAINNYAS']   		= $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMATA_UANG, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['HUTANGS']          		= $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['PENERIMAAN_KASS']  		= $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['PENGELUARAN_KASS'] 		= $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['lamp2s']           		= $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id'");
		$this->data['lampiran_pelepasan'] 		= $this->_lampiran_pelepasan($id_lhkpn);
		$this->data['lampiran_hibah']  			= $this->_lampiran_hibah($id_lhkpn);
		$this->data['asalusul']                 = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER',  NULL);
		$this->data['pemanfaatan1'] 			= $this->daftar_pemanfaatan(1);
		$this->data['pemanfaatan2'] 			= $this->daftar_pemanfaatan(2);
		// print_r($this->data['DATA_PRIBADI']);
		// exit();

		$whereperhitunganpengeluaran    		= "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
        $this->data['getPenka']               	= $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $whereperhitunganpengeluaran);
        $whereperhitunganpemaasukan     		= "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn' ";
        $this->data['getPemka']               	= $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $whereperhitunganpemaasukan);

		$this->data['hartirak']   				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HARTA_TIDAK_BERGERAK','NILAI_PELAPORAN','sum_hartirak');
        $this->data['harger']     				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HARTA_BERGERAK','NILAI_PELAPORAN','sum_harger');
        $this->data['harger2']    				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HARTA_BERGERAK_LAIN',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harger2');
        $this->data['suberga']    				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HARTA_SURAT_BERHARGA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_suberga');
        $this->data['kaseka']     				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HARTA_KAS',"REPLACE(NILAI_EQUIVALEN,'.','')",'sum_kaseka');
        $this->data['harlin']     				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HARTA_LAINNYA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harlin');
        $this->data['_hutang']    				= $this->mlhkpn->summaryHarta($id_lhkpn,'T_LHKPN_HUTANG','SALDO_HUTANG','sum_hutang');

        $agenda = date('Y', strtotime($this->data['LHKPN']->TGL_LAPOR)).'/'.($this->data['LHKPN']->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$this->data['LHKPN']->NIK.'/'.$this->data['LHKPN']->ID_LHKPN;
        $this->data['title'] = 'Detail LHKPN : '.$this->data['DATA_PRIBADI']->NAMA_LENGKAP.' ('.$this->data['DATA_PRIBADI']->NIK.') - '.$agenda;
	}
	function daftar_pemanfaatan($gol){
	    $data = [];
	    $this->load->model('mlhkpnharta', '', TRUE);
	    $pemanfaatan = $this->mlhkpnharta->get_pemanfaatan($gol);
	    foreach ($pemanfaatan as $key) {
	        $data[$key->ID_PEMANFAATAN] = $key->PEMANFAATAN;
	    }
	    return $data;
	}

	private function _lampiran_pelepasan($id_lhkpn, $where = NULL)
	{
		//jenis bukti
		$jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI');
		$list_bukti  = [];
		foreach ($jenis_bukti as $key) {
		    $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
		}
		//jenis Harta
		$jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA');
		$list_harta  = [];
		foreach ($jenis_HARTA as $key) {
		    $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
		}
		//jenis harta bergerak lain
		$list_harta_berhenti =[
		                            '1' => 'Perabotan Rumah Tangga',
		                            '2' => 'Barang Elektronik',
		                            '3' => 'Perhiasan & Logam / Batu Mulia',
		                            '4' => 'Barang Seni / Antik',
		                            '5' => 'Persediaan',
		                            '6' => 'Harta Bergerak Lainnya',
		                      ];
		//jenis harta surat berharga
		$list_harta_surat   = [
		                            '1' => 'Penyertaan Modal pada Badan Hukum',
		                            '2' => 'Investasi',
		                      ];
		//jenis harta kas
		$list_harta_kas     = [
		                            '1' => 'Uang Tunai',
		                            '2' => 'Deposite',
		                            '3' => 'Giro',
		                            '4' => 'Tabungan',
		                            '5' => 'Lainnya',
		                      ];
		//jenis harta lainnya
		$list_harta_lain    = [
		                            '1' => 'Piutang',
		                            '2' => 'Kerjasama Usaha yang Tidak Berbadan Hukum',
		                            '3' => 'Hak Kekayaan Intelektual',
		                            '4' => 'Sewa Jangaka Panjang Dibayar Dimuka',
		                            '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
		                      ];
		//select lampiran pelepasan
		$selectlampiranpelepasan                = 'A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
		$selectpelepasanhartatidakbergerak      = ', B.ATAS_NAMA as ATAS_NAMA, B.LUAS_TANAH as LUAS_TANAH, B.LUAS_BANGUNAN as LUAS_BANGUNAN, B.NOMOR_BUKTI as NOMOR_BUKTI, B.JENIS_BUKTI as JENIS_BUKTI ';
		$selectpelepasanhartabergerak           = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA as ATAS_NAMA, B.MEREK as MEREK, B.NOPOL_REGISTRASI as NOPOL_REGISTRASI, B.NOMOR_BUKTI as NOMOR_BUKTI';
		$selectpelepasanhartabergeraklain       = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, ATAS_NAMA as ATAS_NAMA';
		$selectpelepasansuratberharga           = ', B.KODE_JENIS as KODE_JENIS, B.NAMA_SURAT_BERHARGA as NAMA_SURAT,  B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, B.ATAS_NAMA as ATAS_NAMA';
		$selectpelepasankas                     = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA_REKENING as ATAS_NAMA, B.NAMA_BANK as NAMA_BANK, B.NOMOR_REKENING as NOMOR_REKENING';
		$selectpelepasanhartalainnya            = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.ATAS_NAMA as ATAS_NAMA';
		
		// call data lampiran pelepasan
		$pelepasanhartatidakbergerak            = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK as A' , [['table' => 'T_LHKPN_HARTA_TIDAK_BERGERAK as B'   , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartatidakbergerak,  "A.ID_LHKPN = '$id_lhkpn'");
		$pelepasanhartabergerak                 = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK as A'       , [['table' => 'T_LHKPN_HARTA_BERGERAK as B'         , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartabergerak,  "A.ID_LHKPN = '$id_lhkpn'");
		$pelepasanhartabergeraklain             = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN as A'  , [['table' => 'T_LHKPN_HARTA_BERGERAK_LAIN as B'    , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartabergeraklain,  "A.ID_LHKPN = '$id_lhkpn'");
		$pelepasansuratberharga                 = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA as A' , [['table' => 'T_LHKPN_HARTA_SURAT_BERHARGA as B'   , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasansuratberharga,  "A.ID_LHKPN = '$id_lhkpn'");
		$pelepasankas                           = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS as A'            , [['table' => 'T_LHKPN_HARTA_KAS as B'              , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasankas,  "A.ID_LHKPN = '$id_lhkpn'");
		$pelepasanhartalainnya                  = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA as A'        , [['table' => 'T_LHKPN_HARTA_LAINNYA as B'          , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartalainnya,  "A.ID_LHKPN = '$id_lhkpn'");
		$pelepasan                              = [];

		//packing hasil calling data lampiran pelepasan
		if (!empty($pelepasanhartatidakbergerak)) {
		    foreach ($pelepasanhartatidakbergerak as $key) {
		        $pelepasan[] = [
		                            'KODE_JENIS'        => 'Tanah / Bangunan',
		                            'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
		                            'URAIAN_HARTA'      => "Tanah/Bangunan , Atas Nama ".@$key->ATAS_NAMA." dengan luas tanah ".@$key->LUAS_TANAH." dan luas bangunan ".@$key->LUAS_BANGUNAN." dengan bukti berupa ". $list_bukti[$key->JENIS_BUKTI]." dengan nomor bukti ".@$key->NOMOR_BUKTI,
		                            'ALAMAT'            => $key->ALAMAT,
		                            'NILAI'             => $key->NILAI_PELEPASAN,
		                            'PIHAK_DUA'         => $key->NAMA,
		                        ];
		    }
		}
		if (!empty($pelepasanhartabergerak)) {
		    foreach ($pelepasanhartabergerak as $key) {
		        $pelepasan[] = [
		                            'KODE_JENIS'        => 'Mesin / Alat transport',
		                            'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
		                            'URAIAN_HARTA'      => "Sebuah ".$list_harta[@$key->KODE_JENIS]." , Atas Nama ".@$key->ATAS_NAMA." , merek ".@$key->MEREK." dengan nomor registrasi ".$key->NOPOL_REGISTRASI." dan nomor bukti ".@$key->NOMOR_BUKTI,
		                            'ALAMAT'            => $key->ALAMAT,
		                            'NILAI'             => $key->NILAI_PELEPASAN,
		                            'PIHAK_DUA'         => $key->NAMA,
		                        ];
		    }
		}
		if (!empty($pelepasanhartabergeraklain)) {
		    foreach ($pelepasanhartabergeraklain as $key) {
		        $pelepasan[] = [
		                            'KODE_JENIS'        => 'Harta bergerak',
		                            'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
		                            'URAIAN_HARTA'      => $list_harta_berhenti[@$key->KODE_JENIS]." bernama ".@$key->NAMA_HARTA." , Atas nama ".@$key->ATAS_NAMA." dengan jumlah ".@$key->JUMLAH.' '.@$key->SATUAN,
		                            'ALAMAT'            => $key->ALAMAT,
		                            'NILAI'             => $key->NILAI_PELEPASAN,
		                            'PIHAK_DUA'         => $key->NAMA,
		                        ];
		    }
		}
		if (!empty($pelepasansuratberharga)) {
		    foreach ($pelepasansuratberharga as $key) {
		        $pelepasan[] = [
		                            'KODE_JENIS'        => 'Surat berharga',
		                            'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
		                            'URAIAN_HARTA'      => $list_harta_surat[@$key->KODE_JENIS].', Atas nama '.@$key->ATAS_NAMA.' berupa surat '.@$key->NAMA_SURAT.' dengan jumlah '.@$key->JUMLAH.' '.@$key->SATUAN,
		                            'ALAMAT'            => $key->ALAMAT,
		                            'NILAI'             => $key->NILAI_PELEPASAN,
		                            'PIHAK_DUA'         => $key->NAMA,
		                        ];
		    }
		}
		if (!empty($pelepasankas)) {
		    foreach ($pelepasankas as $key) {
		        $pelepasan[] = [
		                            'KODE_JENIS'        => 'KAS / Setara KAS',
		                            'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
		                            'URAIAN_HARTA'      => "KAS berupa ".$list_harta_kas[@$key->KODE_JENIS].', Atas nama '.@$key->ATAS_NAMA.' pada bank '.@$key->NAMA_BANK.' dengan nomor rekening '.@$key->NOMOR_REKENING,
		                            'ALAMAT'            => $key->ALAMAT,
		                            'NILAI'             => $key->NILAI_PELEPASAN,
		                            'PIHAK_DUA'         => $key->NAMA,
		                        ];
		    }
		}
		if (!empty($pelepasanhartalainnya)) {
		    foreach ($pelepasanhartalainnya as $key) {
		        $pelepasan[] = [
		                            'KODE_JENIS'        => 'Harta lainnya',
		                            'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
		                            'URAIAN_HARTA'      => "Harta lain berupa ".$list_harta_lain[@$key->KODE_JENIS].' dengan nama harta '.@$key->NAMA_HARTA.' atas nama '.@$key->ATAS_NAMA,
		                            'ALAMAT'            => $key->ALAMAT,
		                            'NILAI'             => $key->NILAI_PELEPASAN,
		                            'PIHAK_DUA'         => $key->NAMA,
		                        ];
		    }
		}
		
		return $pelepasan;
	}

    public function genpdf($id_lhkpn=null){
        $this->load->model('mlhkpnkeluarga');
        $this->load->model('mlhkpn', '', TRUE);
        $this->load->model('mlhkpn_lampiran2', '', TRUE);
        $this->load->model('mlhkpndokpendukung', '', TRUE);

        // cek jika $id_lhkpn null
        if($id_lhkpn==null){
            show_error('invalid url', 404 );
            die('invalid url');
        }

        $joinMATA_UANG = [
            ['table' => 'M_MATA_UANG'      , 'on' => 'MATA_UANG  = ID_MATA_UANG'],
            ['table' => 'M_JENIS_HARTA'      , 'on' => 'KODE_JENIS  = ID_JENIS_HARTA']
        ];
        $joinMU = [ ['table' => 'M_MATA_UANG'      , 'on' => 'MATA_UANG  = ID_MATA_UANG'] ];
        $joinHARTA_TIDAK_BERGERAK = [
            ['table' => 'M_MATA_UANG'             , 'on' => 'MATA_UANG  = ID_MATA_UANG']
        ];
        $where_eHARTA_TIDAK_BERGERAK = "SUBSTRING(md5(data.ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $selectHARTA_TIDAK_BERGERAK  = 'ID_NEGARA, IS_PELEPASAN, STATUS, SIMBOL, data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.JALAN as JALAN, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC AND IDKEL = data.KEL) as KEL, (SELECT NAME from M_AREA where IDPROV = data.PROV AND CAST(IDKOT as UNSIGNED) = data.KAB_KOT AND IDKEC = data.KEC LIMIT 1) AS KEC, data.KAB_KOT, data.PROV, data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP';

        //jenis bukti
        $jenis_bukti = $this->mglobal->get_data_all('M_JENIS_BUKTI', NULL, NULL, 'ID_JENIS_BUKTI, JENIS_BUKTI');
        $list_bukti  = [];
        foreach ($jenis_bukti as $key) {
            $list_bukti[$key->ID_JENIS_BUKTI] = $key->JENIS_BUKTI;
        }
        //jenis Harta
        $jenis_HARTA = $this->mglobal->get_data_all('M_JENIS_HARTA', NULL, NULL, 'ID_JENIS_HARTA, NAMA');
        $list_harta  = [];
        foreach ($jenis_HARTA as $key) {
            $list_harta[$key->ID_JENIS_HARTA] = $key->NAMA;
        }
        //jenis harta bergerak lain
        $list_harta_berhenti =[
            '1' => 'Perabotan Rumah Tangga',
            '2' => 'Barang Elektronik',
            '3' => 'Perhiasan & Logam / Batu Mulia',
            '4' => 'Barang Seni / Antik',
            '5' => 'Persediaan',
            '6' => 'Harta Bergerak Lainnya',
        ];
        //jenis harta surat berharga
        $list_harta_surat   = [
            '1' => 'Penyertaan Modal pada Badan Hukum',
            '2' => 'Investasi',
        ];
        //jenis harta kas
        $list_harta_kas     = [
            '1' => 'Uang Tunai',
            '2' => 'Deposite',
            '3' => 'Giro',
            '4' => 'Tabungan',
            '5' => 'Lainnya',
        ];
        //jenis harta lainnya
        $list_harta_lain    = [
            '1' => 'Piutang',
            '2' => 'Kerjasama Usaha yang Tidak Berbadan Hukum',
            '3' => 'Hak Kekayaan Intelektual',
            '4' => 'Sewa Jangaka Panjang Dibayar Dimuka',
            '5' => 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan',
        ];

        $data['getGolongan1'] = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS','NAMA_GOLONGAN');
        $data['getGolongan2'] = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS','NAMA_GOLONGAN');

        $this->data['list_harta']               = $list_harta;
        $this->data['LHKPN']                    = @$this->mglobal->get_data_all(
            'T_LHKPN',
            [
                ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN   = '.'T_PN.ID_PN'],
                ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                ['table' => 'M_INST_SATKER inst', 'on' => 'jbt.LEMBAGA   =  inst.INST_SATKERKD'],
                ['table' => 'M_UNIT_KERJA unke', 'on' => 'jbt.UNIT_KERJA   =  unke.UK_ID'],
//                ['table' => 'M_BIDANG bdg', 'on' => 'unke.UK_BIDANG_ID =  bdg.BDG_ID']
            ],
            NULL,
            '*',
            "SUBSTRING(md5(T_LHKPN.ID_LHKPN), 6, 8) = '$id_lhkpn'"
        )[0];
        // display($this->data['LHKPN']);exit();
        $this->data['lhkpn_jbtn']               = $this->mglobal->get_data_all('T_LHKPN_JABATAN', [['table' => 'M_JABATAN' , 'on' => 'T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN']], NULL, 'NAMA_JABATAN, DESKRIPSI_JABATAN, TEXT_JABATAN_PUBLISH, IS_PRIMARY',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['id_lhkpn']                 = $this->data['LHKPN']->ID_LHKPN;

        $getPN 						 = $this->mglobal->get_data_all('T_LHKPN', NULL, ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id_lhkpn], '*, YEAR(TGL_LAPOR) AS THN')[0]; 
        $this->data['cekLHKPN']     = @$this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_PN' => $getPN->ID_PN, 'YEAR(TGL_LAPOR) <' => $getPN->THN], '*', NULL, ['TGL_LAPOR', 'desc'])[0];
        // harta sebelumnya
        $this->data['hartirakSeb'] = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(NILAI_PELAPORAN) as sum_hartirakSeb')[0];
        $this->data['hargerSeb']   = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(NILAI_PELAPORAN) as sum_hargerSeb')[0];
        $this->data['harger3Seb']  = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(NILAI_PELAPORAN) as sum_harger3Seb')[0];
        $this->data['subergaSeb']  = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(NILAI_PELAPORAN) as sum_subergaSeb')[0];
        $this->data['kasekaSeb']   = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(NILAI_EQUIVALEN) as sum_kasekaSeb')[0];
        $this->data['harlinSeb']  = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(NILAI_PELAPORAN) as sum_harlinSeb')[0];
        $this->data['hutangSeb']  = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, ['ID_LHKPN' => $this->data['cekLHKPN']->ID_LHKPN],'sum(SALDO_HUTANG) as sum_hutangSeb')[0];
         // echo $this->db->last_query();exit;    

        // harta sesudahnya
        $this->data['hartirak']                 = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_TIDAK_BERGERAK','NILAI_PELAPORAN','sum_hartirak')[0];
        $this->data['harger']                   = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_BERGERAK','NILAI_PELAPORAN','sum_harger')[0];
        $this->data['harger2']                  = @$this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_LAINNYA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harger2')[0];
        // echo $this->db->last_query();exit;
        $this->data['harger3']                  = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_BERGERAK_LAIN',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harger3')[0];
        $this->data['suberga']                  = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_SURAT_BERHARGA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_suberga')[0];
        $this->data['kaseka']                   = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_KAS',"REPLACE(NILAI_EQUIVALEN,'.','')",'sum_kaseka')[0];
        $this->data['harlin']                   = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HARTA_LAINNYA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harlin')[0];
        $this->data['_hutang']                  = $this->mlhkpn->summaryHarta($this->data['id_lhkpn'],'T_LHKPN_HUTANG','SALDO_HUTANG','sum_hutang')[0];
        $this->data['getGolongan1']             = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS','NAMA_GOLONGAN');
        $this->data['getGolongan2']             = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS','NAMA_GOLONGAN');
        $this->data['DATA_PRIBADI']             = @$this->mglobal->get_data_all('T_LHKPN_DATA_PRIBADI', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'")[0];

        $selectJabatan = 'T_LHKPN_JABATAN.*, M_INST_SATKER.*, M_UNIT_KERJA.UK_NAMA, M_JABATAN.NAMA_JABATAN';
        $joinJabatan = [
            ['table'=>'M_INST_SATKER', 'on'=>'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],
            ['table'=>'M_UNIT_KERJA', 'on'=>'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA'],
            ['table'=>'M_JABATAN', 'on'=>'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
        ];
        $this->data['JABATANS'] 		= $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "SUBSTRING(md5(T_LHKPN_JABATAN.ID_LHKPN), 6, 8) = '$id_lhkpn'");

        $this->data['lembaga']                  = @$this->mglobal->get_data_all('M_INST_SATKER', NULL, NULL, '*', NULL);
        $this->data['rinci_keluargas']          = $this->mlhkpnkeluarga->get_rincian($this->data['id_lhkpn']);
        $this->data['KELUARGAS']                = $this->mglobal->get_data_all('T_LHKPN_KELUARGA', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_TIDAK_BERGERAKS']    = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $joinHARTA_TIDAK_BERGERAK, NULL,  [$selectHARTA_TIDAK_BERGERAK, FALSE], $where_eHARTA_TIDAK_BERGERAK);
        $this->data['HARTA_BERGERAKS']          = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', $joinMATA_UANG, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_BERGERAK_LAINS']     = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', $joinMU, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_SURAT_BERHARGAS']    = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', $joinMATA_UANG, NULL, "*,REPLACE(NILAI_PELAPORAN,'.','') as PELAPORAN",  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_KASS']               = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', $joinMATA_UANG, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['HARTA_LAINNYAS']           = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', $joinMU, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // display($this->data['HARTA_LAINNYAS']);exit();
        $this->data['HUTANGS']                  = $this->mglobal->get_data_all('T_LHKPN_HUTANG', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['PENERIMAAN_KASS']          = $this->mlhkpn->getGol('M_GOLONGAN_PENERIMAAN_KAS','NAMA_GOLONGAN');
        $this->data['PENGELUARAN_KASS']         = $this->mlhkpn->getGol('M_GOLONGAN_PENGELUARAN_KAS','NAMA_GOLONGAN');
        $this->data['lamp2s']                   = $this->mglobal->get_data_all('T_LHKPN_FASILITAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $this->data['keluargas']                = $this->mlhkpnkeluarga->get_paged_list($this->limit, $this->offset, array('ID_LHKPN'=>$id_lhkpn))->result();
        $this->data['dokpendukungs']            = $this->mlhkpndokpendukung->get_paged_list($this->limit, $this->offset, array('ID_LHKPN'=>$id_lhkpn))->result();
        $this->data['asalusul']                 = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER',  NULL);

        //select lampiran pelepasan
        $selectlampiranpelepasan                = 'A.TANGGAL_TRANSAKSI as TANGGAL_TRANSAKSI, A.NILAI_PELEPASAN as NILAI_PELEPASAN, A.NAMA as NAMA, A.ALAMAT as ALAMAT';
        $selectpelepasanhartatidakbergerak      = ', B.ATAS_NAMA as ATAS_NAMA, B.LUAS_TANAH as LUAS_TANAH, B.LUAS_BANGUNAN as LUAS_BANGUNAN, B.NOMOR_BUKTI as NOMOR_BUKTI, B.JENIS_BUKTI as JENIS_BUKTI ';
        $selectpelepasanhartabergerak           = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA as ATAS_NAMA, B.MEREK as MEREK, B.NOPOL_REGISTRASI as NOPOL_REGISTRASI, B.NOMOR_BUKTI as NOMOR_BUKTI';
        $selectpelepasanhartabergeraklain       = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, ATAS_NAMA as ATAS_NAMA';
        $selectpelepasansuratberharga           = ', B.KODE_JENIS as KODE_JENIS, B.NAMA_SURAT_BERHARGA as NAMA_SURAT,  B.JUMLAH as JUMLAH, B.SATUAN as SATUAN, B.ATAS_NAMA as ATAS_NAMA';
        $selectpelepasankas                     = ', B.KODE_JENIS as KODE_JENIS, B.ATAS_NAMA_REKENING as ATAS_NAMA, B.NAMA_BANK as NAMA_BANK, B.NOMOR_REKENING as NOMOR_REKENING';
        $selectpelepasanhartalainnya            = ', B.KODE_JENIS as KODE_JENIS, B.NAMA as NAMA_HARTA, B.ATAS_NAMA as ATAS_NAMA';

        // call data lampiran pelepasan
        $pelepasanhartatidakbergerak            = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK as A' , [['table' => 'T_LHKPN_HARTA_TIDAK_BERGERAK as B'   , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartatidakbergerak,  "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasanhartabergerak                 = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK as A'       , [['table' => 'T_LHKPN_HARTA_BERGERAK as B'         , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartabergerak,  "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasanhartabergeraklain             = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN as A'  , [['table' => 'T_LHKPN_HARTA_BERGERAK_LAIN as B'    , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartabergeraklain,  "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasansuratberharga                 = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA as A' , [['table' => 'T_LHKPN_HARTA_SURAT_BERHARGA as B'   , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasansuratberharga,  "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasankas                           = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_KAS as A'            , [['table' => 'T_LHKPN_HARTA_KAS as B'              , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasankas,  "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasanhartalainnya                  = $this->mglobal->get_data_all('T_LHKPN_PELEPASAN_HARTA_LAINNYA as A'        , [['table' => 'T_LHKPN_HARTA_LAINNYA as B'          , 'on' => 'A.ID_HARTA   = '.'B.ID']], NULL, $selectlampiranpelepasan.$selectpelepasanhartalainnya,  "SUBSTRING(md5(A.ID_LHKPN), 6, 8) = '$id_lhkpn'");
        $pelepasan                              = [];

        //packing hasil calling data lampiran pelepasan
        if (!empty($pelepasanhartatidakbergerak)) {
            foreach ($pelepasanhartatidakbergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS'        => 'Tanah / Bangunan',
                    'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA'      => "Tanah/Bangunan , Atas Nama ".@$key->ATAS_NAMA." dengan luas tanah ".@$key->LUAS_TANAH." dan luas bangunan ".@$key->LUAS_BANGUNAN." dengan bukti berupa ". $list_bukti[$key->JENIS_BUKTI]." dengan nomor bukti ".@$key->NOMOR_BUKTI,
                    'ALAMAT'            => $key->ALAMAT,
                    'NILAI'             => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA'         => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartabergerak)) {
            foreach ($pelepasanhartabergerak as $key) {
                $pelepasan[] = [
                    'KODE_JENIS'        => 'Mesin / Alat transport',
                    'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA'      => "Sebuah ".$list_harta[@$key->KODE_JENIS]." , Atas Nama ".@$key->ATAS_NAMA." , merek ".@$key->MEREK." dengan nomor registrasi ".$key->NOPOL_REGISTRASI." dan nomor bukti ".@$key->NOMOR_BUKTI,
                    'ALAMAT'            => $key->ALAMAT,
                    'NILAI'             => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA'         => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartabergeraklain)) {
            foreach ($pelepasanhartabergeraklain as $key) {
                $pelepasan[] = [
                    'KODE_JENIS'        => 'Harta bergerak',
                    'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA'      => $list_harta_berhenti[@$key->KODE_JENIS]." bernama ".@$key->NAMA_HARTA." , Atas nama ".@$key->ATAS_NAMA." dengan jumlah ".@$key->JUMLAH.' '.@$key->SATUAN,
                    'ALAMAT'            => $key->ALAMAT,
                    'NILAI'             => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA'         => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasansuratberharga)) {
            foreach ($pelepasansuratberharga as $key) {
                $pelepasan[] = [
                    'KODE_JENIS'        => 'Surat berharga',
                    'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA'      => $list_harta_surat[@$key->KODE_JENIS].', Atas nama '.@$key->ATAS_NAMA.' berupa surat '.@$key->NAMA_SURAT.' dengan jumlah '.@$key->JUMLAH.' '.@$key->SATUAN,
                    'ALAMAT'            => $key->ALAMAT,
                    'NILAI'             => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA'         => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasankas)) {
            foreach ($pelepasankas as $key) {
                $pelepasan[] = [
                    'KODE_JENIS'        => 'KAS / Setara KAS',
                    'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA'      => "KAS berupa ".$list_harta_kas[@$key->KODE_JENIS].', Atas nama '.@$key->ATAS_NAMA.' pada bank '.@$key->NAMA_BANK.' dengan nomor rekening '.@$key->NOMOR_REKENING,
                    'ALAMAT'            => $key->ALAMAT,
                    'NILAI'             => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA'         => $key->NAMA,
                ];
            }
        }
        if (!empty($pelepasanhartalainnya)) {
            foreach ($pelepasanhartalainnya as $key) {
                $pelepasan[] = [
                    'KODE_JENIS'        => 'Harta lainnya',
                    'TGL_TRANSAKSI'     => $key->TANGGAL_TRANSAKSI,
                    'URAIAN_HARTA'      => "Harta lain berupa ".$list_harta_lain[@$key->KODE_JENIS].' dengan nama harta '.@$key->NAMA_HARTA.' atas nama '.@$key->ATAS_NAMA,
                    'ALAMAT'            => $key->ALAMAT,
                    'NILAI'             => $key->NILAI_PELEPASAN,
                    'PIHAK_DUA'         => $key->NAMA,
                ];
            }
        }

        $this->data['lampiran_pelepasan'] = $pelepasan;

        //perhitunganpengeluaran kas
        $whereperhitunganpengeluaran    = "WHERE IS_ACTIVE = '1' AND SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'";
        $this->data['getPenka']         = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $whereperhitunganpengeluaran);

        //perhitunganpemaasukan kas
        $whereperhitunganpemaasukan     = "WHERE IS_ACTIVE = '1' AND SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn' ";
        $this->data['getPemka']         = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $whereperhitunganpemaasukan);

        // echo "<pre>";
        // print_r ($this->data['getPenka']);
        // echo "</pre>";
        // $this->data['']  = $this->mglobal->get_data_all('T_LHKPN_PENERIMAAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // $this->data['PENGELUARAN_KASS'] = $this->mglobal->get_data_all('T_LHKPN_PENGELUARAN_KAS', NULL, NULL, '*',  "SUBSTRING(md5(ID_LHKPN), 6, 8) = '$id_lhkpn'");
        // load view

        $this->data['lampiran_hibah']       = $this->_lampiran_hibah($id_lhkpn);

        $html  = $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $this->data, true);
        $html2 = $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__).'2', $this->data, true);

        ob_clean();
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetFooter('|{PAGENO}|'); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->AddPage();
        $pdf->WriteHTML($html2);
        // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $pdf->Output();
    }

	private function _lampiran_hibah($id_lhkpn, $where = NULL)
	{
	    if(is_null($where)){
	        $where = '';
	    }
	    $result = $this->db->query("
	      SELECT
	        'Tanah / Bangunan' as kode,
	        TGL_TRANSAKSI as tgl,
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
	        TGL_TRANSAKSI as tgl,
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
	        TGL_TRANSAKSI as tgl,
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
	        TGL_TRANSAKSI as tgl,
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
	        TGL_TRANSAKSI as tgl,
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

	public function tandaterima($id_lhkpn, $tt) {
		$iniJoin = [
			['table'=>'T_PN', 'on'=>'T_PN.ID_PN = T_LHKPN.ID_PN', 'join'=>'left'],
			['table'=>'T_LHKPN_JABATAN', 'on'=>'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'join'=>'left'],
            ['table'=>'M_INST_SATKER', 'on'=>'T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'join'=>'left'],
            ['table'=>'M_UNIT_KERJA', 'on'=>'M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA', 'join'=>'left'],
            ['table'=>'M_JABATAN', 'on'=>'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN', 'join'=>'left'],
            ['table'=>'M_BIDANG', 'on'=>'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join'=>'left'],
		];
		$this->data['tt'] = ($tt = 'tts' ? 'tts' : 'tt');
		$this->data['item'] = $this->mglobal->get_data_all('T_LHKPN', $iniJoin, ['IS_PRIMARY' => '1'], '*', "SUBSTRING(md5(T_LHKPN.ID_LHKPN), 6, 8) = '$id_lhkpn'")[0];
		
		$html = $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $this->data, true);
		$this->load->library('pdf');
		$pdf = $this->pdf->load();
		//$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        $pdf->SetFooter('|{PAGENO}|');
		$pdf->WriteHTML($html); // write the HTML into the PDF
		// $pdf->Output($pdfFilePath, 'F'); // save to file because we can
		$pdf->Output();

	}
}