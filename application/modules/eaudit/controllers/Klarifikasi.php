<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Klarifikasi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('makses');
		$this->makses->initialize();
		$this->load->model('mlhkpn');
        $this->load->model('mglobal');
		$this->load->model('eaudit/Klarifikasi_model', 'klarifikasi');
		$this->load->model('ever/Verification_model', 'verification');
        $this->load->model('eaudit/Kkp_model', 'kkp');
	}
	
	function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }

	public function index($id_random, $no_st, $id_audit, $isPreview = FALSE)
	{
        $id = base64_decode(strtr($id_random, '-_~', '+/='));
        $upperli = $this->input->get('upperli');
        $bottomli = $this->input->get('bottomli');
        $data['upperli'] = $upperli ? $upperli : 'li0';
        $data['bottomli'] = $bottomli ? $bottomli : FALSE;
        unset($upperli, $bootomli);
		$data['ID_LHKPN'] = $id;
		$data['NO_ST'] = str_replace('-','/', $no_st);
		$data['icon'] = 'fa-edit';
		$data['title'] = $isPreview ? 'Preview Hasil Klarifikasi Pemeriksaan LHKPN' : 'Input Klarifikasi Pemeriksaan LHKPN';
		$breadcrumbitem[] = ['Dashboard' => 'index.php'];
        $breadcrumbitem[] = ['Pemeriksaan' => 'index.php/eaudit/periksa/index'];
        $breadcrumbitem[] = $isPreview ? ['Preview Hasil Klarifikasi Pemeriksaan' => '#'] : ['Input Klarifikasi Pemeriksaan' => '#'];
        $breadcrumbdata = [];
        foreach ($breadcrumbitem as $list) {
            $breadcrumbdata = array_merge($breadcrumbdata, $list);
        }
        $data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);

        $data['t_lhkpn'] = $this->klarifikasi->get_data_lhkpn_by_old_id($id);
        $data['t_lhkpn_pn'] = $this->klarifikasi->get_data_lhkpn_pn_by_old_id($id);
        $data['new_id_lhkpn'] = $data['t_lhkpn']->ID_LHKPN;
        $data['status_klarifikasi'] = $data['t_lhkpn']->STATUS;
        $nik_pn_master = $this->klarifikasi->get_nik_by_id_pn($data['t_lhkpn']->ID_PN);
        $NIK_Enc =$this->encrypt($nik_pn_master,'e');
        $data['NIKLengkap'] = $NIK_Enc.$nik_pn_master;
        $data['lhkpn_pribadi'] = $this->klarifikasi->get_data_pribadi_by_id($data['new_id_lhkpn']);
        $data['tampil2'] = $data['lhkpn_pribadi']->NAMA_LENGKAP . ' (' . $data['lhkpn_pribadi']->NIK . ')';
        $data['lhkpn_jabatan'] = $this->klarifikasi->get_data_jabatan_by_id($data['new_id_lhkpn']);
        $data['lhkpn_keluarga'] = $this->klarifikasi->get_data_keluarga_by_id($data['new_id_lhkpn']);
        $nik_pn = preg_replace("/[^0-9]/", "", $data['lhkpn_pribadi']->NIK);
        $agenda = date('Y', strtotime($data['t_lhkpn']->tgl_lapor)).'/P/'.$nik_pn.'/'. $data['t_lhkpn']->ID_LHKPN;
        $data['header_laporan'] = 'LHKPN : <b>' . $data['lhkpn_pribadi']->NAMA_LENGKAP . '</b> (' . $nik_pn . ') - ' . $agenda;
        $data['jenis_laporan'] = 'Jenis Laporan : Pemeriksaan<br>Tanggal/Tahun Laporan : ' . tgl_format($data['t_lhkpn']->tgl_lapor);
        $data['t_lhkpn_klarifikasi'] = $this->mglobal->get_data_by_id('T_LHKPN', 'ID_LHKPN', $data['new_id_lhkpn'],NULL,TRUE);
        $data['tanggal_klarifikasi'] = 'Tanggal Klarifikasi : <b>' . tgl_format($data['t_lhkpn_klarifikasi']->TGL_KLARIFIKASI) . '</b>';
        $data['lhkpn_htb'] = $this->klarifikasi->get_data_htb_by_id($data['new_id_lhkpn']);
        //$data['lhkpn_htb'] = $this->klarifikasi->grid_harta_tidak_bergerak($data['new_id_lhkpn']); 
        $data['asalusul'] = $this->mglobal->get_data_all('M_ASAL_USUL', NULL, NULL, 'ID_ASAL_USUL,ASAL_USUL,IS_OTHER', NULL);
        $data['pemanfaatan1'] = $this->daftar_pemanfaatan(1);
        $data['pemanfaatan2'] = $this->daftar_pemanfaatan(2);
        $data['lhkpn_harta_bergerak'] = $this->klarifikasi->get_data_harta_bergerak_by_id($data['new_id_lhkpn']);
        $data['lhkpn_harta_bergerak_lain'] = $this->klarifikasi->get_data_harta_bergerak_lain_by_id($data['new_id_lhkpn']);
        $data['lhkpn_harta_surat_berharga'] = $this->klarifikasi->get_data_harta_surat_berharga_by_id($data['new_id_lhkpn']); 
        $data['lhkpn_harta_kas'] = $this->klarifikasi->get_data_harta_kas_by_id($data['new_id_lhkpn']);
        $data['lhkpn_harta_lainnya'] = $this->klarifikasi->get_data_harta_lainnya_by_id($data['new_id_lhkpn']);
        $data['lhkpn_hutang'] = $this->klarifikasi->get_data_hutang_by_id($data['new_id_lhkpn']);
        $data['lhkpn_penerimaan_kas'] = $this->klarifikasi->get_data_penerimaan_kas_by_id($data['new_id_lhkpn']);
        $data['lhkpn_fasilitas'] = $this->klarifikasi->get_data_fasilitas_by_id($data['new_id_lhkpn']);
        $data['lhkpn_pelepasan'] = $this->lampiran_pelepasan_harta($data['new_id_lhkpn']);
        $data['lhkpn_hibah'] = $this->klarifikasi->get_data_hibah($data['new_id_lhkpn']);
        $data['id_audit'] = $id_audit;

        $data['cek_lhkpn_htb'] = $this->klarifikasi->cek_data_lama($data['new_id_lhkpn'],'t_lhkpn_harta_tidak_bergerak');
        $data['cek_lhkpn_harta_bergerak'] = $this->klarifikasi->cek_data_lama($data['new_id_lhkpn'],'t_lhkpn_harta_bergerak');
        $data['cek_lhkpn_harta_bergerak_lain'] = $this->klarifikasi->cek_data_lama($data['new_id_lhkpn'],'t_lhkpn_harta_bergerak_lain');
        $data['cek_lhkpn_harta_surat_berharga'] = $this->klarifikasi->cek_data_lama($data['new_id_lhkpn'],'t_lhkpn_harta_surat_berharga');
        $data['cek_lhkpn_harta_kas'] = $this->klarifikasi->cek_data_lama($data['new_id_lhkpn'],'t_lhkpn_harta_kas');
        $data['cek_lhkpn_harta_lainnya'] = $this->klarifikasi->cek_data_lama($data['new_id_lhkpn'],'t_lhkpn_harta_lainnya');

        $data['total_htb_old'] = $this->sum_harta($data['lhkpn_htb'], 'NILAI_PELAPORAN_OLD');
        $data['total_htb'] = $this->sum_harta($data['lhkpn_htb'], 'NILAI_PELAPORAN');
        $data['total_hb_old'] = $this->sum_harta($data['lhkpn_harta_bergerak'], 'NILAI_PELAPORAN_OLD');
        $data['total_hb'] = $this->sum_harta($data['lhkpn_harta_bergerak'], 'NILAI_PELAPORAN');
        $data['total_hbl_old'] = $this->sum_harta($data['lhkpn_harta_bergerak_lain'], 'NILAI_PELAPORAN_OLD');
        $data['total_hbl'] = $this->sum_harta($data['lhkpn_harta_bergerak_lain'], 'NILAI_PELAPORAN');
        $data['total_sb_old'] = $this->sum_harta($data['lhkpn_harta_surat_berharga'], 'NILAI_PELAPORAN_OLD');
        $data['total_sb'] = $this->sum_harta($data['lhkpn_harta_surat_berharga'], 'NILAI_PELAPORAN');
        $data['total_kas_old'] = $this->sum_harta($data['lhkpn_harta_kas'], 'NILAI_EQUIVALEN_OLD');
        $data['total_kas'] = $this->sum_harta($data['lhkpn_harta_kas'], 'NILAI_EQUIVALEN');
        $data['total_lainnya_old'] = $this->sum_harta($data['lhkpn_harta_lainnya'], 'NILAI_PELAPORAN_OLD');
        $data['total_lainnya'] = $this->sum_harta($data['lhkpn_harta_lainnya'], 'NILAI_PELAPORAN');
        $data['total_hutang_old'] = $this->sum_harta($data['lhkpn_hutang'], 'SALDO_HUTANG_OLD');
        $data['total_hutang'] = $this->sum_harta($data['lhkpn_hutang'], 'SALDO_HUTANG');
        $data['is_preview'] = $isPreview;
	
            if ($isPreview){
                $this->load->view('eaudit/klarifikasi/klarifikasi_preview_index', $data);
            }else{
                $this->load->view('eaudit/klarifikasi/klarifikasi_index', $data);
            } 
	}

    function daftar_pemanfaatan($gol) {
        $data = [];
        $this->load->model('mlhkpnharta', '', TRUE);
        $pemanfaatan = $this->mlhkpnharta->get_pemanfaatan($gol);
        foreach ($pemanfaatan as $key) {
            $data[$key->ID_PEMANFAATAN] = $key->PEMANFAATAN;
        }
        return $data;
    }

    function soft_delete($id,$datasource){
        $result = $this->klarifikasi->soft_delete_by_id($id,$datasource);
        echo $result;
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function pelepasan($id_lhkpn, $id, $mode)
    {  
        $this->load->model('mjenispelepasanharta');

        
  
        $all_pelepasan_harta = $this->mjenispelepasanharta->get_all();
        if($mode=="t_htb"){
            $table_state = "t_lhkpn_harta_tidak_bergerak";
        }elseif($mode=="t_hb"){
            $table_state = "t_lhkpn_harta_bergerak";
        }elseif($mode=="t_hb2"){
            $table_state = "t_lhkpn_harta_bergerak_lain";
        }elseif($mode=="t_sb"){
            $table_state = "t_lhkpn_harta_surat_berharga";
        }elseif($mode=="t_kas"){
            $table_state = "t_lhkpn_harta_kas";
        }elseif($mode=="t_hl"){
            $table_state = "t_lhkpn_harta_lainnya";
        }elseif($mode=="t_ht"){
            $table_state = "t_lhkpn_hutang";
        }else{
            echo "Data tidak ditemukan";
            exit;
        }

        if($mode=="t_htb"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $id_pelepasan_harta = ['4','5','6','7','8','14'];
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii0'
            ];
        }elseif($mode=="t_hb"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $id_pelepasan_harta = ['4','5','6','7','8','9','14'];
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii1'
            ];
        }elseif($mode=="t_hb2"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $id_pelepasan_harta = ['4','5','7','8','9','14'];
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii2'
            ];
        }elseif($mode=="t_sb"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $id_pelepasan_harta = ['4','5','7','10','14'];
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii3'
            ];
        }elseif($mode=="t_kas"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $id_pelepasan_harta = ['5','7','10','14'];
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii4'
            ];
        }elseif($mode=="t_hl"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $id_pelepasan_harta = ['4','5','7','10','11','12','13','14'];
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii5'
            ];
        }elseif($mode=="t_ht"){
            $data_harta = $this->mglobal->get_by_id($table_state,'ID',$id);
            $data_redirect = [
                'upperli'=>'li3',
                'bottomli'=>'lii6'
            ];
        }else{
            $id_pelepasan_harta = [];
            echo 'Data tidak dapat ditampilkan';
            exit;
        }

        $jenis_pelepasan_harta = array();
        foreach($all_pelepasan_harta as $ap){
            if(in_array ($ap->ID,$id_pelepasan_harta)){
                 array_push($jenis_pelepasan_harta,$ap);
            }
        }
        if ($mode == 't_kas') {
            $NILAI_PELAPORAN_OLD = $data_harta->NILAI_EQUIVALEN_OLD;
        } else if ($mode == 't_ht') {
            $NILAI_PELAPORAN_OLD = $data_harta->SALDO_HUTANG_OLD;
        } else {
            $NILAI_PELAPORAN_OLD = $data_harta->NILAI_PELAPORAN_OLD;
        }

        $data = array(
            'JENIS_PELEPASAN_HARTA' => $jenis_pelepasan_harta,
            'ID_LHKPN'=>base64_encode($id_lhkpn),
            'ID_HARTA'=>base64_encode($id),
            'TABLE'=>$mode,
            'NILAI_PELAPORAN_OLD'=>$NILAI_PELAPORAN_OLD,
            'DATA_HARTA'=>$data_harta,
            'REDIRECT'=>$data_redirect
        );
        
        $this->load->view('klarifikasi/form_pelepasan', $data);
    }

    public function do_pelepasan()
    {
        $mode = $this->input->post('TABLE');

        if($mode=="t_htb"){
            $TABLE = "t_lhkpn_pelepasan_harta_tidak_bergerak";
            $MAIN_TABLE = "t_lhkpn_harta_tidak_bergerak";
        }elseif($mode=="t_hb"){
            $TABLE = "t_lhkpn_pelepasan_harta_bergerak";
            $MAIN_TABLE = "t_lhkpn_harta_bergerak";
        }elseif($mode=="t_hb2"){
            $TABLE = "t_lhkpn_pelepasan_harta_bergerak_lain";
            $MAIN_TABLE = "t_lhkpn_harta_bergerak_lain";
        }elseif($mode=="t_sb"){
            $TABLE = "t_lhkpn_pelepasan_harta_surat_berharga";
            $MAIN_TABLE = "t_lhkpn_harta_surat_berharga";
        }elseif($mode=="t_kas"){
            $TABLE = "t_lhkpn_pelepasan_harta_kas";
            $MAIN_TABLE = "t_lhkpn_harta_kas";
        }elseif($mode=="t_hl"){
            $TABLE = "t_lhkpn_pelepasan_harta_lainnya";
            $MAIN_TABLE = "t_lhkpn_harta_lainnya";
        }elseif($mode=="t_ht"){
            $TABLE = "t_lhkpn_pelepasan_hutang";
            $MAIN_TABLE = "t_lhkpn_hutang";
        }else{
            echo "Data tidak ditemukan";
            exit;
        }
        
        $ID_HARTA = base64_decode($this->input->post('ID_HARTA'));
        $ID_LHKPN = base64_decode($this->input->post('ID_LHKPN'));

        if(!$ID_HARTA){
            echo 'exit';
            exit;
        }
        if(!$ID_LHKPN){
            echo 'exit';
            exit;
        }

        $tgl_transaksi = $this->input->post("TANGGAL_TRANSAKSI");
        $uraian_harta = $this->input->post('URAIAN_HARTA');
        $nilai_lepas = $this->input->post('NILAI_PELEPASAN');
        $nama = $this->input->post('NAMA');
        $alamat = $this->input->post('ALAMAT');
        $JENIS_LEPAS = $this->input->post('JENIS_PELEPASAN_HARTA');
       

        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'JENIS_PELEPASAN_HARTA' => $JENIS_LEPAS,
            'TANGGAL_TRANSAKSI' => date('Y-m-d', strtotime(str_replace('/', '-', $tgl_transaksi))),
            'URAIAN_HARTA' => $uraian_harta,
            'NILAI_PELEPASAN' => intval(str_replace('.', '', $nilai_lepas)),
            'NAMA' => $nama,
            'ALAMAT' => $alamat,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );
        

        if ($MAIN_TABLE == 't_lhkpn_harta_kas') {
            $NILAI = 'NILAI_EQUIVALEN';
        } else if ($MAIN_TABLE == 't_lhkpn_hutang') {
            $NILAI = 'SALDO_HUTANG';
        } else {
            $NILAI = 'NILAI_PELAPORAN';
        }

        $this->db->where('ID', $ID_HARTA);


        $updated = $this->db->update($MAIN_TABLE, array(
            $NILAI => 0,
            'STATUS' => '1',
            'IS_PELEPASAN' => '1',
            'IS_CHECKED' => '1',
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        ));
        if ($updated && ($JENIS_LEPAS != NULL || $JENIS_LEPAS != '') && ($tgl_transaksi != NULL || $tgl_transaksi != '') && ($uraian_harta != NULL || $uraian_harta != '') && ($nilai_lepas != NULL || $nilai_lepas != '') && ($nama != NULL || $nama != '') && ($alamat != NULL || $alamat != '')) {
//        if ($updated) {
            $save = $this->db->insert($TABLE, $data);
            if ($save) {
                echo "1";
            } else {
                echo "0";
            }
        } else {
            echo "0";
        }
    }


    function do_check_penetapan($ID,$mode) {

            if($mode=="t_htb"){
                $TABLE = "t_lhkpn_harta_tidak_bergerak";
            }elseif($mode=="t_hb"){
                $TABLE = "t_lhkpn_harta_bergerak";
            }elseif($mode=="t_hb2"){
                $TABLE = "t_lhkpn_harta_bergerak_lain";
            }elseif($mode=="t_sb"){
                $TABLE = "t_lhkpn_harta_surat_berharga";
            }elseif($mode=="t_kas"){
                $TABLE = "t_lhkpn_harta_kas";
            }elseif($mode=="t_hl"){
                $TABLE = "t_lhkpn_harta_lainnya";
            }elseif($mode=="t_ht"){
                $TABLE = "t_lhkpn_hutang";
            }else{
                $data = array(
                    'result' => 'no table selected',
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }

            //table yg menggunakan kolom pemanfaatan
            $tbl_col_manfaat = ['t_lhkpn_harta_tidak_bergerak', 't_lhkpn_harta_bergerak', 't_lhkpn_harta_bergerak_lain']; 
            
            //table yg menggunakan kolom jenis_bukti
            $tbl_col_bukti = ['t_lhkpn_harta_tidak_bergerak', 't_lhkpn_harta_bergerak']; 

            $PK = $this->pk($TABLE);
            $this->db->select('
                ' . $TABLE . '.*,
                m_asal_usul.*,
                m_mata_uang.*,
                ' . $TABLE . '.ID AS REAL_ID,
                ' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
            ');

            if(in_array($TABLE, $tbl_col_manfaat)){
                $this->db->select('m_pemanfaatan.*,'. $TABLE . '.PEMANFAATAN AS ARR_PEMANFAATAN,');
                $this->db->join('m_pemanfaatan', 'm_pemanfaatan.ID_PEMANFAATAN = ' . $TABLE . '.PEMANFAATAN ', 'left');
            }

            if(in_array($TABLE, $tbl_col_bukti)){
                $this->db->select('m_jenis_bukti.*,');
                $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
            }

            if($TABLE == 't_lhkpn_harta_tidak_bergerak'){
                $this->db->select('m_negara.*, m_area_prov.*,  m_area_kab.*,');
                $this->db->join('m_negara', 'm_negara.ID = ' . $TABLE . '.ID_NEGARA', 'LEFT');
                $this->db->join('m_area_prov', 'm_area_prov.NAME = ' . $TABLE . '.PROV', 'LEFT');
                $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = ' . $TABLE . '.KAB_KOT', 'LEFT');
            }
          
            $this->db->where($TABLE . '.' . $PK, $ID);
            $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = ' . $TABLE . '.ASAL_USUL ', 'left');
            $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
            $result = $this->db->get($TABLE)->row();


            $this->db->where('ID_HARTA', $ID);
            $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL');
            $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result();

            $data = array(
                'result' => $result,
                'asal_usul' => $asal_usul
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

    function do_penetapan($ID, $mode) {



        // t_lhkpn_harta_tidak_bergerak

        if($mode=="t_htb"){
            $TABLE = "t_lhkpn_harta_tidak_bergerak";
        }elseif($mode=="t_hb"){
            $TABLE = "t_lhkpn_harta_bergerak";
        }elseif($mode=="t_hb2"){
            $TABLE = "t_lhkpn_harta_bergerak_lain";
        }elseif($mode=="t_sb"){
            $TABLE = "t_lhkpn_harta_surat_berharga";
        }elseif($mode=="t_kas"){
            $TABLE = "t_lhkpn_harta_kas";
        }elseif($mode=="t_hl"){
            $TABLE = "t_lhkpn_harta_lainnya";
        }elseif($mode=="t_ht"){
            $TABLE = "t_lhkpn_hutang";
        }else{
            echo "Data tidak ditemukan";
            exit;
        }
        $result = 0;
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        $PK = $data[0]['Column_name'];

        $this->db->where($PK, $ID);
        $check = $this->db->get($TABLE)->row();

        if ($TABLE == 't_lhkpn_harta_kas') {
            $NILAI = 'NILAI_EQUIVALEN';
            $nilai_lepas = $check->NILAI_EQUIVALEN_OLD;
        } else if ($TABLE == 't_lhkpn_hutang') {
            $NILAI = 'SALDO_HUTANG';
        } else {
            $NILAI = 'NILAI_PELAPORAN';
            $nilai_lepas = $check->NILAI_PELAPORAN_OLD;
        }



        if ($check) {
            if ($check->IS_PELEPASAN == '1') {
                $t_pelepasan = str_replace('t_lhkpn_', 't_lhkpn_pelepasan_', $TABLE);
                $this->db->where('ID_HARTA', $ID);
                $pelepasan = $this->db->get($t_pelepasan)->row();
                $nilai_lepas = $pelepasan->NILAI_PELEPASAN;
            }
        }

        $data_arr =  array(
            'STATUS' => '1',
            'IS_PELEPASAN' => '0',
            'IS_CHECKED' => '1',
            $NILAI => $nilai_lepas,
            'UPDATED_TIME' => date('Y-m-d H:i:s'),
            'UPDATED_BY' => $this->session->userdata('USERNAME'),
            'UPDATED_IP' => $this->get_client_ip(),
        );

        if($TABLE == 't_lhkpn_harta_kas'){
            $data_kas = $this->mglobal->get_data_by_id($TABLE, 'ID', $ID, NULL, TRUE);
            $data_kas_prev = $this->mglobal->get_data_by_id($TABLE, 'ID', $data_kas->Previous_ID, NULL, TRUE);
            $data_arr['NILAI_SALDO'] = $data_kas_prev->NILAI_SALDO;
            $data_arr['NILAI_EQUIVALEN'] = $data_kas->NILAI_KURS  * $data_kas_prev->NILAI_SALDO;
        }

        $this->db->where($PK, $ID);
        $id_harta = $this->db->update($TABLE, $data_arr);

        if ($id_harta) {
            $result = 1;
        }
        echo $result;
    }












    public function update_htb($id_lhkpn, $id, $mode)
    { 
        if($id == 0){
			$data['ID'] = NULL;
		}else{
			$data['ID'] = $id; 
		}
        
        $data['ID_LHKPN'] = $id_lhkpn;
        		
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_htb/new';
            $data['is_load'] = false;
        }
        else{
            $data['onAdd'] = true;
            $data['js']     = $this->edit_harta_tidak_begerak($id); 
            $data['action'] = 'do_update_htb/edit';
            $result = $this->klarifikasi->get_data_by_id($id, 'harta_tidak_bergerak'); 
            $data['harta'] =  $result['result'];

			$data['asal_usul'] = $result['asal_usul'];
            $is_load = $data['harta']->Previous_ID;
            if ($is_load != '') {
                $data['is_load'] = true;
            }
            else{
                $data['is_load'] = false;
            }
        } 
        $this->load->view('klarifikasi/form_data_htb2', $data);
    }

    public function do_update_htb($mode)
    {  
        $id = $this->input->post('ID'); 
        $is_load = $this->input->post('is_load');
		$id_lhkpn = $this->input->post('ID_LHKPN'); 
		
		$ASAL_USUL_INPUT = $this->input->post('ASAL_USUL'); 
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }
		
		$PEMANFAATAN_INPUT = $this->input->post('PEMANFAATAN');
        if (count($PEMANFAATAN_INPUT) > 1) {
            $PEMANFAATAN = join(',', $PEMANFAATAN_INPUT);
        } else {
            $PEMANFAATAN = $PEMANFAATAN_INPUT[0];
        }
		
		$data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak); 

        $ket_lainnya = $this->input->post('KET_LAINNYA_AN');

        /////// ----- cek jika atas_nama yg dipilih 1 saja ---- /////
        $cek_atas_nama = explode(",", $data_atas_nama);
        if(count($cek_atas_nama)==1){
            if($cek_atas_nama[0] == '1'){
                $data_pasangan_anak = NULL;
                $ket_lainnya = NULL;
            }elseif($cek_atas_nama[0] == '2'){
                $ket_lainnya = NULL;
            }else{
                $data_pasangan_anak = NULL;
            }
        }

        /////// ---- cek jika atas_nama yg dipilih berjumlah 2  ---- //////
        if(count($cek_atas_nama)==2){
            $array_1 = array('1', '2');
            $array_2 = array('1', '3');

            if(in_array($cek_atas_nama[0], $array_1) && in_array($cek_atas_nama[1], $array_1)){
                $ket_lainnya = NULL;
            }
            
            if(in_array($cek_atas_nama[0], $array_2) && in_array($cek_atas_nama[1], $array_2)){
                $data_pasangan_anak = NULL;
            }
        }


        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = $this->pk($TABLE);
        if (!$id) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $id);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0'; 
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

        $negara_id = '';
        if($this->input->post('NEGARA') == '1'){
            $negara_id = '2';
        }else{
            $negara_id = $this->input->post('ID_NEGARA');
        }
		
        $posted_fields = array(
            "ID_PROV" => $this->input->post('PROV'),
            "PROV" => $this->input->post('PROV_NAME'),
            "KAB_KOT" => $this->input->post('KAB_KOT_NAME'),
            "KEC" => $this->input->post('KEC'),
            "NEGARA" => $this->input->post('NEGARA'),
            "ID_NEGARA" => $negara_id,
            "KEL" => $this->input->post('KEL'),
            "JALAN" => $this->input->post('JALAN'),
            "LUAS_TANAH" => str_replace(',', '.', $this->input->post('LUAS_TANAH')),
            "LUAS_BANGUNAN" => str_replace(',', '.', $this->input->post('LUAS_BANGUNAN')),
            "JENIS_BUKTI" => $this->input->post('JENIS_BUKTI'),
            "NOMOR_BUKTI" => $this->input->post('NOMOR_BUKTI'),
			'ATAS_NAMA' => $data_atas_nama,
            'ASAL_USUL' => $ASAL_USUL,
            'PEMANFAATAN' => $PEMANFAATAN,
            'KET_LAINNYA' => $ket_lainnya,
            'PASANGAN_ANAK' => $data_pasangan_anak,
            //"ATAS_NAMA" => $this->input->post('ATAS_NAMA'),
            //"KET_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
            // "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            //"ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
            //"PEMANFAATAN" => implode(',', $this->input->post('PEMANFAATAN')),
            "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN'),
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED
        );
        if ($mode == 'new') {
			$posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
            $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_tidak_bergerak');  
            
        }
        else{
			if ($is_load == '') {
                $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
                $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            }
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_tidak_bergerak');
        } 
        //if ($update) {
        //    $result = array(
        //        'success' => 1,
        //        'msg' => 'Data Berhasil Diupdate'
        //    );
        //}
        //else{
        //    $result = array(
        //        'success' => 0,
        //        'msg' => 'Data Gagal Diupdate'
        //    );
        //} 
		
		$id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_tidak_bergerak'); 
		
        if ($id_data_harta != 0) {
            $this->delete_condition($id, $update); 
            $this->db->delete('t_lhkpn_pelepasan_harta_tidak_bergerak');  
        } 
		
        $this->delete_condition($id, $update);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak');  
        if ($update != 0) {
            if ($ASAL_USUL_INPUT) {  
                foreach ($ASAL_USUL_INPUT as $asl) { 
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $update,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        ); 
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak', $asl_array); 
						
                    }
                }
            }
            echo "1";
			//$result = array(
            //    'success' => 1,
            //    'msg' => 'Data Berhasil Diupdate'
            //);
        } else {
            echo "0";
			//$result = array(
            //    'success' => 0,
            //    'msg' => 'Data Gagal Diupdate'
            //);
        }
		//display(json_encode($result));die;
        //echo json_encode($result);
    }
    
	
	
	
	function __get_id_harta($TABLE_HARTA) {
        $this->db->select('ID_HARTA');
        $this->db->from($TABLE_HARTA);
        return $this->db->get()->num_rows();
    }
	
	function delete_condition($ID, $ID_HARTA_ASL) {
        if ($ID) { 
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
    }

    public function update_harta_bergerak($id_lhkpn, $id, $mode)
    { 
        if($id == 0){
			$data['ID'] = NULL;
		}else{
			$data['ID'] = $id; 
		}
        
		$data['ID_LHKPN'] = $id_lhkpn;
		
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_harta_bergerak/new';
            $data['is_load'] = false;
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_harta_bergerak/edit';
            //$data['harta'] = $this->klarifikasi->get_data_by_id($id, 'harta_bergerak'); 
			$result = $this->klarifikasi->get_data_by_id($id, 'harta_bergerak'); 
            $data['harta'] =  $result['result'];  //display($data['harta']);
			$data['asal_usul'] = $result['asal_usul'];
            $is_load = $data['harta']->Previous_ID;
            if ($is_load != '') {
                $data['is_load'] = true;
            }
            else{
                $data['is_load'] = false;
            }
        }
        $this->load->view('klarifikasi/form_data_harta_bergerak', $data); 
    }

    public function do_update_harta_bergerak($mode)
    { 
        $id = $this->input->post('ID');
		$is_load = $this->input->post('is_load');
        $id_lhkpn = $this->input->post('ID_LHKPN');
		
		$ASAL_USUL_INPUT = $this->input->post('ASAL_USUL'); 
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }
		
		$data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak); 

        $ket_lainnya = $this->input->post('KET_LAINNYA_AN');

        /////// ---- cek jika atas_nama yg dipilih 1 saja ---- /////
        $cek_atas_nama = explode(",", $data_atas_nama);
        if(count($cek_atas_nama)==1){
            if($cek_atas_nama[0] == '1'){
                $data_pasangan_anak = NULL;
                $ket_lainnya = NULL;
            }elseif($cek_atas_nama[0] == '2'){
                $ket_lainnya = NULL;
            }else{
                $data_pasangan_anak = NULL;
            }
        }

        /////// ---- cek jika atas_nama yg dipilih berjumlah 2  ---- //////
        if(count($cek_atas_nama)==2){
            $array_1 = array('1', '2');
            $array_2 = array('1', '3');

            if(in_array($cek_atas_nama[0], $array_1) && in_array($cek_atas_nama[1], $array_1)){
                $ket_lainnya = NULL;
            }
            
            if(in_array($cek_atas_nama[0], $array_2) && in_array($cek_atas_nama[1], $array_2)){
                $data_pasangan_anak = NULL;
            }
        }

        $TABLE = 't_lhkpn_harta_bergerak';
        $PK = $this->pk($TABLE);
        if (!$id) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $id);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0'; 
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }
		
        $posted_fields = array(
            "KODE_JENIS" => $this->input->post('KODE_JENIS'),
            "MEREK" => $this->input->post('MEREK'),
            "MODEL" => $this->input->post('MODEL'),
            "TAHUN_PEMBUATAN" => $this->input->post('TAHUN_PEMBUATAN'),
            "NOPOL_REGISTRASI" => $this->input->post('NOPOL_REGISTRASI'),
            "JENIS_BUKTI" => $this->input->post('JENIS_BUKTI'),
            "ATAS_NAMA" => $data_atas_nama,
            //"KET_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
			"KET_LAINNYA" => $ket_lainnya,
            "PASANGAN_ANAK" => $data_pasangan_anak,
            // "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            //"ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
			'ASAL_USUL' => $ASAL_USUL,
            "PEMANFAATAN" => implode(',', $this->input->post('PEMANFAATAN')),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN'),
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED
        ); 
        if ($mode == 'new') { 
            $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
            $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME'); 
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_bergerak');
        }
        else{
            if ($is_load == '') {
                $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
                $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            }
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_bergerak');
        }
        //if ($update) {
        //    $result = array(
        //        'success' => 1,
        //        'msg' => 'Data Berhasil Diupdate'
        //    );
        //}
        //else{
        //    $result = array(
        //        'success' => 0,
        //        'msg' => 'Data Gagal Diupdate'
        //    );
        //}
		
		$id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_bergerak'); 
		
        if ($id_data_harta != 0) {
            $this->delete_condition($id, $update); 
            $this->db->delete('t_lhkpn_pelepasan_harta_bergerak');  
        } 
		
        $this->delete_condition($id, $update);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_bergerak');  
        if ($update != 0) {
            if ($ASAL_USUL_INPUT) {  
                foreach ($ASAL_USUL_INPUT as $asl) { 
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $update,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        ); 
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_bergerak', $asl_array); 
						
                    }
                }
            }
            echo "1";
        } else {
            echo "0";

        }
		//display(json_encode($result));die;
        //echo json_encode($result);
    }


    public function update_harta_bergerak_lain($id_lhkpn, $id, $mode)
    {
        if($id == 0){
			$data['ID'] = null;
		}else{
			$data['ID'] = $id; 
		}
        $data['ID_LHKPN'] = $id_lhkpn; 
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_harta_bergerak_lain/new';
            $data['is_load'] = false;
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_harta_bergerak_lain/edit';
            //$data['harta'] = $this->klarifikasi->get_data_by_id($id, 'harta_bergerak_lain');
			$result = $this->klarifikasi->get_data_by_id($id, 'harta_bergerak_lain'); 
            $data['harta'] =  $result['result']; 
			$data['asal_usul'] = $result['asal_usul'];
            $is_load = $data['harta']->Previous_ID;
            if ($is_load != '') {
                $data['is_load'] = true;
            }
            else{
                $data['is_load'] = false;
            }
        }
        $this->load->view('klarifikasi/form_data_harta_bergerak_lain', $data);
    }

    public function do_update_harta_bergerak_lain($mode)
    {
        $id = $this->input->post('ID');
        $is_load = $this->input->post('is_load');
		$id_lhkpn = $this->input->post('ID_LHKPN'); 
		
        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL'); 
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }

        $TABLE = 't_lhkpn_harta_bergerak_lain';
        $PK = $this->pk($TABLE);
        if (!$id) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $id);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0'; 
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }
        $posted_fields = array(
            "KODE_JENIS" => $this->input->post('KODE_JENIS'),
            "JUMLAH" => str_replace(',', '.', $this->input->post('JUMLAH')),
            "SATUAN" => $this->input->post('SATUAN'),
            "KETERANGAN" => $this->input->post('KETERANGAN'),
            "ASAL_USUL" => $ASAL_USUL,
            // "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN'),
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED
        );
        
        if ($mode == 'new') {
            $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
            $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_bergerak_lain');
        }
        else{
            if ($is_load == '') {
                $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
                $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            }
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_bergerak_lain'); 
        }
        //if ($update) {
        //    $result = array(
        //        'success' => 1,
        //        'msg' => 'Data Berhasil Diupdate'
        //    );
        //}
        //else{
        //    $result = array(
        //        'success' => 0,
        //        'msg' => 'Data Gagal Diupdate'
        //    );
        //}
        //echo json_encode($result);
		$id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_bergerak_lain');  
		
        if ($id_data_harta != 0) {
            $this->delete_condition($id, $update); 
            $this->db->delete('t_lhkpn_pelepasan_harta_bergerak_lain');  
        } 
		
        $this->delete_condition($id, $update);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain');  
        if ($update != 0) {
            if ($ASAL_USUL_INPUT) {  
                foreach ($ASAL_USUL_INPUT as $asl) { 
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $besar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $update,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $besar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        ); 
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain', $asl_array); 
						
                    }
                }
            }
            echo "1";
        } else {
            echo "0";

        }
    }

    public function update_harta_surat_berharga($id_lhkpn = '', $id = '', $mode = '')
    { 
		if($id == 0){
			$data['ID'] = NULL;
		}else{
			$data['ID'] = $id;
		}
        
		$data['ID_LHKPN'] = $id_lhkpn;
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_harta_surat_berharga/new';
            $data['is_load'] = false;
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_harta_surat_berharga/edit';
			$result = $this->klarifikasi->get_data_by_id($id, 'harta_surat_berharga'); 
            $data['harta'] =  $result['result']; 
			$data['asal_usul'] = $result['asal_usul'];
            //$data['harta'] = $this->klarifikasi->get_data_by_id($id, 'harta_surat_berharga');
            $is_load = $data['harta']->Previous_ID;
            if ($is_load != '') {
                $data['is_load'] = true;
            }
            else{
                $data['is_load'] = false;
            }
        }
        $this->load->view('klarifikasi/form_data_harta_surat_berharga', $data);
    }

    public function do_update_harta_surat_berharga($mode)
    { 
		$TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = $this->pk($TABLE); 
        $id = $this->input->post('ID'); 
		$id_lhkpn = $this->input->post('ID_LHKPN'); 

		$ASAL_USUL_INPUT = $this->input->post('ASAL_USUL');
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }
		
		$FILE_BUKTI = NULL; 
        if ($id) {
            $this->db->where($PK, $id);
            $temp = $this->db->get($TABLE)->row();
            $upload = $this->upload_surat_berharga();
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
            $upload = $this->upload_surat_berharga();
            if ($upload['upload']) {
                $FILE_BUKTI = $upload['url'];
            }
        } 
		
		$data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak); 

        $ket_lainnya = $this->input->post('KET_LAINNYA_AN');

        /////// ----- cek jika atas_nama yg dipilih 1 saja ----- /////
        $cek_atas_nama = explode(",", $data_atas_nama);
        if(count($cek_atas_nama)==1){
            if($cek_atas_nama[0] == '1'){
                $data_pasangan_anak = NULL;
                $ket_lainnya = NULL;
            }elseif($cek_atas_nama[0] == '2'){
                $ket_lainnya = NULL;
            }else{
                $data_pasangan_anak = NULL;
            }
        }

        /////// ---- cek jika atas_nama yg dipilih berjumlah 2  ---- //////
        if(count($cek_atas_nama)==2){
            $array_1 = array('1', '2');
            $array_2 = array('1', '3');

            if(in_array($cek_atas_nama[0], $array_1) && in_array($cek_atas_nama[1], $array_1)){
                $ket_lainnya = NULL;
            }
            
            if(in_array($cek_atas_nama[0], $array_2) && in_array($cek_atas_nama[1], $array_2)){
                $data_pasangan_anak = NULL;
            }
        }
        
		
        $TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = $this->pk($TABLE);
        if (!$id) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $id);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0'; 
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

		$encrypt_norek = encrypt_username($this->input->post('NOMOR_REKENING'),'e');
		
        $posted_fields = array(
            "NOMOR_REKENING" => $encrypt_norek,
            "KODE_JENIS" => $this->input->post('KODE_JENIS'),
            "ATAS_NAMA" => $data_atas_nama,
            // "KET_LAINNYA" => $this->input->post('ATAS_NAMA_LAINNYA'),
            "KET_LAINNYA" => $ket_lainnya,
			"PASANGAN_ANAK" => $data_pasangan_anak,
            "NAMA_PENERBIT" => $this->input->post('NAMA_PENERBIT'),
            "CUSTODIAN" => $this->input->post('CUSTODIAN'),
            //"ASAL_USUL" => implode(',', $this->input->post('ASAL_USUL')),
			"ASAL_USUL" => $ASAL_USUL,
			'FILE_BUKTI' => $FILE_BUKTI,
            // "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN'),
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED
        );
        if ($mode == 'new') {
            $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
            $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_surat_berharga');
        }
        else{ 
            if ($is_load == '') {
                $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
                $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            }
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_surat_berharga');  //display($update);die();
        }
        //if ($update) {
        //    $result = array(
        //        'success' => 1,
        //        'msg' => 'Data Berhasil Diupdate'
        //    );
        //}
        //else{
        //    $result = array(
        //        'success' => 0,
        //        'msg' => 'Data Gagal Diupdate'
        //    );
        //}
        //echo json_encode($result);
		$id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_surat_berharga'); 
		
        if ($id_data_harta != 0) {
            $this->delete_condition($id, $update); 
            $this->db->delete('t_lhkpn_pelepasan_harta_surat_berharga');  
        } 
		
        $this->delete_condition($id, $update);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_surat_berharga');  
        if ($update != 0) { 
            if ($ASAL_USUL_INPUT) {  
                foreach ($ASAL_USUL_INPUT as $asl) { 
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $update,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        ); 
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_surat_berharga', $asl_array); 
						
                    }
                }
            }
            echo "1";
        } else {
            echo "0";

        }
    }
	
	    function upload_kas() {
        $result = array();
        $this->db->select('T_PN.*');
        $this->db->where('ID_LHKPN', $this->input->post('ID_LHKPN'));
        $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        $temp = $this->db->get('T_LHKPN')->row();
        $folder = $this->encrypt($temp->NIK, 'e');
        
        ///////////////////security image///////////////////
        $post_nama_file = 'file1'; 
        $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff");
        $extension_current_diijinkan = array("application","image");
        $redirect_jika_gagal = null;
        $tipe_function = "json";
        $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan,$redirect_jika_gagal,$tipe_function,$extension_current_diijinkan);
        if($check_protect){
            echo 'INGAT DOSA WAHAI PARA HACKER';
            exit;
        }
        ///////////////////security image///////////////////

        if (!file_exists('uploads/data_kas/' . $folder)) {
            mkdir('uploads/data_kas/' . $folder);
            $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_kas/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);

            /* --- IBO ADD -- */
        }
            $rst = false;
            $urllist = 'uploads/data_kas/' . $folder . '/';
            foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
                $time = time();
                $ext = end((explode(".", $_FILES['file1']['name'][$key])));
                $file_name = $key . $time . '.' . $ext;
                $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                $urllist = $urllist . $file_name . ',';
                $uploadext = '.' . strtolower($ext);

                if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                    $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
                }
            }
            $result = array('upload' => $rst, 'url' => $urllist);
            
            
            
            
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }
	
	    function upload_surat_berharga() {
        $result = array();
        $this->db->select('T_PN.*');
        $this->db->where('ID_LHKPN', $this->input->post('ID_LHKPN'));
        $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        $temp = $this->db->get('T_LHKPN')->row();
        $folder = $this->encrypt($temp->NIK, 'e');

        ///////////////////security image///////////////////
        $post_nama_file = 'file1'; 
        $extension_diijinkan = array("pdf", "jpg", "png","jpeg","tif","tiff");
        $extension_current_diijinkan = array("application","image");
        $redirect_jika_gagal = null;
        $tipe_function = "json";
        $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan,$redirect_jika_gagal,$tipe_function,$extension_current_diijinkan);
        if($check_protect){
            echo 'INGAT DOSA WAHAI PARA HACKER';
            exit;
        }
        ///////////////////security image///////////////////

        if (!file_exists('uploads/data_suratberharga/' . $folder)) {
            mkdir('uploads/data_suratberharga/' . $folder);
            $content = "Bukti Surat Berharga Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_suratberharga/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }
        
        $rst = false;
        $urllist = 'uploads/data_suratberharga/' . $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);

            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {

                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);
        
        
//         $total = count($_FILES['file1']['name']);
        
//         // Loop through each file
//         for($i=0; $i<$total; $i++) {
//             //Get the temp file path
//             $tmpFilePath = $_FILES['file1']['tmp_name'][$i];
            
//             //Make sure we have a filepath
//             if ($tmpFilePath != ""){
//                 //Setup our new file path
//                 $uploaddir = 'uploads/data_suratberharga/' .  $_FILES['file1']['name'][$i];
                
//                 //Upload the file into the temp dir
//                 if(move_uploaded_file($tmpFilePath, $uploaddir)) {
                    
//                     //Handle other code here
//                     $result = array('upload' => true, 'url' => $uploaddir);
//                 }
//             }
//         }
        
//         $tmpFilePath = $_FILES['file1']['tmp_name'];
        
//         //Make sure we have a filepath
//         if ($tmpFilePath != ""){
//             //Setup our new file path
//             $uploaddir = 'uploads/data_suratberharga/' . $total . '.'. $_FILES['file1']['name'];
            
//             //Upload the file into the temp dir
//             if(move_uploaded_file($tmpFilePath, $uploaddir)) {
                
//                 //Handle other code here
//                 $result = array('upload' => true, 'url' => $uploaddir);
//             }
//         }
        
//             if (isset($_FILES["file1"])) {
//                 foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file1']['name'][$key])));
//                     $file_name = $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         } else {
//                             $result = array('upload' => false, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                 foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file1']['name'][$key])));
//                     $file_name = $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         } else {
//                             $result = array('upload' => false, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
        //}
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

	function upload_harta_lainnya() {
        $result = array();
        $this->db->select('T_PN.*');
        $this->db->where('ID_LHKPN', $this->input->post('ID_LHKPN'));
        $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        $temp = $this->db->get('T_LHKPN')->row();
        $folder = $this->encrypt($temp->NIK, 'e');
        if (!file_exists('uploads/data_hartalainnya/' . $folder)) {
            mkdir('uploads/data_hartalainnya/' . $folder);
            $content = "Bukti Harta Lainnya Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
             $fp = fopen(FCPATH . "/uploads/data_hartalainnya/" . $folder . "/readme.txt", "wb");
             fwrite($fp, $content);
             fclose($fp);
            /* IBO UPDATE */
        }
        
        $rst = false;
        $urllist = 'uploads/data_hartalainnya/' . $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);

            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
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
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

    public function update_harta_kas($id_lhkpn, $id, $mode)
    {   
        if($id == 0){
			$data['ID'] = NULL;
		}else{
			$data['ID'] = $id; 
		}
        
		$data['ID_LHKPN'] = $id_lhkpn;
		
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_harta_kas/new';
            $data['is_load'] = false;
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_harta_kas/edit';
            //$data['harta'] = $this->klarifikasi->get_data_by_id($id, 'harta_kas');
			$result = $this->klarifikasi->get_data_by_id($id, 'harta_kas'); 
            $data['harta'] =  $result['result'];  
			$data['asal_usul'] = $result['asal_usul'];
            $is_load = $data['harta']->Previous_ID;
            if ($is_load != '') {
                $data['is_load'] = true;
            }
            else{
                $data['is_load'] = false;
            }
        } 
        $this->load->view('klarifikasi/form_data_harta_kas', $data);
    }

    public function do_update_harta_kas($mode)
    { 
		$TABLE = 't_lhkpn_harta_kas';
        $PK = $this->pk($TABLE); 
        $id = $this->input->post('ID');
		$id_lhkpn = $this->input->post('ID_LHKPN'); 
		
		$ASAL_USUL_INPUT = $this->input->post('ASAL_USUL'); 
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }
		
		$FILE_BUKTI = NULL; 
        if ($id) {
            $this->db->where($PK, $id);
            $temp = $this->db->get($TABLE)->row();
            $upload = $this->upload_kas();
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
            $upload = $this->upload_kas();
            if ($upload['upload']) {
                $FILE_BUKTI = $upload['url'];
            }
        } 
		
		$data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);

        $ket_lainnya = $this->input->post('KET_LAINNYA_AN');

        /////// ---- cek jika atas_nama yg dipilih 1 saja ---- /////
        $cek_atas_nama = explode(",", $data_atas_nama);
        if(count($cek_atas_nama)==1){
            if($cek_atas_nama[0] == '1'){
                $data_pasangan_anak = NULL;
                $ket_lainnya = NULL;
            }elseif($cek_atas_nama[0] == '2'){
                $ket_lainnya = NULL;
            }else{
                $data_pasangan_anak = NULL;
            }
        }
        
        /////// ---- cek jika atas_nama yg dipilih berjumlah 2  ---- //////
        if(count($cek_atas_nama)==2){
            $array_1 = array('1', '2');
            $array_2 = array('1', '3');

            if(in_array($cek_atas_nama[0], $array_1) && in_array($cek_atas_nama[1], $array_1)){
                $ket_lainnya = NULL;
            }
            
            if(in_array($cek_atas_nama[0], $array_2) && in_array($cek_atas_nama[1], $array_2)){
                $data_pasangan_anak = NULL;
            }
        }
		
        $TABLE = 't_lhkpn_harta_kas';
        $PK = $this->pk($TABLE);
        if (!$id) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $id);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0'; 
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

		$encrypt_namabank = encrypt_username($this->input->post('NAMA_BANK'),'e');
		$encrypt_norek = encrypt_username($this->input->post('NOMOR_REKENING'),'e');

        $posted_fields = array(
            "KODE_JENIS" => $this->input->post('KODE_JENIS'),
            "NAMA_BANK" => $encrypt_namabank,
            "NOMOR_REKENING" => $encrypt_norek,
			"ATAS_NAMA_REKENING" => $data_atas_nama,
            "ATAS_NAMA_LAINNYA" => $ket_lainnya,
            "MATA_UANG" => $this->input->post('MATA_UANG'),
            "PASANGAN_ANAK" => $data_pasangan_anak,
            "ASAL_USUL" => $ASAL_USUL,
			"TAHUN_BUKA_REKENING" => $this->input->post('TAHUN_BUKA_REKENING'),
			'FILE_BUKTI' => $FILE_BUKTI,
            "NILAI_SALDO" => str_replace('.', '', $this->input->post('NILAI_EQUIVALEN')),
            "NILAI_KURS" => str_replace('.', '', $this->input->post('NILAI_KURS')),
            "NILAI_EQUIVALEN" => str_replace('.', '', $this->input->post('NILAI_EQUIVALEN_OLD')),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN'),
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED
        );
        if ($mode == 'new') {
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_kas');
        }
        else{
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_kas');
        } 
        //if ($update) {
        //    $result = array(
        //        'success' => 1,
        //        'msg' => 'Data Berhasil Diupdate'
        //    );
        //}
        //else{
        //    $result = array(
        //        'success' => 0,
        //        'msg' => 'Data Gagal Diupdate'
        //    );
        //}
        //echo json_encode($result);
		$id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_kas'); 
		
        if ($id_data_harta != 0) {
            $this->delete_condition($id, $update); 
            $this->db->delete('t_lhkpn_pelepasan_harta_kas');  
        } 
		
        $this->delete_condition($id, $update);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_kas');  
        if ($update != 0) {
            if ($ASAL_USUL_INPUT) {  
                foreach ($ASAL_USUL_INPUT as $asl) { 
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $update,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        ); 
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_kas', $asl_array); 
						
                    }
                }
            }
            echo "1";
        } else {
            echo "0";

        }
    }

    public function update_harta_lainnya($id_lhkpn, $id, $mode)
    {
        if($id == 0){
			$data['ID'] = NULL;
		}else{
			$data['ID'] = $id; 
		}
        
		$data['ID_LHKPN'] = $id_lhkpn;
		
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_harta_lainnya/new';
            $data['is_load'] = false;
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_harta_lainnya/edit';
            //$data['harta'] = $this->klarifikasi->get_data_by_id($id, 'harta_lainnya');
			$result = $this->klarifikasi->get_data_by_id($id, 'harta_lainnya'); 
            $data['harta'] =  $result['result'];  
			$data['asal_usul'] = $result['asal_usul'];
            $is_load = $data['harta']->Previous_ID;
            if ($is_load != '') {
                $data['is_load'] = true;
            }
            else{
                $data['is_load'] = false;
            }
        } 
        $this->load->view('klarifikasi/form_data_harta_lainnya', $data);
    }

    public function do_update_harta_lainnya($mode)
    {
		$TABLE = 't_lhkpn_harta_lainnya';
        $PK = $this->pk($TABLE); 
        $id = $this->input->post('ID'); 
		$id_lhkpn = $this->input->post('ID_LHKPN'); 
		$ASAL_USUL_INPUT = $this->input->post('ASAL_USUL'); 
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }
		$FILE_BUKTI = NULL; 
        if ($id) {
            $this->db->where($PK, $id);
            $temp = $this->db->get($TABLE)->row();
            $upload = $this->upload_harta_lainnya();
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
            $upload = $this->upload_harta_lainnya();
            if ($upload['upload']) {
                $FILE_BUKTI = $upload['url'];
            }
        } 
       
        if (!$id) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $id);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0'; 
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }
		
        $posted_fields = array(
            "KODE_JENIS" => $this->input->post('KODE_JENIS'),
            "KETERANGAN" => $this->input->post('KETERANGAN'),
            "ASAL_USUL"  => $ASAL_USUL,
			'FILE_BUKTI' => $FILE_BUKTI,
            // "NILAI_PEROLEHAN" => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            "NILAI_PELAPORAN" => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            "TAHUN_PEROLEHAN_AWAL" => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN'),
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED
        ); 
        if ($id) {
			if ($is_load == '') {
                $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
                $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            }
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'harta_lainnya');
            
        }
        else{ 
            $posted_fields['NILAI_PEROLEHAN'] = str_replace('.', '', $this->input->post('NILAI_PEROLEHAN'));
            $posted_fields['NILAI_PELAPORAN_OLD'] = str_replace('.', '', $this->input->post('NILAI_PELAPORAN_OLD'));
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $update = $this->klarifikasi->update_data($posted_fields,null, 'harta_lainnya');
        } 
        //if ($update) {
        //    $result = array(
        //        'success' => 1,
        //        'msg' => 'Data Berhasil Diupdate'
        //    );
        //}
        //else{
        //    $result = array(
        //        'success' => 0,
        //        'msg' => 'Data Gagal Diupdate'
        //    );
        //}
        //echo json_encode($result);
		$id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_lainnya'); 
		
        if ($id_data_harta != 0) {
            $this->delete_condition($id, $update); 
            $this->db->delete('t_lhkpn_pelepasan_harta_lainnya');  
        } 
		
        $this->delete_condition($id, $update);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_lainnya');  
        if ($update != 0) { 
            if ($ASAL_USUL_INPUT) {  
                foreach ($ASAL_USUL_INPUT as $asl) { 
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $update,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        ); 
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_lainnya', $asl_array); 
						
                    }
                }
            }
            echo "1";
        } else {
            echo "0";

        }
    }

    public function update_hutang($id_lhkpn, $id, $mode)
    {   
        if($id == 0){
			$data['ID'] = NULL;
		}else{
			$data['ID'] = $id; 
		}
        $data['ID_LHKPN'] = $id_lhkpn;
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_hutang/new';
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_hutang/edit';
            $result = $this->klarifikasi->get_data_by_id($id, 'hutang');
            $data['hutang'] = $result['result']; 
        }
        $this->load->view('klarifikasi/form_data_hutang', $data);
    }

    public function do_update_hutang($mode)
    {
        $id = $this->input->post('ID');
        $id_lhkpn = $this->input->post('ID_LHKPN');

        $data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak); 

        $ket_lainnya = $this->input->post('ATAS_NAMA_LAINNYA');

        /////// cek jika atas_nama yg dipilih 1 saja /////
        $cek_atas_nama = explode(",", $data_atas_nama);
        if(count($cek_atas_nama)==1){
            if($cek_atas_nama[0] == '1'){
                $data_pasangan_anak = NULL;
                $ket_lainnya = NULL;
            }elseif($cek_atas_nama[0] == '2'){
                $ket_lainnya = NULL;
            }else{
                $data_pasangan_anak = NULL;
            }
        }

        /////// ---- cek jika atas_nama yg dipilih berjumlah 2  ---- //////
        if(count($cek_atas_nama)==2){
            $array_1 = array('1', '2');
            $array_2 = array('1', '3');

            if(in_array($cek_atas_nama[0], $array_1) && in_array($cek_atas_nama[1], $array_1)){
                $ket_lainnya = NULL;
            }
            
            if(in_array($cek_atas_nama[0], $array_2) && in_array($cek_atas_nama[1], $array_2)){
                $data_pasangan_anak = NULL;
            }
        }

        $posted_fields = array(
            "KODE_JENIS" => $this->input->post('KODE_JENIS'),
            "KET_LAINNYA" => $ket_lainnya,
			'ATAS_NAMA' => $data_atas_nama,
            'PASANGAN_ANAK' => $data_pasangan_anak,
            "NAMA_KREDITUR" => $this->input->post('NAMA_KREDITUR'),
            "AGUNAN" => $this->input->post('AGUNAN'),
            "AWAL_HUTANG" => str_replace('.', '', $this->input->post('AWAL_HUTANG')),
            "SALDO_HUTANG" => str_replace('.', '', $this->input->post('SALDO_HUTANG')),
            "SALDO_HUTANG_OLD" => str_replace('.', '', $this->input->post('SALDO_HUTANG_OLD')),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN')
        );
        if ($mode == 'new') {
            $posted_fields['ID_LHKPN'] = $id_lhkpn;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $update = $this->klarifikasi->insert_data($posted_fields, 'hutang');
        }
        else{
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'hutang');
        }
        if ($update) {
             echo "1";
            // $result = array(
            //     'success' => 1,
            //     'msg' => 'Data Berhasil Diupdate'
            // );
        }
        else{
            echo "0";
            // $result = array(
            //     'success' => 0,
            //     'msg' => 'Data Gagal Diupdate'
            // );
        }
        // echo json_encode($result);
    }

    public function update_fasilitas($id, $mode)
    {
        $data['ID'] = $id;
        if ($mode == 'new') {
            $data['onAdd'] = false;
            $data['action'] = 'do_update_fasilitas/new';
        }
        else{
            $data['onAdd'] = true;
            $data['action'] = 'do_update_fasilitas/edit';
            $data['fasilitas'] = $this->verification->get_data_by_id($id, 'fasilitas');
        }
        $this->load->view('klarifikasi/form_data_fasilitas', $data);
    }

    public function do_update_fasilitas($mode)
    {
        $id = $this->input->post('ID');
        $posted_fields = array(
	        "JENIS_FASILITAS" => $this->input->post('JENIS_FASILITAS'),
	        "KETERANGAN" => $this->input->post('KETERANGAN'),
	        "PEMBERI_FASILITAS" => $this->input->post('PEMBERI_FASILITAS'),
	        "KETERANGAN_LAIN" => $this->input->post('KETERANGAN_LAIN'),
            "KET_PEMERIKSAAN" => $this->input->post('KET_PEMERIKSAAN')
        );
        if ($mode == 'new') {
            $posted_fields['ID_LHKPN'] = $id;
            $posted_fields['IS_ACTIVE'] = '1';
            $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['CREATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->insert_data($posted_fields, 'fasilitas');
        }
        else{
            $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
            $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
            $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'fasilitas');
        }
        if ($update) {
            $result = array(
                'success' => 1,
                'msg' => 'Data Berhasil Diupdate'
            );
        }
        else{
            $result = array(
                'success' => 0,
                'msg' => 'Data Gagal Diupdate'
            );
        }
        echo json_encode($result);
    }

		// draft : status pemeriksaan 2 ( dalam proses )
		public function saveDraftKlarifikasi()
		{
			$id = $this->input->post('id');
			$no_st = $this->input->post('no_st');

			$posted_fields = array(
					'status_periksa' => '2', // <-- bedanya cuma disini
					'updated_by' => $this->session->userdata('USERNAME'),
					'updated_date' => date('Y-m-d H:i:s'),
					'updated_ip' => $this->get_client_ip()
			);

			$update = $this->klarifikasi->set_klarifikasi_as_final($id, $no_st, $posted_fields);
			if ($update) {
					$result = array(
							'success' => 1,
							'msg' => 'Data Berhasil Disimpan'
					);
			}
			else{
					$result = array(
							'success' => 0,
							'msg' => 'Data Gagal Disimpan'
					);
			}
			echo json_encode($result);

		}

    public function saveFinalKlarifikasi()
    {
        $id = $this->input->post('id');
				$no_st = $this->input->post('no_st');

        $posted_fields = array(
            'status_periksa' => '3',
            'updated_by' => $this->session->userdata('USERNAME'),
            'updated_date' => date('Y-m-d H:i:s'),
            'updated_ip' => $this->get_client_ip()
        );
        $update = $this->klarifikasi->set_klarifikasi_as_final($id, $no_st, $posted_fields);
        if ($update) {
            $result = array(
                'success' => 1,
                'msg' => 'Data Berhasil Disimpan'
            );
        }
        else{
            $result = array(
                'success' => 0,
                'msg' => 'Data Gagal Disimpan'
            );
        }
        echo json_encode($result);
    }

    private function lampiran_pelepasan_harta($id_lhkpn, $where = NULL) {
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

    function load_data_penerimaan($ID_LHKPN) {
        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN");
        $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_penerimaan_kas2')->result();

        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $temp = $this->db->get('m_jenis_penerimaan_kas')->result();

        $jml = array();
        $jml_old = array();
        $total = 0;
        $total_old = 0;
        $i = 1;
        foreach ($temp as $t) {
            $this->db->select('SUM(PN+PASANGAN) AS JML, SUM(PN_OLD+PASANGAN_OLD) AS JML_OLD', false);
            $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
            $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_penerimaan_kas2.ID_LHKPN");
            $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
            $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
            $this->db->where('GROUP_JENIS', $this->GOLONGAN($t->GOLONGAN));
            $temp2 = $this->db->get('t_lhkpn_penerimaan_kas2')->row();
            if ($temp2->JML || $temp2->JML_OLD) {
                $jml['SUM_' . $this->GOLONGAN($t->GOLONGAN)] = $temp2->JML;
                $jml_old['OLD_SUM_' . $this->GOLONGAN($t->GOLONGAN)] = $temp2->JML_OLD;
            } else {
                $jml['SUM_' . $this->GOLONGAN($t->GOLONGAN)] = 0;
                $jml_old['OLD_SUM_' . $this->GOLONGAN($t->GOLONGAN)] = 0;
            }


            $total += $temp2->JML;
            $total_old += $temp2->JML_OLD;
            $i++;
        }
        $jml['SUM_ALL'] = "" . $total;
        $jml_old['OLD_SUM_ALL'] = "" . $total_old;
        header('Content-Type: application/json');
        echo json_encode(array('list' => $data, 'sum' => $jml, 'sum_old' => $jml_old));exit;
    }

    function GOLONGAN($index) {
        $data = array();
        $data['1'] = 'A';
        $data['2'] = 'B';
        $data['3'] = 'C';
        $data['4'] = 'D';
        $data['5'] = 'E';
        $data['6'] = 'F';
        $data['7'] = 'G';
        $data['8'] = 'H';
        $data['9'] = 'I';
        $data['10'] = 'J';
        $data['11'] = 'K';
        $data['12'] = 'L';
        $data['13'] = 'M';
        $data['14'] = 'N';
        $data['15'] = 'O';
        $data['16'] = 'P';
        $data['17'] = 'Q';
        $data['18'] = 'R';
        $data['19'] = 'S';
        $data['20'] = 'T';
        $data['21'] = 'U';
        $data['22'] = 'V';
        $data['23'] = 'W';
        $data['24'] = 'X';
        $data['25'] = 'Y';
        $data['26'] = 'Z';
        return $data[$index];
    }

    function update_penerimaan() {

        $result = NULL;
        $ID_LHKPN = $this->input->post('ID_LHKPN');

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->delete('t_lhkpn_penerimaan_kas2');

        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $check = $this->db->get('t_lhkpn_penerimaan_kas')->result();

        $DATA = array(
            'ID_LHKPN' => $ID_LHKPN,
            'LAINNYA' => $this->input->post('LAINNYA'),
            'NILAI_PENERIMAAN_KAS_PN' => NULL,
            'NILAI_PENERIMAAN_KAS_PASANGAN' => NULL,
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('USERNAME'),
            'CREATED_IP' => $this->get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('USERNAME'),
            'UPDATED_IP' => $this->get_client_ip(),
        );

        if ($check) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $update = $this->db->update('t_lhkpn_penerimaan_kas', $DATA);
            if ($update) {
                $result = 1;
            } else {
                $result = 0;
            }
        } else {
            $save = $this->db->insert('t_lhkpn_penerimaan_kas', $DATA);
            if ($save) {
                $result = 1;
            } else {
                $result = 0;
            }
        }
        if ($result == 1) {

            $this->db->select('GOLONGAN');
            $this->db->where('IS_ACTIVE','1');
            $this->db->group_by('GOLONGAN');
            $this->db->order_by('GOLONGAN');
            $data = $this->db->get('m_jenis_penerimaan_kas')->result();

            $PN = array();
            $PASANGAN = array();
            foreach ($data as $row) {
                $this->db->where('GOLONGAN', $row->GOLONGAN);
                $this->db->where('IS_ACTIVE','1');
                $value = $this->db->get('m_jenis_penerimaan_kas')->result();
                $i = 0;
                $index = $this->GOLONGAN($row->GOLONGAN);
                foreach ($value as $rs) {
                    $PN[$index][] = array($this->GOLONGAN($rs->GOLONGAN) . '' . $i => $this->input->post($this->GOLONGAN($rs->GOLONGAN) . '' . $i));
                    $v_catatan = $this->input->post('KET_PEMERIKSAAN_' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                    if ($rs->GOLONGAN == 1) {
                        $PASANGAN[] = array('P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i => $this->input->post('P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i));
                        $v_pn = $this->input->post($this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $v_ps = $this->input->post('P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $v_pn_old = $this->input->post('OLD_'.$this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $v_ps_old = $this->input->post('OLD_P' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $child = array(
                            'ID_LHKPN' => $ID_LHKPN,
                            'GROUP_JENIS' => $this->GOLONGAN($rs->GOLONGAN),
                            'KODE_JENIS' => $this->GOLONGAN($rs->GOLONGAN) . '' . $i,
                            'JENIS_PENERIMAAN' => $rs->NAMA,
                            'PN' => intval(str_replace('.', '', $v_pn)),
                            'PASANGAN' => intval(str_replace('.', '', $v_ps)),
                            'PN_OLD' => intval(str_replace('.', '', $v_pn_old)),
                            'PASANGAN_OLD' => intval(str_replace('.', '', $v_ps_old)),
                            'KET_PEMERIKSAAN' => $v_catatan,
                        );
                        $this->db->insert('t_lhkpn_penerimaan_kas2', $child);
                    } else {
                        $v_pn = $this->input->post($this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $v_pn_old = $this->input->post('OLD_'.$this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                        $child = array(
                            'ID_LHKPN' => $ID_LHKPN,
                            'GROUP_JENIS' => $this->GOLONGAN($rs->GOLONGAN),
                            'KODE_JENIS' => $this->GOLONGAN($rs->GOLONGAN) . '' . $i,
                            'JENIS_PENERIMAAN' => $rs->NAMA,
                            'PN' => intval(str_replace('.', '', $v_pn)),
                            'PN_OLD' => intval(str_replace('.', '', $v_pn_old)),
                            'PASANGAN' => 0,
                            'PASANGAN_OLD' => 0,
                            'KET_PEMERIKSAAN' => $v_catatan,
                        );
                        $this->db->insert('t_lhkpn_penerimaan_kas2', $child);
                    }
                    $i++;
                }
            }

            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $update = $this->db->update('t_lhkpn_penerimaan_kas', array(
                'NILAI_PENERIMAAN_KAS_PN' => json_encode($PN),
                'NILAI_PENERIMAAN_KAS_PASANGAN' => json_encode($PASANGAN)
            ));
        }
        echo $result;exit;
    }

    function update_pengeluaran(){

        $result = NULL;
        $ID_LHKPN = $this->input->post('ID_LHKPN2');

        $this->db->where('ID_LHKPN',$ID_LHKPN);
        $this->db->delete('t_lhkpn_pengeluaran_kas2');

        $this->db->where('ID_LHKPN',$ID_LHKPN);
        $check = $this->db->get('t_lhkpn_pengeluaran_kas')->result();

        $DATA = array(
            'ID_LHKPN' => $ID_LHKPN,
            'NILAI_PENGELUARAN_KAS' => NULL,
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('USERNAME'),
            'CREATED_IP' => $this->get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('USERNAME'),
            'UPDATED_IP' => $this->get_client_ip(),
        );

        if($check){
            $this->db->where('ID_LHKPN',$ID_LHKPN);
            $update = $this->db->update('t_lhkpn_pengeluaran_kas',$DATA);
            if($update){
                $result = 1;
            }else{
                $result = 0;
            }
        }else{
            $save = $this->db->insert('t_lhkpn_pengeluaran_kas',$DATA);
            if($save){
                $result = 1;
            }else{
                $result = 0;
            }
        }
        if($result==1){

            $this->db->select('GOLONGAN');
                        $this->db->where('IS_ACTIVE','1');
            $this->db->group_by('GOLONGAN');
            $this->db->order_by('GOLONGAN');
            $data = $this->db->get('m_jenis_pengeluaran_kas')->result();

            $PN  = array();
            $PASANGAN = array();
            foreach($data as $row){
                $this->db->where('GOLONGAN',$row->GOLONGAN);
                                $this->db->where('IS_ACTIVE','1');
                $value = $this->db->get('m_jenis_pengeluaran_kas')->result();
                $i = 0;
                $index = $this->GOLONGAN($row->GOLONGAN);
                foreach ($value as $rs) {
                    $PN[$index][] = array($this->GOLONGAN($rs->GOLONGAN).''.$i=>$this->input->post('P_'.$this->GOLONGAN($rs->GOLONGAN).''.$i));
                    $v_pn = $this->input->post('P_'.$this->GOLONGAN($rs->GOLONGAN).''.$i);
                    $v_pn_old = $this->input->post('OLD_P_'.$this->GOLONGAN($rs->GOLONGAN).''.$i);
                    $v_catatan = $this->input->post('KET_PEMERIKSAAN2_' . $this->GOLONGAN($rs->GOLONGAN) . '' . $i);
                    $child = array(
                        'ID_LHKPN'=>$ID_LHKPN,
                        'GROUP_JENIS'=>$this->GOLONGAN($rs->GOLONGAN),
                        'KODE_JENIS'=>$this->GOLONGAN($rs->GOLONGAN).''.$i,
                        'JENIS_pengeluaran'=>$rs->NAMA,
                        'JML'=>intval(str_replace('.', '', $v_pn)),
                        'JML_OLD'=>intval(str_replace('.', '', $v_pn_old)),
                        'KET_PEMERIKSAAN' => $v_catatan,
                    );
                    $this->db->insert('t_lhkpn_pengeluaran_kas2',$child);
                    $i++;
                }

            }

            $this->db->where('ID_LHKPN',$ID_LHKPN);
            $update = $this->db->update('t_lhkpn_pengeluaran_kas',array(
                'NILAI_PENGELUARAN_KAS'=>json_encode($PN),
            ));

        }
        echo $result;exit;
    }

    function load_data_pengeluaran($ID_LHKPN){

        $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
                $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN");
                $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$ID_LHKPN);
        $data = $this->db->get('t_lhkpn_pengeluaran_kas2')->result(); 

        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $temp = $this->db->get('m_jenis_pengeluaran_kas')->result();

        $jml = array();
        $jml_old = array();
        $total = 0;
        $total_old = 0;
        $i = 1;
        foreach($temp as $t){
            $this->db->select('SUM(JML) AS JML, SUM(JML_OLD) AS JML_OLD',false);
                        $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
                        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_pengeluaran_kas2.ID_LHKPN");
                        $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
            $this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN',$ID_LHKPN);
            $this->db->where('GROUP_JENIS',$this->GOLONGAN($t->GOLONGAN));
            $temp2 = $this->db->get('t_lhkpn_pengeluaran_kas2')->row();

            if($temp2->JML || $temp2->JML_OLD){
                $jml['PSUM_'.$this->GOLONGAN($t->GOLONGAN)] = $temp2->JML;
                $jml_old['OLD_PSUM_'.$this->GOLONGAN($t->GOLONGAN)] = $temp2->JML_OLD;
            }else{
                $jml['PSUM_'.$this->GOLONGAN($t->GOLONGAN)] = 0;
                $jml_old['OLD_PSUM_'.$this->GOLONGAN($t->GOLONGAN)] = 0;
            }

            $total+=$temp2->JML;
            $total_old+=$temp2->JML_OLD;
            $i++;
        }
        $jml['PSUM_ALL'] = "".$total;
        $jml_old['OLD_PSUM_ALL'] = "".$total_old;
        header('Content-Type: application/json');
        echo json_encode(array('list'=>$data,'sum'=>$jml,'sum_old'=>$jml_old));exit;
    }

    function load_pengeluaran($index,$golongan){
        $this->db->where('IS_ACTIVE','1');
        $this->db->order_by('ID_JENIS_PENGELUARAN_KAS');
        $this->db->where('GOLONGAN',$golongan);
        $data = $this->db->get('m_jenis_pengeluaran_kas')->result();
        if($data){
            $i = 1;
            $temp = 0;
            foreach($data as $row){
                echo '
                    <tr>
                        <td rowspan="2">'.$i.'</td>
                        <td>'.$row->NAMA.'</td>
                        <td>Rp.<input type="text" name="OLD_P_'.$index.''.$temp.'" class="OLD_P_'.$index.''.$temp.' old-val" readonly /></td>
                        <td>Rp.<input type="text" id="P_'.$index.''.$temp.'" name="P_'.$index.''.$temp.'" class="table-input-text aksi-readonly input2"/></td>
                    </tr>
                    <tr>
                        <td class="pull-right">Catatan Pemeriksaan :</td>
                        <td colspan="2"><textarea rows="2" class="form-control aksi-readonly" name="KET_PEMERIKSAAN2_' . $index . $temp . '" id="KET_PEMERIKSAAN2_' . $index . $temp . '"></textarea></td>
                    </tr>
                ';
                $temp++;
                $i++;
            }
        }
        exit;
    }

    /*
        Untuk Upload Dokumen BAK
    */
    public function bak($id_random = NULL, $id_audit = NULL)
    {
        $id = base64_decode(strtr($id_random, '-_~', '+/='));
        $data['ID_LHKPN'] = $id;
        $data['icon'] = 'fa-list-ol';
        $data['title'] = 'Manajemen Dokumen Pemeriksaan';
        $breadcrumbitem[] = ['Dashboard' => 'index.php'];
        $breadcrumbitem[] = ['Pemeriksaan' => 'index.php/eaudit/periksa/index'];
        $breadcrumbitem[] = ['Manajemen Dokumen Pemeriksaan' => '#'];
        $breadcrumbdata = [];
        foreach ($breadcrumbitem as $list) {
            $breadcrumbdata = array_merge($breadcrumbdata, $list);
        }
        $data['breadcrumb'] = call_user_func('ng::genBreadcrumb', $breadcrumbdata);

        $data['t_lhkpn'] = $this->klarifikasi->get_data_lhkpn_by_old_id($id);
        $data['t_lhkpn_pn'] = $this->klarifikasi->get_data_lhkpn_pn_by_old_id($id);
        $data['new_id_lhkpn'] = $data['t_lhkpn']->ID_LHKPN;
        $data['lhkpn_pribadi'] = $this->klarifikasi->get_data_pribadi_by_id($id);
        // $data['lhkpn_pribadi'] = $this->klarifikasi->get_data_pribadi_by_id($data['new_id_lhkpn']);
        $nik_pn = preg_replace("/[^0-9]/", "", $data['lhkpn_pribadi']->NIK);
        // $agenda = date('Y', strtotime($data['t_lhkpn']->tgl_lapor)).'/P/'.$nik_pn.'/'. $data['t_lhkpn']->ID_LHKPN;
        $data['header_laporan'] = 'LHKPN : <b>' . $data['lhkpn_pribadi']->NAMA_LENGKAP . '</b> (' . $nik_pn . ')'; // . $agenda;
				// $data['jenis_laporan'] = 'Jenis Laporan : Pemeriksaan<br>Tanggal/Tahun Laporan : ' . tgl_format($data['t_lhkpn']->tgl_lapor);
        $data['jenis_laporan'] = 'Tanggal/Tahun Laporan : ' . tgl_format($data['t_lhkpn']->tgl_lapor);

        $this->load->view('eaudit/klarifikasi/upload_bak', $data);
    }

    public function saveDocuments()
    {
        $id = $this->input->post('upload_id');
        $destination = './uploads/data_pemeriksaan/'.sha1(md5($id));
        if (!is_dir($destination)) {
            mkdir($destination, 0777, TRUE);
        }
        $config['upload_path']          = $destination;
        $config['allowed_types']        = 'jpeg|jpg|png|pdf|tiff|tif|doc|docx|xls|xlsx|xlsm|zip|rar';
        $config['max_size']             = 51200;
        $path = $_FILES['userfile']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $new_name = sha1(md5(date('Y-m-d-h-i-s'))).'.'.$ext;
        $config['file_name'] = $new_name;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
                $status = "error";
                $msg = $this->upload->display_errors();
        }
        else
        {
            $posted_fields = array(
                'ID_LHKPN' => $id,
								// 'ID_AUDIT' =>
                'DOCUMENT_NAME' => $this->input->post('file_name'),
                'FILE_NAME' => $path,
                'FILE_NAME_SAFE' => $new_name,
                'PATH_FILE' => $destination,
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => date("Y-m-d H:i:s"),
                'CREATED_BY' => $this->session->userdata('USERNAME'),
                'CREATED_IP' => $this->get_client_ip(),
                'UPDATED_TIME' => date("Y-m-d H:i:s"),
                'UPDATED_BY' => $this->session->userdata('USERNAME'),
                'UPDATED_IP' => $this->get_client_ip()
            );
            $this->klarifikasi->insert_data($posted_fields, 'lampiran_bak');
            $status = "success";
            $msg = "Data Berhasil Diunggah.";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }


    public function load_file_bak($id)
    {
        $data = $this->klarifikasi->get_data_lampiran_by_id($id);
        $jml = $this->klarifikasi->count_data_by_id($id);
        if ($jml > 0) {
            $i = 1;
            foreach ($data as $list) {
                $aaData[] = array(
                    $i++,
                    $list->DOCUMENT_NAME,
                    $list->FILE_NAME,
                    '<a title="Unduh Lampiran" href="'.base_url().'eaudit/klarifikasi/download_file/'.$list->ID.'" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-download"></i></a> &nbsp; <button type="button" class="btn btn-xs btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/'.$list->ID.'/lampiran_bak" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>'
                );
            }
        }
        else{
            $aaData = array();
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
    }

    public function saveCatatan()
    {
        $id_lhkpn = $this->input->post('id_lhkpn');
        $posted_fields = array(
            'CATATAN_PEMERIKSAAN' => $this->input->post('catatan_pemeriksaan'),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('USERNAME'),
            'UPDATED_IP' => $this->get_client_ip()
        );
        $update = $this->klarifikasi->update_data($posted_fields, $id_lhkpn, 'catatan_pemeriksaan');
        if ($update) {
            echo "1";
        }
        else{
            echo "0";
        }
    }

    function download_file($param) {
        $files = $this->klarifikasi->get_data_by_id($param, 'lampiran_bak');
        $file = $files['result']->PATH_FILE.'/'.$files['result']->FILE_NAME_SAFE;
        $file_name = $files['result']->FILE_NAME;
        $ext = $files['result']->EXTENSION;
        switch($ext) {
            case 'pdf':
                $cType = 'application/pdf';
            break;

            case 'zip':
                $cType = 'application/zip';
            break;

            case 'doc':
                $cType = 'application/msword';
            break;

            case 'xls':
                $cType = 'application/vnd.ms-excel';
            break;

            case 'xlsx':
                $cType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            break;

            case 'ppt':
                $cType = 'application/vnd.ms-powerpoint';
            break;
            case 'tif':
            case 'tiff':
                $cType = 'image/tiff';
            break;
            case 'gif':
                $cType = 'image/gif';
            break;
            case 'png':
                $cType = 'image/png';
            break;
            case 'jpeg':
            case 'jpg':
                $cType = 'image/jpg';
            break;

            default:
                $cType = 'application/force-download';
            break;
        }
        if (file_exists($file)) {
            header('Content-type: '.$cType);
            header('Content-Disposition: attachment; filename="'.$file_name.'"');
            readfile($file);
        }
    }

    function load_penerimaan($index, $golongan) {
        $this->db->where('IS_ACTIVE','1');
        $this->db->order_by('ID_JENIS_PENERIMAAN_KAS');
        $this->db->where('GOLONGAN', $golongan);
        $data = $this->db->get('m_jenis_penerimaan_kas')->result();

        if ($data) {
            $i = 1;
            $temp = 0;
            foreach ($data as $row) {
                if ($golongan == 1) {
                    echo '
                        <tr>
                            <td rowspan="2">' . $i . '</td>
                            <td>' . $row->NAMA . '</td>
                            <td>Rp.<input type="text" name="OLD_' . $index . '' . $temp . '" class="' . $index . '' . $temp . ' old-val" readonly /></td>
                            <td>Rp.<input type="text" name="OLD_PA' . $temp . '" class="PA' . $temp . ' old-val" readonly /></td>
                            <td>Rp.<input type="text" id="' . $index . '' . $temp . '" name="' . $index . '' . $temp . '" class="table-input-text  input aksi-readonly"/></td>
                            <td>Rp.<input type="text" id="PA' . $temp . '" name="PA' . $temp . '" class="table-input-text  input aksi-readonly"/></td>
                        </tr>
                        <tr>
                            <td class="pull-right">Catatan Pemeriksaan :</td>
                            <td colspan="4"><textarea rows="2" class="form-control aksi-readonly" name="KET_PEMERIKSAAN_' . $index . $temp . '" id="KET_PEMERIKSAAN_' . $index . $temp . '"></textarea></td>
                        </tr>
                        ';
                } else {
                    echo '
                        <tr>
                            <td rowspan="2">' . $i . '</td>
                            <td>' . $row->NAMA . '</td>
                            <td>Rp.<input type="text" name="OLD_' . $index . '' . $temp . '" class="' . $index . '' . $temp . ' old-val" readonly /></td>
                            <td>Rp.<input type="text" id="' . $index . '' . $temp . '" name="' . $index . '' . $temp . '" class="table-input-text  input input-cuk aksi-readonly"/></td>
                        </tr>
                        <tr>
                            <td class="pull-right">Catatan Pemeriksaan :</td>
                            <td colspan="2"><textarea rows="2" class="form-control aksi-readonly" name="KET_PEMERIKSAAN_' . $index . $temp . '" id="KET_PEMERIKSAAN_' . $index . $temp . '"></textarea></td>
                        </tr>
                    ';
                }
                $temp++;
                $i++;
            }
        }
    exit;
    }

    private function edit_harta_tidak_begerak($ID) {
        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = 'ID';
        $this->db->select('
            ' . $TABLE . '.*,
            m_jenis_bukti.*,
            m_asal_usul.*,
            m_pemanfaatan.*,
            m_mata_uang.*,
            m_negara.*,
            m_area_prov.*,
            m_area_kab.*,
            ' . $TABLE . '.ID AS REAL_ID,
            ' . $TABLE . '.PEMANFAATAN AS ARR_PEMANFAATAN,
            ' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
        ');
        $this->db->where($TABLE . '.' . $PK, $ID);
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = ' . $TABLE . '.ASAL_USUL ', 'left');
        $this->db->join('m_pemanfaatan', 'm_pemanfaatan.ID_PEMANFAATAN = ' . $TABLE . '.PEMANFAATAN ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = ' . $TABLE . '.ID_NEGARA', 'LEFT');
        $this->db->join('m_area_prov', 'm_area_prov.NAME = ' . $TABLE . '.PROV', 'LEFT');
        $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = ' . $TABLE . '.KAB_KOT', 'LEFT');
        $result = $this->db->get($TABLE)->row();

        return $result;
    }


    function encrypt( $string, $action = 'e' ) {
        $secret_key = 'R@|-|a5iaKPK';
        $secret_iv = 'R@|-|a5ia|/|394124';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }

        return $output;exit;
    }

    public function cuk()
    {
        $NIK_Enc =$this->encrypt('3307020507930001','e');
        echo $NIK_Enc;
        # code...
    }

    // function form_delete($id) {
    //     $data = array(
    //         'form' => 'delete',
    //         'item' => null,
    //         'id'=>$id
    //     );
    //     $this->load->view(strtolower(__CLASS__) . '/form/form_delete', $data);
    // }


    
//////////////////////////////////////UNTUK PENARIKAN DATA OPTION/////////////////////////////////
        function get_jenis_bukti_with_data($gol = NULL) {

                $posted_data = $this->input->post('data');

                $this->db->where('IS_ACTIVE', '1');
                $this->db->order_by('ID_JENIS_BUKTI');
                if ($gol) {
                    $this->db->where('GOLONGAN_HARTA', $gol);
                }
                $data = $this->db->get('m_jenis_bukti')->result(); 
                echo "<option></option>";
                foreach ($data as $key_value => $row) {

        //            $selected = $posted_data == $row->ID_JENIS_BUKTI ? "selected" : "";
                    $selected = $posted_data == ($key_value + 1) ? "selected" : "";

        //            echo "<option " . $selected . " value='" . $row->ID_JENIS_BUKTI . "'>" . strtoupper($row->JENIS_BUKTI) . "</option>";
                    echo "<option " . $selected . " value='" . $row->ID_JENIS_BUKTI . "'>" . strtoupper($row->JENIS_BUKTI) . "</option>";
                }
                exit;
            }

        function get_pasangan_anak_by_id($table_source = '', $id_lhkpn = '') {  
            $this->db->where('ID_LHKPN', $id_lhkpn);
            $this->db->order_by('ID_KELUARGA', 'ASC');
            $data = $this->db->get('t_lhkpn_keluarga')->result(); 
            if ($data) {
                echo "<option></option>";
                $cek_1 = false; $cek_2 = false;
                foreach ($data as $row) {
                    if($row->ID_KELUARGA_LAMA != NULL){ 
                        $cek_1 = true;
                    }
                    
                     /// -- check have new keluarga -- ///
                    if($row->ID_KELUARGA_LAMA == NULL){
                        $cek_2 = true;
                    }
                    if($cek_1 && $cek_2){
                        echo "<option value='" . $row->ID_KELUARGA . "'>" . $row->NAMA . "</option>";
                    }else{
                        echo "<option value='" . $row->ID_KELUARGA_LAMA . "'>" . $row->NAMA . "</option>";
                    }
                }
            }
            exit;
        }

        function get_asal_usul_with_data_p($gol = NULL) {
        $post = $this->input->post('data');  
        $data_json = json_decode($post, true); 
		$posted_data = $data_json['asal_usul']; 
        $ID = $data_json['ID']; 
        
		if($gol == 1){
			$table2 = 't_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak';
		}elseif($gol == 2){
			$table2 = 't_lhkpn_asal_usul_pelepasan_harta_bergerak';
		}elseif($gol == 3){
			$table2 = 't_lhkpn_asal_usul_pelepasan_harta_bergerak_lain';
		}elseif($gol == 4){
			$table2 = 't_lhkpn_asal_usul_pelepasan_surat_berharga';
		}elseif($gol == 5){
			$table2 = 't_lhkpn_asal_usul_pelepasan_kas';
		}elseif($gol == 6){
			$table2 = 't_lhkpn_asal_usul_pelepasan_harta_lainnya';
		}

        $arr_data = $posted_data; 

        $value = 'ID_ASAL_USUL';
        $table = 'm_asal_usul';
        $object = 'ASAL_USUL';
        $name = 'ASAL_USUL';
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result(); 
		$this->db->where(''.$table2.'.ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = '.$table2.'.ID_ASAL_USUL');
        $asal_usul = $this->db->get($table2)->result();  	
        $i = 1;
        foreach ($data as $row) { 
			$property = str_replace(' ', '-', $row->$name); 
			$property = strtolower($property);
			$label = strtoupper($row->$name);
			$tanggal_transaksi = '';
			$besar_nilai = '';
			$keterangan = '';
			$pihak2_nama = '';
			$pihak2_alamat = '';
			$ahref = '';
			$label2 = '';
			
			foreach($asal_usul as $dom){ 
				if($row->$value == $dom->ID_ASAL_USUL){ 
					$tanggal_transaksi = $dom->TANGGAL_TRANSAKSI; 
					$besar_nilai = $dom->NILAI_PELEPASAN;
					$keterangan = $dom->URAIAN_HARTA;
					$pihak2_nama = $dom->NAMA;
					$pihak2_alamat = $dom->ALAMAT;
					$ahref = '<a href="javascript:void(0)" id="view-to-pilih-' . $property . '" class="btn btn-view btn-xs btn-info">Lihat</a>';
					$label2 = '<label class="label label-primary">'.$dom->NILAI_PELEPASAN.'</label>';
					
				}	
				
			}
				$checked = !empty($arr_data) && in_array($row->$value, $arr_data) ? "checked=checked" : ""; 
					echo '
				<tr> 
						<td><input type="checkbox" class="pilih order-' . $row->IS_OTHER . ' pilih-asal-usul" id="pilih-' . $property . '" name="' . $object . '[]" value="' . $row->$value . '" ' . $checked . ' /> ' . $i . '. ' . $label . '</td>
						<td id="view-pilih-' . $property . '">'.$ahref.'</td>
						<td id="result-pilih-' . $property . '">'.$label2.'</td>
						<input type="hidden" name="asal_tgl_transaksi[' . $row->$value . ']" id="asal-tgl_transaksi-pilih-' . $property . '" value="'.$tanggal_transaksi.'"/>
						<input type="hidden" name="asal_besar_nilai[' . $row->$value . ']" id="asal-besar_nilai-pilih-' . $property . '" value="'.$besar_nilai.'" />
						<input type="hidden" name="asal_keterangan[' . $row->$value . ']" id="asal-keterangan-pilih-' . $property . '" value="'.$keterangan.'"/>
						<input type="hidden" name="asal_pihak2_nama[' . $row->$value . ']" id="asal-pihak2_nama-pilih-' . $property . '" value="'.$pihak2_nama.'"/>
						<input type="hidden" name="asal_pihak2_alamat[' . $row->$value . ']" id="asal-pihak2_alamat-pilih-' . $property . '" value="'.$pihak2_alamat.'"/>
				</tr> ';
	
				
				
				
				$i++;

         }
        exit;
    }

    function get_pemanfaatan_with_data($golongan_harta) {

        $posted_data = $this->input->post('data');
        $arr_data = array();
        if ($posted_data) {
            $arr_data = explode(",", $posted_data);
        }

        $value = 'ID_PEMANFAATAN';
        $table = 'm_pemanfaatan';
        $object = 'PEMANFAATAN';
        $name = 'PEMANFAATAN';

        $ob = explode('x', $golongan_harta);
        if (count($ob) > 1) {
            $condition = str_replace('x', ', ', $golongan_harta);
            $this->db->like('GOLONGAN_HARTA', $condition);
        } else {
            $this->db->where('GOLONGAN_HARTA', $golongan_harta);
        }

        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        $i = 1;
        foreach ($data as $key_value => $row) {
            $property = str_replace(' ', '-', $row->$name);
            $property = strtolower($property);
            $label = strtoupper($row->$name);

//            $checked = !empty($arr_data) && in_array($row->$value, $arr_data) ? "checked=checked" : "";
            $checked = !empty($arr_data) && in_array(($key_value + 1), $arr_data) ? "checked=checked" : "";

            echo '
			   <tr>
                    <td><input type="'.($golongan_harta == 2 ? 'radio' : 'checkbox').'" class="pilih pilih-pemanfaatan set-pemanfaatan-'.$row->ID_PEMANFAATAN.'" id="pilih-pemanfaatan-' . $row->ID_PEMANFAATAN . '" name="' . $object . '[]" value="' . $row->ID_PEMANFAATAN . '" ' . $checked . ' /> ' . $i . '. ' . $label . '</td>
                    <td></td>
                    <td></td>
                </tr>
			';
            $i++;
        }
        exit;
    }

    public function send_email_klarifikasi($id_lhkpn, $id_audit)
    {
        //update status dan cetak ikhtisar dilakukan melalui jquery (view)
        //file sudah di-create saat cetak

        //get data lhkpn
        $get_data_lhkpn = $this->mail_klarifikasi_data($id_lhkpn, $id_audit);
        $get_message = $this->mail_klarifikasi_message(
            $get_data_lhkpn['lhkpn_pribadi'],
            $get_data_lhkpn['lhkpn_jabatan'],
            $get_data_lhkpn['lhkpn_audit']
        );
        //do send message
        $to = $get_data_lhkpn['lhkpn_pribadi']->EMAIL_PRIBADI;
        $cc_email = null;
        $attach = "file/wrd_gen/IkhtisarHarta-Klarifikasi" . date('d-F-Y') . $id_lhkpn. ".docx";
        $attach = file_exists($attach) ? $attach : null;
        
        if ($this->mail_klarifikasi_send($to, $get_message, false, $cc_email, $attach)){
            echo "success";
        }
    }

    function update_status_klarifikasi($id_lhkpn, $id_audit) {
        $posted_fields = array(
            'STATUS' => '3',
        );
        $where_fields = array(
            'ID_LHKPN' => $id_lhkpn,
            'JENIS_LAPORAN' => 5,
            'IS_ACTIVE' => 1,
        );
        
        $update = $this->db->update('T_LHKPN', $posted_fields, $where_fields);
        if ($update != 0) {
            echo "success";
        }
    }

	function data_harta_tetap($id_lhkpn) {
        $c_dt_harta_1 = $this->klarifikasi->cek_data_lama($id_lhkpn,'t_lhkpn_harta_tidak_bergerak');
        $c_dt_harta_2 = $this->klarifikasi->cek_data_lama($id_lhkpn,'t_lhkpn_harta_bergerak');
        $c_dt_harta_3 = $this->klarifikasi->cek_data_lama($id_lhkpn,'t_lhkpn_harta_bergerak_lain');
        $c_dt_harta_4 = $this->klarifikasi->cek_data_lama($id_lhkpn,'t_lhkpn_harta_surat_berharga');
        $c_dt_harta_5 = $this->klarifikasi->cek_data_lama($id_lhkpn,'t_lhkpn_harta_kas');
        $c_dt_harta_6 = $this->klarifikasi->cek_data_lama($id_lhkpn,'t_lhkpn_harta_lainnya');
        $data = array();
        // HARTA
        $data[1] = array(
            'index' => '1',
            'table' => 't_lhkpn_harta_tidak_bergerak',
            'notif' => 'Data Harta Tanah / Bangunan belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Tanah / Bangunan yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '?upperli=li3&bottomli=lii0',
            'title' => 'Data Harta Harta Tanah / Bangunan',
            'total_harta' => $c_dt_harta_1
        );

        $data[2] = array(
            'index' => '2',
            'table' => 't_lhkpn_harta_bergerak',
            'notif' => 'Data Harta Alat Transportasi / Mesin belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Alat Transportasi / Mesin yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '?upperli=li3&bottomli=lii1',
            'title' => 'Data Harta Alat Transportasi / Mesin',
            'total_harta' => $c_dt_harta_2
        );

        $data[3] = array(
            'index' => '3',
            'table' => 't_lhkpn_harta_bergerak_lain',
            'notif' => 'Data Harta Bergerak Lainnya belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Bergerak Lainnya yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '?upperli=li3&bottomli=lii2',
            'title' => 'Data Harta Bergerak Lainnya',
            'total_harta' => $c_dt_harta_3
        );

        $data[4] = array(
            'index' => '4',
            'table' => 't_lhkpn_harta_surat_berharga',
            'notif' => 'Data Surat Berharga belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Surat Berharga yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '?upperli=li3&bottomli=lii3',
            'title' => 'Data Harta Surat Berharga',
            'total_harta' => $c_dt_harta_4
        );

        $data[5] = array(
            'index' => '5',
            'table' => 't_lhkpn_harta_kas',
            'notif' => 'Data KAS/Setara KAS belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta KAS/Setara KAS yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '?upperli=li3&bottomli=lii4',
            'title' => 'Data Harta Kas',
            'total_harta' => $c_dt_harta_5
        );

        $data[6] = array(
            'index' => '6',
            'table' => 't_lhkpn_harta_lainnya',
            'notif' => 'Data Harta Lainnya belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.',
            'notif_status' => 'Di temukan data Harta Lainnya yang belum direview, mohon melakukan review sebelum melanjutkan.',
            'view' => '?upperli=li3&bottomli=lii5',
            'title' => 'Data Harta Lainnya',
            'total_harta' => $c_dt_harta_6
        );

        // END OF HARTA
        echo json_encode($data);
    }

    function get_jenis_harta_with_data($gol = NULL) {
        $this->db->order_by('ID_JENIS_HARTA');

        $posted_data = $this->input->post('data');

        if ($gol) {
            $this->db->where('GOLONGAN', $gol);
        }
        $data = $this->db->get('m_jenis_harta')->result();
        echo "<option></option>";
        foreach ($data as $row) {

            $selected = $posted_data == $row->ID_JENIS_HARTA ? "selected" : "";

            echo "<option " . $selected . " value='" . $row->ID_JENIS_HARTA . "'>" . strtoupper($row->NAMA) . "</option>";
        }
        exit;
    }

    function update_data_pribadi($id_lhkpn) {
        $data['data_pribadi'] = $this->klarifikasi->get_data_pribadi_by_id($id_lhkpn);
        $this->load->view('klarifikasi/form_data_pribadi', $data);
    }

    function do_update_data_pribadi() {
		$id = $this->input->post('ID');
		$tgl_lahir = $this->input->post('TANGGAL_LAHIR');
		$tgl_lahir = implode('-', array_reverse(explode('/', $tgl_lahir)));
		$posted_fields = array(
			"GELAR_DEPAN" => $this->input->post('GELAR_DEPAN'),
	        "NO_KK" => $this->input->post('NO_KK'),
	        "NIK" => $this->input->post('NIK'),
	        "NAMA_LENGKAP" => $this->input->post('NAMA_LENGKAP'),
	        "GELAR_BELAKANG" => $this->input->post('GELAR_BELAKANG'),
	        "JENIS_KELAMIN" => $this->input->post('JENIS_KELAMIN'),
	        "TEMPAT_LAHIR" => $this->input->post('TEMPAT_LAHIR'),
	        "TANGGAL_LAHIR" => $tgl_lahir,
	        "AGAMA" => $this->input->post('AGAMA'),
	        "NPWP" => $this->input->post('NPWP'),
	        "TELPON_RUMAH" => $this->input->post('TELPON_RUMAH'),
	        "EMAIL_PRIBADI" => $this->input->post('EMAIL_PRIBADI'),
	        "HP" => $this->input->post('HP'),
	        "ALAMAT_RUMAH" => $this->input->post('ALAMAT_RUMAH'),
	        "STATUS_PERKAWINAN" => $this->input->post('STATUS_PERKAWINAN'),

	        "PROVINSI" => $this->input->post('PROVINSI'),
	        "KABKOT" => $this->input->post('KABKOT_NAME'),
	        "KECAMATAN" => $this->input->post('KECAMATAN'),
            "KELURAHAN" => $this->input->post('KELURAHAN')
        );
        $update = $this->klarifikasi->update_data($posted_fields, $id, 'data_pribadi');
            if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);        
    }

	public function update_data_jabatan($id, $mode) {
	    $data['ID'] = $id;
	    if ($mode == 'new') {
	        $data['onAdd'] = false;
	        $data['lhkpn_jabatan']->edit = false;
	        $data['action'] = 'do_update_jabatan/new';
	    }
	    else{
	        $data['onAdd'] = true;
	        $data['action'] = 'do_update_jabatan/edit';
            // $data['lhkpn_jabatan'] = $this->klarifikasi->get_data_jabatan_by_id($data['new_id_lhkpn']);
            $data['lhkpn_jabatan'] = $this->klarifikasi->get_data_by_id($id, 'jabatan');

            $jabatan = $this->klarifikasi->get_data_by_id($data['lhkpn_jabatan']['result']->ID_JABATAN,'jabatan_1');
	        $data['lhkpn_jabatan']['result']->JABATAN_NAMA = $jabatan['result']->NAMA_JABATAN;
            
            $lembaga = $this->klarifikasi->get_data_by_id($jabatan['result']->INST_SATKERKD,'inst_satker');
            $data['lhkpn_jabatan']['result']->LEMBAGA = $lembaga['result']->INST_SATKERKD;
            $data['lhkpn_jabatan']['result']->LEMBAGA_NAMA = $lembaga['result']->INST_NAMA;

            $unit_kerja = $this->klarifikasi->get_data_by_id($jabatan['result']->UK_ID,'unit_kerja');
            $data['lhkpn_jabatan']['result']->UNIT_KERJA = $unit_kerja['result']->UK_ID;
            $data['lhkpn_jabatan']['result']->UNIT_KERJA_NAMA = $unit_kerja['result']->UK_NAMA;

            $sub_unit_kerja = $this->klarifikasi->get_data_by_id($jabatan['result']->SUK_ID,'sub_unit_kerja');
            $data['lhkpn_jabatan']['result']->SUB_UNIT_KERJA = $sub_unit_kerja['result']->SUK_ID;
            $data['lhkpn_jabatan']['result']->SUB_UNIT_KERJA_NAMA = $sub_unit_kerja['result']->SUK_NAMA;

	        $data['lhkpn_jabatan']['result']->edit = true;
	    }
	    $this->load->view('klarifikasi/form_data_jabatan', $data);
	}

	public function do_update_jabatan($mode) {
	    $id = $this->input->post('ID');

	    $jabatan = $this->klarifikasi->get_data_by_id($this->input->post('ID_JABATAN'),'jabatan_1');
	    $DESKRIPSI_JABATAN = $jabatan['result']->NAMA_JABATAN;
	    $posted_fields = array(
	        "LEMBAGA" => $this->input->post('LEMBAGA'),
	        "UNIT_KERJA" => $this->input->post('UNIT_KERJA'),
	        "SUB_UNIT_KERJA" => $this->input->post('SUB_UNIT_KERJA'),
	        "ID_JABATAN" => $this->input->post('ID_JABATAN'),
	        "ALAMAT_KANTOR" => $this->input->post('ALAMAT_KANTOR'),
	        "DESKRIPSI_JABATAN" => $DESKRIPSI_JABATAN
	    );

	    if ($mode == 'new') {
	        $posted_fields['ID_LHKPN'] = $id;
	        $posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
	        $posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
	        $posted_fields['CREATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->insert_data($posted_fields, 'jabatan');
	    }
	    else {
	        $posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
	        $posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
	        $posted_fields['UPDATED_IP'] = $this->get_client_ip();
            $update = $this->klarifikasi->update_data($posted_fields, $id, 'jabatan');
        }
	    if ($update) {
	        $result = array(
	            'success' => 1,
	            'msg' => 'Data Berhasil Diupdate'
	        );
	    }
	    else{
	        $result = array(
	            'success' => 0,
	            'msg' => 'Data Gagal Diupdate'
	        );
	    }
	    echo json_encode($result);
    }
    
    function delete($id,$datasource){
        $result = $this->klarifikasi->delete_by_id($id,$datasource);
	}

    function changePrimaryKey($id,$datasource,$id_lhkpn){
	    $this->verification->set_primary_null_by_id($id_lhkpn,$datasource);
        $result = $this->verification->change_primary_by_id($id,$datasource);
        
    }
    
    public function update_data_keluarga($id, $mode = 'new') {
		$data['id_lhkpn'] = $id;
        if ($mode == 'edit') {
			$data['keluarga'] = $this->verification->get_keluarga_by_id_keluarga($id);
			$data['mode'] = $mode;
            $t_lhkpn_data_pribadi = $this->verification->get_data_by_id($data['keluarga']->ID_LHKPN,'t_data_pribadi');
            if ($t_lhkpn_data_pribadi->NEGARA == 1 && substr($t_lhkpn_data_pribadi->KABKOT,0,5) == 'KOTA ')
                $alamat_tgl = $t_lhkpn_data_pribadi->ALAMAT_RUMAH . ', Kelurahan ' . $t_lhkpn_data_pribadi->KELURAHAN . ', Kecamatan ' . $t_lhkpn_data_pribadi->KECAMATAN . ', ' . $t_lhkpn_data_pribadi->KABKOT . ', Provinsi ' . $t_lhkpn_data_pribadi->PROVINSI;
            else if ($t_lhkpn_data_pribadi->NEGARA == 1)
                $alamat_tgl = $t_lhkpn_data_pribadi->ALAMAT_RUMAH . ', Kelurahan ' . $t_lhkpn_data_pribadi->KELURAHAN . ', Kecamatan ' . $t_lhkpn_data_pribadi->KECAMATAN . ', Kabupaten/Kota ' . $t_lhkpn_data_pribadi->KABKOT . ', Provinsi ' . $t_lhkpn_data_pribadi->PROVINSI;
            else 
                $alamat_tgl = $t_lhkpn_data_pribadi->ALAMAT_RUMAH;
    
            $alamat_tgl = preg_replace("/[\r\n]+/", "\n", $alamat_tgl);
            $alamat_tgl = nl2br($alamat_tgl);
            $alamat_tgl = trim(preg_replace('/\s\s+/', ' ', $alamat_tgl));
            $alamat_tgl = preg_replace('/\s+/', ' ', str_replace(array("\r\n", "\r", "\n"), ' ', trim($alamat_tgl)));
            $alamat_tgl = preg_replace('#\s+#', ' ', trim($alamat_tgl));
            $alamat_tgl = strip_tags($alamat_tgl);
            $data['alamat_rumah'] = $alamat_tgl;
		}
		else{
			$data['mode'] = $mode;
            $t_lhkpn_data_pribadi = $this->verification->get_data_by_id($id,'t_data_pribadi');
            if ($t_lhkpn_data_pribadi->NEGARA == 1 && substr($t_lhkpn_data_pribadi->KABKOT,0,5) == 'KOTA ')
                $alamat_tgl = $t_lhkpn_data_pribadi->ALAMAT_RUMAH . ', Kelurahan ' . $t_lhkpn_data_pribadi->KELURAHAN . ', Kecamatan ' . $t_lhkpn_data_pribadi->KECAMATAN . ', ' . $t_lhkpn_data_pribadi->KABKOT . ', Provinsi ' . $t_lhkpn_data_pribadi->PROVINSI;
            else if ($t_lhkpn_data_pribadi->NEGARA == 1)
                $alamat_tgl = $t_lhkpn_data_pribadi->ALAMAT_RUMAH . ', Kelurahan ' . $t_lhkpn_data_pribadi->KELURAHAN . ', Kecamatan ' . $t_lhkpn_data_pribadi->KECAMATAN . ', Kabupaten/Kota ' . $t_lhkpn_data_pribadi->KABKOT . ', Provinsi ' . $t_lhkpn_data_pribadi->PROVINSI;
            else 
                $alamat_tgl = $t_lhkpn_data_pribadi->ALAMAT_RUMAH;
    
            $alamat_tgl = preg_replace("/[\r\n]+/", "\n", $alamat_tgl);
            $alamat_tgl = nl2br($alamat_tgl);
            $alamat_tgl = trim(preg_replace('/\s\s+/', ' ', $alamat_tgl));
            $alamat_tgl = preg_replace('/\s+/', ' ', str_replace(array("\r\n", "\r", "\n"), ' ', trim($alamat_tgl)));
            $alamat_tgl = preg_replace('#\s+#', ' ', trim($alamat_tgl));
            $alamat_tgl = strip_tags($alamat_tgl);
            $data['alamat_rumah'] = $alamat_tgl;
		}
		$this->load->view('klarifikasi/form_data_keluarga', $data);
    }
    
	public function do_update_data_keluarga($mode) {
		$id = $this->input->post('ID');
		$tgl_lahir = $this->input->post('TANGGAL_LAHIR');
		$tgl_lahir = implode('-', array_reverse(explode('/', $tgl_lahir)));
		$posted_fields = array(
			"NIK" => $this->input->post('NIK'),
	        "NAMA" => $this->input->post('NAMA'),
	        "HUBUNGAN" => $this->input->post('HUBUNGAN'),
	        "TEMPAT_LAHIR" => $this->input->post('TEMPAT_LAHIR'),
	        "TANGGAL_LAHIR" => $tgl_lahir,
	        "JENIS_KELAMIN" => $this->input->post('JENIS_KELAMIN'),
	        "PEKERJAAN" => $this->input->post('PEKERJAAN'),
	        "NOMOR_TELPON" => $this->input->post('NOMOR_TELPON'),
	        "ALAMAT_RUMAH" => $this->input->post('ALAMAT_RUMAH')
        );
		if ($mode == 'new') {
			$posted_fields['ID_LHKPN'] = $id;
			$posted_fields['IS_ACTIVE'] = '1';
			$posted_fields['CREATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['CREATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['CREATED_IP'] = $this->get_client_ip();
            $update = $this->verification->insert_data_keluarga($posted_fields);
		}
		else{
			$posted_fields['UPDATED_TIME'] = date('Y-m-d H:i:s');
			$posted_fields['UPDATED_BY'] = $this->session->userdata('USERNAME');
			$posted_fields['UPDATED_IP'] = $this->get_client_ip();
			$update = $this->verification->update_data_keluarga($posted_fields, $id);
		}
		if ($update) {
			$result = array(
				'success' => 1,
				'msg' => 'Data Berhasil Diupdate'
			);
		}
		else{
			$result = array(
				'success' => 0,
				'msg' => 'Data Gagal Diupdate'
			);
		}
		echo json_encode($result);
    }
    
    ///////////////////////CETAK BERITA ACARA KLARIFIKASI
    function cetak_bak ($ID_LHKPN = FALSE, $ID_AUDIT = FALSE) {

        // initiate the database
        $id_lhkpn_old                       = $this->mglobal->get_data_by_id('T_LHKPN', 'ID_LHKPN', $ID_LHKPN, NULL, TRUE)->ID_LHKPN_PREV;
        $this->data_audit                   = $this->klarifikasi->get_data_by_id($ID_AUDIT, 'lhkpn_audit');
        $this->data_pn_prev                 = $this->kkp->get_biodata_pn($id_lhkpn_old);
        $this->data_pn                      = $this->kkp->get_biodata_pn($ID_LHKPN);
        $this->riwayat                      = $this->kkp->get_riwayat_pelaporan($ID_LHKPN, 1);
        $this->riwayat_lain                 = $this->kkp->get_riwayat_pelaporan($ID_LHKPN, 0);
        $this->data_keluarga                = $this->kkp->get_data_keluarga($ID_LHKPN);
        $this->data_tanah_bangunan          = $this->kkp->get_data_tanah_bangunan($ID_LHKPN);
        $this->data_harta_bergerak          = $this->kkp->get_data_bergerak($ID_LHKPN);
        $this->data_harta_bergerak_lainnya  = $this->kkp->get_data_bergerak_lainnya($ID_LHKPN);
        $this->data_surat_berharga          = $this->kkp->get_surat_berharga($ID_LHKPN);
        $this->data_kas                     = $this->kkp->get_data_kas($ID_LHKPN);
        $this->data_harta_lainnya           = $this->kkp->get_harta_lainnya($ID_LHKPN);
        $this->data_hutang                  = $this->kkp->get_data_hutang($ID_LHKPN);
        $this->data_penerimaan_tunai        = $this->kkp->get_data_penerimaan_tunai($ID_LHKPN);
        $this->data_pengeluaran_tunai       = $this->kkp->get_data_pengeluaran_tunai($ID_LHKPN);
        
        $id_is_ok_and_ready_print = $this->is_ever ? (bool) $ID_LHKPN : ($ID_LHKPN && $ID_AUDIT);
        if ($id_is_ok_and_ready_print) {

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/penelaahan/LembarBAK-Template.docx";
            
            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            if (!$load_template_success) {
                throw new Exception("Gagal Mencetak Data.");
                exit;
            }
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {

                // $alamat = 'JL.Kuningan Persada Kav.4 Setiabudi, Jakarta 12950 Telp.+6221 25578300';
                // $jam = '14:50';

                $tgl_cetak = date('d/m/Y');
                $jenis_laporan = ($data_pn_prev[0]->JENIS_LAPORAN == '4' ? 'PERIODIK' : ($data_pn_prev[0]->JENIS_LAPORAN == '5' ? 'KLARIFIKASI' : 'KHUSUS'));
                
                // $this->lwphpword->set_value("ALAMAT", $alamat);
                // $this->lwphpword->set_value("JAM", $jam);
                $this->lwphpword->set_value("NOMOR_BAK", isExist($this->data_audit['result']->nomor_surat_tugas));
                $this->lwphpword->set_value("TGL_BAK", isExist(indonesian_date($this->data_audit['result']->tgl_mulai_periksa, 'd F Y')));

                $this->lwphpword->set_value("NAMA_PIC", isExist($this->data_audit['result']->NAMA));

                $this->lwphpword->set_value("NAMA_LENGKAP", isExist($this->data_pn[0]->NAMA_LENGKAP));
                $this->lwphpword->set_value("TEMPAT_LAHIR", isExist(TRIM($this->data_pn[0]->TEMPAT_LAHIR)));
                $this->lwphpword->set_value("TGL_LAHIR", isExist(str_replace(' ','',indonesian_date($this->data_pn[0]->TANGGAL_LAHIR, 'd-M -Y'))));
                $this->lwphpword->set_value("JABATAN", isExist($this->data_pn[0]->NAMA_JABATAN));
                $this->lwphpword->set_value("NIK", isExist($this->data_pn[0]->NIK));
                $this->lwphpword->set_value("ALAMAT_KANTOR", isExist($this->data_pn[0]->ALAMAT_KANTOR));
                $this->lwphpword->set_value("TELP", isExist($this->data_pn[0]->HP));
                $this->lwphpword->set_value("TGL_KLARIFIKASI", isExist(indonesian_date($this->data_pn[0]->TGL_KLARIFIKASI, 'd F Y')));
                $this->lwphpword->set_value("TGL_KLARIF", isExist(str_replace('  ',' ',indonesian_date($this->data_pn[0]->TGL_KLARIFIKASI, 'd M  Y'))));
                $this->lwphpword->set_value("CATATAN", isExist($this->data_pn[0]->CATATAN_PEMERIKSAAN));
                $this->lwphpword->set_value("TGL_LAPOR", isExist(str_replace('  ',' ',indonesian_date($this->data_pn[0]->tgl_lapor, 'd M  Y'))));
                $this->lwphpword->set_value("JENIS_LAPORAN", $jenis_laporan);

                ////////////////// SET VALUE TABLE HARTA KEKAYAAN
                $TOTAL1 = $this->sum_harta($this->data_tanah_bangunan, 'NILAI_PELAPORAN_OLD') +
                    $this->sum_harta($this->data_harta_bergerak, 'NILAI_PELAPORAN_OLD') +
                    $this->sum_harta($this->data_harta_bergerak_lainnya, 'NILAI_PELAPORAN_OLD') +
                    $this->sum_harta($this->data_surat_berharga, 'NILAI_PELAPORAN_OLD') +
                    $this->sum_harta($this->data_kas, 'NILAI_EQUIVALEN_OLD') +
                    $this->sum_harta($this->data_harta_lainnya, 'NILAI_PELAPORAN_OLD');
                $TOTAL2 = $this->sum_harta($this->data_tanah_bangunan, 'NILAI_PELAPORAN') +
                    $this->sum_harta($this->data_harta_bergerak, 'NILAI_PELAPORAN') +
                    $this->sum_harta($this->data_harta_bergerak_lainnya, 'NILAI_PELAPORAN') +
                    $this->sum_harta($this->data_surat_berharga, 'NILAI_PELAPORAN') +
                    $this->sum_harta($this->data_kas, 'NILAI_EQUIVALEN') +
                    $this->sum_harta($this->data_harta_lainnya, 'NILAI_PELAPORAN');
                $TOTAL3 = $TOTAL2 - $TOTAL1;
                $SUM1 = $TOTAL1 - $this->sum_harta($this->data_hutang, 'SALDO_HUTANG_OLD');
                $SUM2 = $TOTAL2 - $this->sum_harta($this->data_hutang, 'SALDO_HUTANG');
                $SUM3 = $TOTAL3 - ($this->sum_harta($this->data_hutang, 'SALDO_HUTANG') - $this->sum_harta($this->data_hutang, 'SALDO_HUTANG_OLD'));

                $this->lwphpword->set_value("A1", $this->sum_harta($this->data_tanah_bangunan, 'NILAI_PELAPORAN_OLD', TRUE));
                $this->lwphpword->set_value("B1", $this->sum_harta($this->data_harta_bergerak, 'NILAI_PELAPORAN_OLD', TRUE));
                $this->lwphpword->set_value("C1", $this->sum_harta($this->data_harta_bergerak_lainnya, 'NILAI_PELAPORAN_OLD', TRUE));
                $this->lwphpword->set_value("D1", $this->sum_harta($this->data_surat_berharga, 'NILAI_PELAPORAN_OLD', TRUE));
                $this->lwphpword->set_value("E1", $this->sum_harta($this->data_kas, 'NILAI_EQUIVALEN_OLD', TRUE));
                $this->lwphpword->set_value("F1", $this->sum_harta($this->data_harta_lainnya, 'NILAI_PELAPORAN_OLD', TRUE));
                $this->lwphpword->set_value("G1", $this->sum_harta($this->data_hutang, 'SALDO_HUTANG_OLD', TRUE));
                $this->lwphpword->set_value("TOTAL1", $this->zeroWord(number_rupiah($TOTAL1)));
                $this->lwphpword->set_value("SUM1", $this->zeroWord(number_rupiah($SUM1)));

                $this->lwphpword->set_value("A2", $this->sum_harta($this->data_tanah_bangunan, 'NILAI_PELAPORAN', TRUE));
                $this->lwphpword->set_value("B2", $this->sum_harta($this->data_harta_bergerak, 'NILAI_PELAPORAN', TRUE));
                $this->lwphpword->set_value("C2", $this->sum_harta($this->data_harta_bergerak_lainnya, 'NILAI_PELAPORAN', TRUE));
                $this->lwphpword->set_value("D2", $this->sum_harta($this->data_surat_berharga, 'NILAI_PELAPORAN', TRUE));
                $this->lwphpword->set_value("E2", $this->sum_harta($this->data_kas, 'NILAI_EQUIVALEN', TRUE));
                $this->lwphpword->set_value("F2", $this->sum_harta($this->data_harta_lainnya, 'NILAI_PELAPORAN', TRUE));
                $this->lwphpword->set_value("G2", $this->sum_harta($this->data_hutang, 'SALDO_HUTANG', TRUE));
                $this->lwphpword->set_value("TOTAL2", $this->zeroWord(number_rupiah($TOTAL2)));
                $this->lwphpword->set_value("SUM2", $this->zeroWord(number_rupiah($SUM2)));

                $this->lwphpword->set_value("A3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_tanah_bangunan, 'NILAI_PELAPORAN') - $this->sum_harta($this->data_tanah_bangunan, 'NILAI_PELAPORAN_OLD'))));
                $this->lwphpword->set_value("B3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_harta_bergerak, 'NILAI_PELAPORAN') - $this->sum_harta($this->data_harta_bergerak, 'NILAI_PELAPORAN_OLD'))));
                $this->lwphpword->set_value("C3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_harta_bergerak_lainnya, 'NILAI_PELAPORAN') - $this->sum_harta($this->data_harta_bergerak_lainnya, 'NILAI_PELAPORAN_OLD'))));
                $this->lwphpword->set_value("D3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_surat_berharga, 'NILAI_PELAPORAN') - $this->sum_harta($this->data_surat_berharga, 'NILAI_PELAPORAN_OLD'))));
                $this->lwphpword->set_value("E3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_kas, 'NILAI_EQUIVALEN') - $this->sum_harta($this->data_kas, 'NILAI_EQUIVALEN_OLD'))));
                $this->lwphpword->set_value("F3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_harta_lainnya, 'NILAI_PELAPORAN') - $this->sum_harta($this->data_harta_lainnya, 'NILAI_PELAPORAN_OLD'))));
                $this->lwphpword->set_value("G3", $this->zeroWord(number_rupiah($this->sum_harta($this->data_hutang, 'SALDO_HUTANG') - $this->sum_harta($this->data_hutang, 'SALDO_HUTANG_OLD'))));
                $this->lwphpword->set_value("TOTAL3", $this->zeroWord(number_rupiah($TOTAL3)));
                $this->lwphpword->set_value("SUM3", $this->zeroWord(number_rupiah($SUM3)));
                
                ////////////////// SET VALUE TABLE PENERIMAAN & PENGELUARAN
                $pn = new stdClass();
                $ps = new stdClass();
                $pn_old = new stdClass();
                $ps_old = new stdClass();
                foreach ($this->data_penerimaan_tunai as $p) {
                    $jenis = substr($p->JENIS_PENERIMAAN, 0, 1);
                    $pn->$jenis += $p->NILAI_PENERIMAAN_KAS_PN;
                    $ps->$jenis += $p->NILAI_PENERIMAAN_KAS_PASANGAN;
                    $pn_old->$jenis += $p->NILAI_PENERIMAAN_KAS_PN_OLD;
                    $ps_old->$jenis += $p->NILAI_PENERIMAAN_KAS_PASANGAN_OLD;
                }

                $this->lwphpword->set_value("1A11", $this->zeroWord(number_rupiah($pn_old->A)));
                $this->lwphpword->set_value("1A21", $this->zeroWord(number_rupiah($pn_old->B)));
                $this->lwphpword->set_value("1A31", $this->zeroWord(number_rupiah($pn_old->C)));
                $this->lwphpword->set_value("1B11", $this->zeroWord(number_rupiah($ps_old->A)));
                
                $this->lwphpword->set_value("1A12", $this->zeroWord(number_rupiah($pn->A)));
                $this->lwphpword->set_value("1A22", $this->zeroWord(number_rupiah($pn->B)));
                $this->lwphpword->set_value("1A32", $this->zeroWord(number_rupiah($pn->C)));
                $this->lwphpword->set_value("1B12", $this->zeroWord(number_rupiah($ps->A)));

                $this->lwphpword->set_value("1A13", $this->zeroWord(number_rupiah($pn->A - $pn_old->A)));
                $this->lwphpword->set_value("1A23", $this->zeroWord(number_rupiah($pn->B - $pn_old->B)));
                $this->lwphpword->set_value("1A33", $this->zeroWord(number_rupiah($pn->C - $pn_old->C)));
                $this->lwphpword->set_value("1B13", $this->zeroWord(number_rupiah($ps->A - $ps_old->A)));

                $this->lwphpword->set_value("2A11", $this->sum_harta($this->data_pengeluaran_tunai, 'NILAI_PENGELUARAN_KAS_OLD', TRUE));
                $this->lwphpword->set_value("2A12", $this->sum_harta($this->data_pengeluaran_tunai, 'NILAI_PENGELUARAN_KAS', TRUE));
                $this->lwphpword->set_value("2A13", $this->zeroWord(number_rupiah($this->sum_harta($this->data_pengeluaran_tunai, 'NILAI_PENGELUARAN_KAS') - $this->sum_harta($this->data_pengeluaran_tunai, 'NILAI_PENGELUARAN_KAS_OLD'))));


                $save_document_success = $this->lwphpword->save_document();
                if ($save_document_success) {
                    $output_filename = "BAK_LHKPN" . date('d-F-Y H:i:s') . $ID_LHKPN;
                    $this->lwphpword->download($save_document_success, $output_filename);
                }
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        } else {
            redirect('portal/filing');
        }
    }

    private function sum_harta ($arr_obj, $obj, $num_format = FALSE) {
        foreach ($arr_obj as $value) {
            $total += $value->$obj;
        }
        if (!$num_format) {
            return $this->zeroWord($total);
        } else {
            return $this->zeroWord(number_rupiah($total));
        }
    }

    private function zeroWord ($data) {
        if (is_null($data) || $data == '0' || $data == 0) {
            return ' 0';
        } else {
            return $data;
        }
    }

    function nonActive ($id_lhkpn, $id_audit) {
        $posted_fields = array(
            "JENIS_PEMERIKSAAN" => '1'
        );

        $dt_audit = $this->mglobal->get_by_id('T_LHKPN_AUDIT','ID_AUDIT', $id_audit);
        $update_jenis_pemeriksaan = $this->klarifikasi->update_data($posted_fields, $dt_audit->id_lhkpn, 'audit', $dt_audit->nomor_surat_tugas);
        $posted_fields = array(
            "IS_ACTIVE" => '-1'
        );
        $update_active = $this->klarifikasi->update_data($posted_fields, $id_lhkpn, 'lhkpn');
        if ($update_jenis_pemeriksaan && $update_active) {
            echo "1";
        } else {
            echo "0";
        }
    }

  public function ajax_modal_resend() {
    $id_lhkpn = $this->input->post('id_lhkpn');
    $id_audit = $this->input->post('id_audit');
    $get_data_lhkpn = $this->mail_klarifikasi_data($id_lhkpn, $id_audit);
    $get_message = $this->mail_klarifikasi_message($get_data_lhkpn['lhkpn_pribadi'], $get_data_lhkpn['lhkpn_jabatan'], $get_data_lhkpn['lhkpn_audit']);

    $data = $get_data_lhkpn;
    $data['mail_message'] = $get_message;

    $this->load->view(strtolower(__CLASS__) . '/' . 'form_email_klarifikasi.php', $data);
  }

  private function mail_klarifikasi_data($id_lhkpn, $id_audit) {
    $t_lhkpn = $this->klarifikasi->get_data_lhkpn_by_old_id($id_lhkpn);
    $t_lhkpn_pn = $this->klarifikasi->get_data_lhkpn_pn_by_old_id($id_lhkpn);
    $new_id_lhkpn = $t_lhkpn->ID_LHKPN;
    $lhkpn_pribadi = $this->klarifikasi->get_data_pribadi_by_id($new_id_lhkpn);
    $lhkpn_audit = $this->klarifikasi->get_data_by_id($id_audit,'lhkpn_audit')["result"];

    $selectJabatan = 'T_LHKPN_JABATAN.ID_JABATAN, '
                    . 'T_LHKPN_JABATAN.IS_PRIMARY, '
                    . 'YEAR(T_LHKPN.TGL_LAPOR) AS TAHUN_LAPOR, '
                    . 'T_LHKPN.TGL_KLARIFIKASI, '
                    . 'M_INST_SATKER.INST_NAMA, '
                    . 'M_UNIT_KERJA.UK_NAMA, '
                    . 'M_JABATAN.NAMA_JABATAN, '
                    . 'M_SUB_UNIT_KERJA.SUK_NAMA';
    $joinJabatan = [
        ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_JABATAN.ID_LHKPN'],
        ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
        ['table' => 'M_INST_SATKER', 'on' => 'M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
        ['table' => 'M_UNIT_KERJA', 'on' => 'M_UNIT_KERJA.UK_ID = M_JABATAN.UK_ID'],
        ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.SUK_ID = M_JABATAN.SUK_ID'],
    ];
    $lhkpn_jabatan = $this->mglobal->get_data_all('T_LHKPN_JABATAN', $joinJabatan, NULL, $selectJabatan, "T_LHKPN_JABATAN.ID_LHKPN = '$new_id_lhkpn' AND IS_PRIMARY = '1' ", ['IS_PRIMARY', 'DESC'])[0];

    $data['lhkpn_pribadi'] = $lhkpn_pribadi;
    $data['lhkpn_audit'] = $lhkpn_audit;
    $data['lhkpn_jabatan'] = $lhkpn_jabatan;

    return $data;
  }

  public function mail_klarifikasi_send_progress($id_lhkpn, $id_audit, $cc_email = null)
  {
    //get data
    $id_lhkpn = $id_lhkpn ?: $this->input->post('id_lhkpn');
    $id_audit = $id_audit ?: $this->input->post('id_audit');
    $subject = 'Hasil Klarifikasi LHKPN';
    $get_data_lhkpn = $this->mail_klarifikasi_data($id_lhkpn, $id_audit);
    $get_message = $this->mail_klarifikasi_message($get_data_lhkpn['lhkpn_pribadi'], $get_data_lhkpn['lhkpn_jabatan'], $get_data_lhkpn['lhkpn_audit']);

    //proses send
    $to = $get_data_lhkpn['lhkpn_pribadi']->EMAIL_PRIBADI;
    $cc_email = $this->input->post('cc_email') ?: ($cc_email ?: null);
    $attach = "file/wrd_gen/IkhtisarHarta-Klarifikasi" . date('d-F-Y') . $id_lhkpn. ".docx";
    $attach = file_exists($attach) ? $attach : null;
    
    if ($this->mail_klarifikasi_send($to, $get_message, $subject, $cc_email, $attach)){
        return  true;
    } else {
        return false;
    }
  }

  private function mail_klarifikasi_send($to, $message, $subject = null, $cc = null, $attach = null)
  { 
    
         if (ng::mail_send($to, $subject, $message, null, $attach, $cc)) {
             unlink($attach);
             echo "success";
         }

//    if (ng::mail_send_queue($to, $subject, $message, NULL, NULL, $attach, $cc, NULL, NULL, NULL, TRUE)) {
//        echo "success";
//    }

  }

  private function mail_klarifikasi_message($lhkpn_pribadi, $lhkpn_jabatan, $lhkpn_audit)
  {
        $lhkpn_pribadi = (object) $lhkpn_pribadi;
        $lhkpn_jabatan = (object) $lhkpn_jabatan;
        $lhkpn_audit = (object) $lhkpn_audit;

        $message = '<table>';
        $message .= '<tr>';
        $message .= '<td>';
        $message .= '<b>Yth. Sdr.</b> ' . $lhkpn_pribadi->NAMA_LENGKAP;
        $message .= '<br/>' . $lhkpn_jabatan->NAMA_JABATAN . ' - '. $lhkpn_jabatan->SUK_NAMA .' - '. $lhkpn_jabatan->UK_NAMA;
        $message .= '<br/>' . $lhkpn_jabatan->INST_NAMA . '<br/>';
        $message .= 'Di Tempat<br/>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $message .= '<table>';
        $message .= '<tr>';
        $message .= '<td>';
        $message .= '<ol>';
        $message .= '<li value="1">Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggara Negara yang Bersih dan Bebas dari Korupsi, Kolusi, dan Nepotisme</li>';
        $message .= '<li>Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi sebagaimana telah diubah dua kali, terakhir dengan Undang-Undang Nomor 19 Tahun 2019 tentang Perubahan Kedua atas Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi;</li>';
        $message .= '<li>Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara sebagaimana diubah dengan Peraturan Komisi Pemberantasan Korupsi Nomor 02 Tahun 2020 tentang Perubahan atas Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara.</li>';
        $message .= '</ol>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $message .= '<table>';
        $message .= '<tr>';
        $message .= '<td>Berdasarkan ketentuan tersebut di atas, kami mengapresiasi kepatuhan Sdr dalam menyampaikan Laporan Harta Kekayaan Penyelenggara (LHKPN) tahun lapor '. $lhkpn_jabatan->TAHUN_LAPOR .' dengan jabatan sebagai '. $lhkpn_jabatan->NAMA_JABATAN .'. Terhadap LHKPN yang dilaporkan tersebut, kami telah melakukan klarifikasi harta kekayaan pada tanggal '. tgl_format($lhkpn_jabatan->TGL_KLARIFIKASI) .' dan terdapat rincian LHKPN yang perlu dilakukan perbaikan sebagaimana tercantum dalam Ikhtisar LHKPN pada lampiran email ini.</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $message .= '<table>';
        $message .= '<tr>';
        $message .= '<td>Menindaklanjuti hasil klarifikasi tersebut, kami harap Sdr dapat melaporkan seluruh harta kekayaan, penerimaan dan pengeluaran setiap tahun dengan kriteria sebagai berikut :</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $message .= '<table>';
        $message .= '<tr>';
        $message .= '<td>';
        $message .= '<ol>';
        $message .= '<li value="1">Harta kekayaan baik atas nama Penyelenggara Negara, Istri/Suami, Anak tanggungan ataupun atas nama pihak lain;</li>';
        $message .= '<li>Harta kekayaan tersebut belum pernah dilaporkan dalam LHKPN yang telah disampaikan kepada Komisi Pemberantasan Korupsi;</li>';
        $message .= '<li>Jumlah penerimaan dan pengeluaran tanggal 1 Januari s.d 31 Desember pada tahun lapor LHKPN.</li>';
        $message .= '</ol>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $message .= '<table>';
        $message .= '<tr>';
        $message .= '<td>Apabila diperlukan penjelasan lebih lanjut terkait dengan pemberitahuan ini, silakan menghubungi Sdr. '.$lhkpn_audit->NAMA.' pada nomor telepon (021) 2557 8300 ext '.$lhkpn_audit->HANDPHONE.' atau via e-mail ke '.$lhkpn_audit->EMAIL. '</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<br>';

        $message .= '<table>';
        $message .= '<tr>';
        $message .= '<td>';
        $message .= 'Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.<br/><br/>';
        $message .= 'Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>';
        $message .= '--------------------------------------------------------------<br/>';
        $message .= 'Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.<br/>';
        $message .= '&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        return $message;
  }


}
