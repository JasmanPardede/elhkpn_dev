<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * Controller PN
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mutasikeluar extends CI_Controller {
	// num of records per page
	public $limit = 10;

	public function __construct()
	{
		parent::__construct();
		call_user_func('ng::islogin');
	}

	/** Mutasi keluar List
	 *
	 * @return html Mutasikeluar List
	 */
	public function index($offset = 0){
		echo 'Pindah';
		// load model
		$this->load->model('Mmutasi', '', TRUE);
		// prepare paging
		$this->base_url	 = site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 3;
		$this->offset	   = $this->uri->segment($this->uri_segment);

		// filter
		$cari             = '';
		$filter		     = '';
		if($_POST && $this->input->post('cari', TRUE)!=''){
			$cari = $this->input->post('cari', TRUE);
			$filter = array(
					'NAMA' => $this->input->post('cari', TRUE),
					'INST_ASAL' => 1
			);
		}

		// load and packing data
		$this->items		= $this->mmutasi->get_paged_list($this->limit, $this->offset, $filter)->result();
		$this->total_rows   = $this->mmutasi->count_all($filter);

		$data = array(
				'items'         => $this->items,
				'total_rows'    => $this->total_rows,
				'offset'        => $this->offset,
				'cari'          => $cari,
				'breadcrumb'	=> call_user_func('ng::genBreadcrumb',
						array(
								'Dashboard'	 => 'index.php/welcome/dashboard',
								'E Registration' => 'index.php/welcome/dashboard',
								'PN'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
						)),
				'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}
}
