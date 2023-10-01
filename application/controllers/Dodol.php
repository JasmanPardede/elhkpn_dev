<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller User
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dodol extends CI_Controller {
	public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mglobal');
        // call_user_func('ng::islogin');
        $this->uri_segment = 5;
        $this->offset      = $this->uri->segment($this->uri_segment);
    }

    /** Home
     * 
     * @return html Home
     */    
	public function index()
	{
        // echo 'sas';
				$sql = "select 
					't_lhkpn_pengeluaran_kas2' as uraian,
					(
						SELECT  max(formulirid_migrasi) FROM t_lhkpn_pengeluaran_kas2
						left join t_lhkpn on t_lhkpn_pengeluaran_kas2.id_lhkpn = t_lhkpn.id_lhkpn
					) as formidprogress,
					(SELECT  MAX(formulirid_migrasi) FROM t_lhkpn) as maxformid
				union
				select 
					't_lhkpn_penerimaan_kas2' AS uraian,
					(
						SELECT  MAX(formulirid_migrasi) FROM t_lhkpn_penerimaan_kas2 
						LEFT JOIN t_lhkpn ON t_lhkpn_penerimaan_kas2.id_lhkpn = t_lhkpn.id_lhkpn
						WHERE group_jenis = 'B'
					) AS formidprogress,
					(SELECT  MAX(formulirid_migrasi) FROM t_lhkpn) AS maxformid
				";
		
		
		//"SELECT COUNT(*) JUMLAH  FROM T_USER WHERE ID_ROLE = $param1 AND INST_SATKERKD = $param2";
		$rs = $this->db->query($sql)->result();
        // echo $rs;
		echo '
			<table border="1">
				<tr>
					<td>uraian</td>
					<td>formidprogress</td>
					<td>max_formid</td>
				</tr>
		';
		foreach ($rs as $row)
		{
			echo '
				<tr>
					<td>'.$row->uraian.'</td>
					<td>'.$row->formidprogress.'</td>
					<td>'.$row->maxformid.'</td>
				</tr>
				';
		}
		echo '
			</table>
		';
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
        
        public function PreviewAnnoun($id_lhkpn, $id_bap = NULL) {
        $datapn = $this->mglobal->get_data_all(
                        'R_BA_PENGUMUMAN', [
                    ['table' => 'T_BA_PENGUMUMAN ba', 'on' => 'R_BA_PENGUMUMAN.ID_BAP   = ' . 'ba.ID_BAP'],
                    ['table' => 'T_LHKPN', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 'R_BA_PENGUMUMAN.ID_LHKPN'],
                    ['table' => 't_lhkpn_data_pribadi', 'on' => 'T_LHKPN.ID_LHKPN   = ' . 't_lhkpn_data_pribadi.ID_LHKPN'],
                    ['table' => 'T_LHKPN_JABATAN jbt', 'on' => 'T_LHKPN.ID_LHKPN   =  jbt.ID_LHKPN'],
                    ['table' => 'M_JABATAN m_jbt', 'on' => 'm_jbt.ID_JABATAN   =  jbt.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER inst', 'on' => 'm_jbt.INST_SATKERKD   =  inst.INST_SATKERKD'],
                    ['table' => 'M_UNIT_KERJA unke', 'on' => 'm_jbt.UK_ID   =  unke.UK_ID'],
                    ['table' => 'M_BIDANG bdg', 'on' => 'inst.INST_BDG_ID =  bdg.BDG_ID'],
                    ['table' => 'T_LHKPN_HUTANG', 'on' => 'T_LHKPN.ID_LHKPN = T_LHKPN_HUTANG.ID_LHKPN AND T_LHKPN_HUTANG.IS_ACTIVE = 1'],
                    ['table' => 'T_PN', 'on' => 'T_LHKPN.ID_PN = T_PN.ID_PN'],
                    ['table' => 'T_USER', 'on' => 'T_USER.USERNAME = T_PN.NIK'],
                    ['table' => '(SELECT ID_LHKPN, COUNT(T_LHKPN_JABATAN.ID_LHKPN) AS C_TB FROM T_LHKPN_JABATAN GROUP BY ID_LHKPN  ) AS TB', 'on' => 'TB.ID_LHKPN = T_LHKPN.ID_LHKPN']
                        ], NULL, "t_lhkpn_data_pribadi.*, jbt.ALAMAT_KANTOR, jbt.DESKRIPSI_JABATAN, m_jbt.NAMA_JABATAN,, T_PN.NIK, T_PN.ID_PN,  inst.INST_NAMA, T_PN.TGL_LAHIR, T_PN.NHK,T_PN.NAMA, SUM(T_LHKPN_HUTANG.SALDO_HUTANG) AS jumhut, T_LHKPN.TGL_LAPOR, T_LHKPN.tgl_kirim_final, T_LHKPN.JENIS_LAPORAN, T_LHKPN.STATUS, bdg.BDG_KODE, bdg.BDG_NAMA, unke.UK_NAMA, ba.NOMOR_PNRI, ba.TGL_PNRI, ba.NOMOR_BAP, ba.TGL_BA_PENGUMUMAN, T_USER.ID_USER, IF (T_LHKPN.JENIS_LAPORAN = '4', 'Periodik', 'Khusus') AS JENIS, T_USER.EMAIL,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_tidak_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T1,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_surat_berharga WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T2,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_lainnya WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T3,
                        (SELECT SUM(NILAI_EQUIVALEN) FROM t_lhkpn_harta_kas WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T4,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak_lain WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T5,
                        (SELECT SUM(NILAI_PELAPORAN) FROM t_lhkpn_harta_bergerak WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_PELEPASAN = '0' AND IS_ACTIVE = '1') T6,
                        (SELECT SUM(SALDO_HUTANG) FROM t_lhkpn_hutang WHERE ID_LHKPN = `T_LHKPN`.`ID_LHKPN` AND IS_ACTIVE = '1') T7 ", "T_LHKPN.ID_LHKPN = '$id_lhkpn' AND jbt.IS_PRIMARY = '1'", NULL, 0, NULL, "T_LHKPN.ID_LHKPN"
                )[0];

        $this->data['dt_harta_tidak_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_tidak_bergerak", [
            ['table' => 'm_negara ', 'on' => 'm_negara.ID   = ' . 't_lhkpn_harta_tidak_bergerak.ID_NEGARA'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_tidak_bergerak.ASAL_USUL'],
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   IN ' . '(t_lhkpn_harta_tidak_bergerak.PEMANFAATAN)']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_tidak_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_tidak_bergerak.IS_ACTIVE = '1'", "*, GROUP_CONCAT(DISTINCT m_pemanfaatan.PEMANFAATAN) as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_tidak_bergerak.ID");

        $this->data['dt_harta_bergerak'] = $this->mglobal->get_data_all("t_lhkpn_harta_bergerak", [
            ['table' => 'm_pemanfaatan ', 'on' => 'm_pemanfaatan.ID_PEMANFAATAN   = t_lhkpn_harta_bergerak.PEMANFAATAN'],
            ['table' => 'm_asal_usul ', 'on' => 'm_asal_usul.ID_ASAL_USUL   = ' . 't_lhkpn_harta_bergerak.ASAL_USUL'],
            ['table' => 'm_jenis_harta ', 'on' => 'm_jenis_harta.ID_JENIS_HARTA   = t_lhkpn_harta_bergerak.KODE_JENIS']], "ID_LHKPN = '$id_lhkpn' AND t_lhkpn_harta_bergerak.IS_PELEPASAN = '0' AND t_lhkpn_harta_bergerak.IS_ACTIVE = '1'", "*, m_pemanfaatan.PEMANFAATAN as peruntukan", NULL, NULL, 0, NULL, "t_lhkpn_harta_bergerak.ID");
        
        $this->data['datapn'] = $datapn;
        $th = date('Y');

        $arr_dhb = array();
        $arr_dhtb = array();
        foreach ($this->data['dt_harta_bergerak'] as $data) {
            $arr_dhb[] = $data->NAMA . ', ' . $data->MEREK . ' ' . $data->MODEL . ' Tahun ' . $data->TAHUN_PEMBUATAN . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
        }
        foreach ($this->data['dt_harta_tidak_bergerak'] as $data) {
            $tmp = $data->NEGARA == 2 ? $data->JALAN . ', ' . $data->NAMA_NEGARA : ' Kota ' . $data->KAB_KOT;
            if ($data->LUAS_TANAH == NULL || $data->LUAS_TANAH == '') {
                $luas_tanah = '-';
            } else {
                $luas_tanah = $data->LUAS_TANAH;
            }
            if ($data->LUAS_BANGUNAN == NULL || $data->LUAS_BANGUNAN == '') {
                $luas_bangunan = '-';
            } else {
                $luas_bangunan = $data->LUAS_BANGUNAN;
            }
            if ($data->LUAS_BANGUNAN !== "0" && $data->LUAS_TANAH !== "0") {
                $arr_dhtb[] = 'Tanah dan Bangunan Seluas ' . $luas_tanah . ' m2/' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else if ($data->LUAS_TANAH !== "0" && $data->LUAS_BANGUNAN == "0") {
                $arr_dhtb[] = 'Tanah Seluas ' . $luas_tanah . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            } else {
                $arr_dhtb[] = 'Bangunan Seluas ' . $luas_bangunan . ' m2 di ' . $tmp . ', ' . $data->ASAL_USUL . ' Rp. ' . number_rupiah($data->NILAI_PELAPORAN);
            }
        }

        $arr_all_data = array(
            'nama' => $datapn->NAMA,
            'jabatan' => $datapn->DESKRIPSI_JABATAN,
            'nhk' => $datapn->NHK,
            'tempat_tgl_lahir' => $datapn->TGL_LAHIR,
            'alamat_kantor' => $datapn->ALAMAT_KANTOR,
            'tgl_pelaporan' => $datapn->TGL_LAPOR,
            'nilai_hutang' => $datapn->jumhut,
            'nilai_hl' => $datapn->T3,
            'nilai_kas' => $datapn->T4,
            'nilai_surga' => $datapn->T2,
            'hbl' => $datapn->T5,
            'hb' => $arr_dhb,
            'htb' => $arr_dhtb,
        );

        $obj_dhb = (object) $arr_dhb;
        $obj_dhtb = (object) $arr_dhtb;

        $this->db->trans_begin();

        if ($datapn->STATUS == '3' || $datapn->STATUS == '4')
            $sts = '4';
        else if ($datapn->STATUS == '5' || $datapn->STATUS == '6')
            $sts = '6';

        $data_lhkpn = array('STATUS' => $sts);
        $max_nhk = $datapn->NHK;

        $data_ba = array(
            'STATUS_CETAK_PENGUMUMAN_PDF' => 1
        );


        $this->data['nhk'] = $max_nhk;
        $data_pn = array(
            'NHK' => $max_nhk
        );

        $no_bap = str_replace("/", "_", $datapn->NOMOR_BAP);
        $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $datapn->NHK . ".docx";


        $this->load->library('lwphpword/lwphpword', array(
            "base_path" => APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            "base_url" => base_url() . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/",
            "base_root" => base_url(),
        ));

        $template_file = "../file/template/FormatPengumuman.docx";

        $this->load->library('lws_qr', [
            "model_qr" => "Cqrcode",
            "model_qr_prefix_nomor" => "PHK-ELHKPN-",
            "callable_model_function" => "insert_cqrcode_with_filename"
        ]);

        $filename_bap = 'uploads/FINAL_LHKPN/' . $no_bap . "/" . $datapn->NIK;

        if (!is_dir($filename_bap)) {
            $dir_bap = './uploads/FINAL_LHKPN/' . $no_bap . '/';

            if (is_dir($dir_bap) === false) {
                mkdir($dir_bap);
            }
        }

//            if (is_dir($dir_bap) == TRUE) {
        $filename = $dir_bap . $datapn->NIK . "/$output_filename";

//                if (!file_exists($filename)) {
        $dir = $dir_bap . $datapn->NIK . '/';

        if (is_dir($dir) === false) {
            mkdir($dir);
        }
        $qr_content_data = json_encode((object) [
                    "data" => [
                        (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $datapn->NAMA_LENGKAP],
                        (object) ["tipe" => '1', "judul" => "NHK", "isi" => $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]],
                        (object) ["tipe" => '1', "judul" => "BIDANG", "isi" => $datapn->BDG_NAMA],
                        (object) ["tipe" => '1', "judul" => "JABATAN", "isi" => $datapn->NAMA_JABATAN],
                        (object) ["tipe" => '1', "judul" => "LEMBAGA", "isi" => $datapn->INST_NAMA],
                        (object) ["tipe" => '1', "judul" => "Jenis Laporan", "isi" => ($datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus') . " - " . show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final))],
                        (object) ["tipe" => '1', "judul" => "Tanggal Pelaporan", "isi" => tgl_format($datapn->tgl_kirim_final)],
                        (object) ["tipe" => '1', "judul" => "Tanah dan Bangunan", "isi" => $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1)],
                        (object) ["tipe" => '1', "judul" => "Alat Transportasi dan Mesin", "isi" => $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6)],
                        (object) ["tipe" => '1', "judul" => "Harta Bergerak Lainnya", "isi" => $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5)],
                        (object) ["tipe" => '1', "judul" => "Surat Berharga", "isi" => $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2)],
                        (object) ["tipe" => '1', "judul" => "Kas dan Setara Kas", "isi" => $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4)],
                        (object) ["tipe" => '1', "judul" => "Harta Lainnya", "isi" => $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3)],
                        (object) ["tipe" => '1', "judul" => "Hutang", "isi" => $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7)],
                        (object) ["tipe" => '1', "judul" => "Total Harta Kekayaan", "isi" => number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7) == NULL ? "----" : number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7)],
                    ],
                    "encrypt_data" => $datapn->NAMA_LENGKAP . "phk",
                    "id_lhkpn" => $id_lhkpn,
                    "judul" => "Pengumuman Harta Kekayaan Penyelenggara Negara",
                    "tgl_surat" => date('Y-m-d'),
        ]);

        $qr_image_location = $this->lws_qr->create($qr_content_data, "tes_qr2-" . $id_lhkpn . ".png");

        $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array("image2.jpeg" => $qr_image_location));

        $this->lwphpword->save_path = APPPATH . "../uploads/FINAL_LHKPN/" . $no_bap . '/' . $datapn->NIK . "/";

        $this->lwphpword->set_value("NHK", $data_pn["NHK"] == NULL ? '-' : $data_pn["NHK"]);
        $this->lwphpword->set_value("NAMA_LENGKAP", $datapn->NAMA_LENGKAP);
        $this->lwphpword->set_value("LEMBAGA", $datapn->INST_NAMA);
        $this->lwphpword->set_value("BIDANG", $datapn->BDG_NAMA);
        $this->lwphpword->set_value("JABATAN", $datapn->NAMA_JABATAN);
        $this->lwphpword->set_value("UNIT_KERJA", $datapn->UK_NAMA);
        $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
        $this->lwphpword->set_value("KHUSUS", show_jenis_laporan_khusus($datapn->JENIS_LAPORAN, $datapn->tgl_kirim_final, tgl_format($datapn->tgl_kirim_final)));
        $this->lwphpword->set_value("TANGGAL", tgl_format($datapn->tgl_kirim_final));
        $this->lwphpword->set_value("TAHUN", substr($datapn->tgl_kirim_final, 0, 4));
//                    $this->lwphpword->set_value("JENIS", $datapn->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus');
        $this->lwphpword->set_value("TGL_BN", tgl_format($datapn->TGL_PNRI));
        $this->lwphpword->set_value("NO_BN", $datapn->NOMOR_PNRI);
        $this->lwphpword->set_value("PENGESAHAN", tgl_format(date("Y-m-d")));
        $this->lwphpword->set_value("STATUS", $datapn->STATUS == '3' || $datapn->STATUS == '4' ? "LENGKAP" : "TIDAK LENGKAP");

        $this->lwphpword->set_value("HTB", $datapn->T1 == NULL ? "----" : number_rupiah($datapn->T1));
        $this->lwphpword->set_value("HB", $datapn->T6 == NULL ? "----" : number_rupiah($datapn->T6));
        $this->lwphpword->set_value("HBL", $datapn->T5 == NULL ? "----" : number_rupiah($datapn->T5));
        $this->lwphpword->set_value("SB", $datapn->T2 == NULL ? "----" : number_rupiah($datapn->T2));
        $this->lwphpword->set_value("KAS", $datapn->T4 == NULL ? "----" : number_rupiah($datapn->T4));
        $this->lwphpword->set_value("HL", $datapn->T3 == NULL ? "----" : number_rupiah($datapn->T3));
        $this->lwphpword->set_value("HUTANG", $datapn->T7 == NULL ? "----" : number_rupiah($datapn->T7));
        $this->lwphpword->set_value("TOTAL", number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6 - $datapn->T7));

        $this->set_data_harta_bergerak($obj_dhb, $this->lwphpword);
        $this->set_data_harta_tidak_bergerak($obj_dhtb, $this->lwphpword);

        $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE, $output_filename);
        $this->lwphpword->download($save_document_success->document_path, $output_filename);

    }
    
    private function set_data_harta_bergerak($obj_dhb, $obj) {
        $array_message = array_filter((array) $obj_dhb);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('no_hb', 0);
        } else {
            $obj->clone_row('no_hb', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {

                $template_string_no_hb = 'no_hb#' . ($key + 1);

                $template_string_hb = 'DHB#' . $i;

                $obj->set_value($template_string_no_hb, ($key + 1));
                $obj->set_value($template_string_hb, $row);
                $i++;
            }
        }
        return FALSE;
    }

    private function set_data_harta_tidak_bergerak($obj_dhtb, $obj) {
        $array_message = array_filter((array) $obj_dhtb);

        $jumlah_data = count($array_message);

        if ($jumlah_data < 0) {
            $obj->clone_row('no_htb', 0);
        } else {
            $obj->clone_row('no_htb', $jumlah_data);
            $i = 1;
            foreach ($array_message as $key => $row) {

                $template_string_no_thb = 'no_htb#' . ($key + 1);

                $template_string_thb = 'DHTB#' . $i;

                $obj->set_value($template_string_no_thb, ($key + 1));
                $obj->set_value($template_string_thb, $row);
                $i++;
            }
        }
        return FALSE;
    }
}
