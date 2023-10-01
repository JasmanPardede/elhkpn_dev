<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
	
/** 
 * Controller Area
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller {
	// num of records per page
	public $limit = 10;
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
    }

	public function index($offset = 0)
	{
		echo '<h4>Backup Data</h4>';
		echo '<h4>About</h4>';
		echo '<h4>Info</h4>';
		echo '<h4>Environment</h4>';
		echo '<h4>License</h4>';
		echo '<h4>Utilities</h4>';
		echo '<h4>Version</h4>';
		echo '<br>';
		echo 'System Info : ';
		echo php_uname();
		echo PHP_OS;
		echo '<br>';
		echo 'OS Time : ';
		echo '<br>';
		echo 'DB Time : ';
		$sql = "SELECT now() now FROM dual";
		$row = $this->db->query($sql)->row();
		print_r(date('d-m-Y H:i:s', strtotime($row->now)));

		echo '<br>';
		echo 'PHP Time : '.date('d-m-Y H:i:s',time());
		echo '<br>';
		echo 'Start up info';
		echo '<br>';
		echo '<h4>Database info</h4>';
		echo '<br>';
		echo 'DB Size : ';
		$sql = "SELECT table_schema 'TABLE', 
			Round(Sum(data_length + index_length) / 1024 / 1024, 1) 'SIZE'
			FROM   information_schema.tables WHERE table_schema = 'c1_lhkpn' 
			GROUP  BY table_schema";
		$row = $this->db->query($sql)->row();
		print_r($row->TABLE.' '.$row->SIZE.' MB');

		echo '<br>';		
		echo 'ANALYZE TABLE : ';
		$sql = "ANALYZE TABLE `ci_sessions`, `T_DEV_ISSUE`, `T_HISTORY_JABATAN`, `T_LHKPN`, `T_LHKPN_FASILITAS`, `T_LHKPN_HARTA`, `T_LHKPN_HUTANG`, `T_LHKPN_KELUARGA`, `T_LHKPN_PENERIMAAN_KAS`, `T_LHKPN_PENGELUARAN_KAS`, `T_LHKPN_PENJUALAN`, `T_MAIL`, `T_MENU`, `T_MUTASI_PN`, `T_PN`, `M_AGAMA`, `M_AREA`, `M_ASAL_USUL`, `M_GOLONGAN_HARTA`, `M_GOLONGAN_PENGELUARAN_KAS`, `M_INST_SATKER`, `M_JABATAN`, `M_JENIS_BUKTI`, `M_JENIS_HARTA`, `M_JENIS_PENERIMAAN_KAS`, `M_JENIS_PENGELUARAN_KAS`, `T_R_KOTA`, `M_PEMANFAATAN`, `M_PENDIDIKAN`, `T_R_PROVINSI`, `M_STATUS_NIKAH`, `T_SETTINGS`, `T_SURAT_KELUAR`, `T_USER`, `T_USER_ACTIVITY`, `T_USER_AKSES`, `T_USER_LOG`, `T_USER_REGISTRASI`, `T_USER_ROLE`";
		$rows = $this->db->query($sql)->result();		
		echo '<table class="table table-bordered table-hover">';
		
		foreach ($rows as $row) {
		echo '<tr>';
		echo '<td>';
		echo $row->Table;
		echo '</td>';
		echo '<td>';
		echo $row->Op;
		echo '</td>';
		echo '<td>';
		echo $row->Msg_type;
		echo '</td>';
		echo '<td>';
		echo $row->Msg_text;
		echo '</td>';
		echo '</tr>';
		}
		
		echo '</table>';



		echo '<br>';
		echo 'ci_session : <a href="#">Cleaner</a>';
		echo '<br>';
		echo 'Current PHP version : ' . phpversion();
	}
}