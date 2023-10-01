echo"makan yuk";exit;
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_rppt_pnwl extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->load->model('mreport');
		$this->username    = $this->session->userdata('USERNAME');
		$this->makses->initialize();
		$this->makses->check_is_read();        
		$this->uri_segment = 5;
		$this->offset      = $this->uri->segment($this->uri_segment);
		$this->limit       = 10;
        
        // prepare search
        foreach ((array)@$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];        
        
        $this->act = $this->input->post('act', TRUE);
    }

	public function index()
	{
		$data = array(
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL II'  => 'index.php/ereport/all_rppt_pnwl/',
                                )),
            );
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}
	
	public function kepatuhan_bidang()
	{
		$this->db->start_cache();

		@$this->CARI['TAHUN'];

		$where["IS_ACTIVE"] 	= '1';
		(@$this->CARI['BIDANG']) ? $where["INST_BDG_ID"] 	 = @$this->CARI['BIDANG'] : $where["INST_BDG_ID"] = '';

		$selected 	= "BDG_NAMA, INST_BDG_ID, INST_SATKERKD, INST_NAMA,
					   (SELECT COUNT(*) FROM T_PN_JABATAN JOIN T_PN ON T_PN.ID_PN = T_PN_JABATAN.ID_PN WHERE T_PN_JABATAN.IS_CURRENT = '1' AND T_PN.IS_ACTIVE = '1' AND T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD AND T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD) AS WAJIB_LAPOR,
                       (SELECT COUNT(DISTINCT T_PN.ID_PN) FROM T_PN JOIN T_PN_JABATAN ON T_PN.ID_PN = T_PN_JABATAN.ID_PN JOIN T_LHKPN ON T_PN.ID_PN = T_LHKPN.ID_PN JOIN T_LHKPN_JABATAN ON T_LHKPN.ID_LHKPN=T_LHKPN_JABATAN.ID_LHKPN WHERE T_PN.IS_ACTIVE = '1' AND T_LHKPN.IS_ACTIVE='1' AND T_LHKPN_JABATAN.IS_PRIMARY='1' AND T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD) AS SUDAH_LAPOR
					  ";
		$items 		= $this->mglobal->get_data_all('M_INST_SATKER', [['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID']], $where, $selected);

		$this->db->flush_cache();
		
		$data = array(
				'cari'		=> @$this->CARI,
				'items' 	=> $items,
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL II'  => 'index.php/ereport/all_rppt_pnwl/',
                                    'Kepatuhan Bidang'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
			);
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	public function kepatuhan_unit_kerja()
	{
		$this->db->start_cache();

		@$this->CARI['TAHUN'];

		$where["IS_ACTIVE"] 	= '1';
		(@$this->CARI['BIDANG']) ? $where["INST_BDG_ID"] 	 = @$this->CARI['BIDANG'] : $where["INST_BDG_ID"] = '';
		(@$this->CARI['BIDANG']) ? $where["UK_BIDANG_ID"]	 = @$this->CARI['BIDANG'] : $where["INST_BDG_ID"] = '';
		(@$this->CARI['INSTANSI']) ? $where["INST_SATKERKD"] = @$this->CARI['INSTANSI'] : $where["INST_SATKERKD"] = '';

		$selected 	= "UK_BIDANG_ID, INST_SATKERKD, UK_ID, INST_NAMA, UK_NAMA,
					   (SELECT COUNT(*) FROM T_PN_JABATAN JOIN T_PN ON T_PN.ID_PN = T_PN_JABATAN.ID_PN WHERE T_PN_JABATAN.IS_CURRENT = '1' AND T_PN.IS_ACTIVE = '1' AND T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD AND T_PN_JABATAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID) AS WAJIB_LAPOR,
                       (SELECT COUNT(DISTINCT T_PN.ID_PN) FROM T_PN JOIN T_PN_JABATAN ON T_PN.ID_PN = T_PN_JABATAN.ID_PN JOIN T_LHKPN ON T_PN.ID_PN = T_LHKPN.ID_PN JOIN T_LHKPN_JABATAN ON T_LHKPN.ID_LHKPN=T_LHKPN_JABATAN.ID_LHKPN WHERE T_PN.IS_ACTIVE = '1' AND T_LHKPN.IS_ACTIVE='1' AND T_LHKPN_JABATAN.IS_PRIMARY='1' AND T_LHKPN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD AND T_LHKPN_JABATAN.UNIT_KERJA = M_UNIT_KERJA.UK_ID) AS SUDAH_LAPOR
					  ";
		$items 		= $this->mglobal->get_data_all('M_INST_SATKER', [['table' => 'M_UNIT_KERJA', 'on' => 'M_INST_SATKER.INST_SATKERKD = M_UNIT_KERJA.UK_LEMBAGA_ID']], $where, $selected);

		$this->db->flush_cache();
		
		$data = array(
				'cari'		=> @$this->CARI,
				'items' 	=> $items,
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL II'  => 'index.php/ereport/all_rppt_pnwl/',
                                    'Kepatuhan Unit Kerja'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
			);
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	public function kepatuhan_jabatan(){
		$this->db->start_cache();

		@$this->CARI['TAHUN'];

		$where["M_JABATAN.IS_ACTIVE"] 	= '1';
		(@$this->CARI['BIDANG']) ? $where["INST_BDG_ID"] 	 = @$this->CARI['BIDANG'] : $where["INST_BDG_ID"] = '';

		$selected 	= "BDG_NAMA, T_PN_JABATAN.ID_JABATAN, NAMA_JABATAN,
					   (SELECT COUNT(*) FROM T_PN_JABATAN JOIN T_PN ON T_PN.ID_PN = T_PN_JABATAN.ID_PN WHERE T_PN_JABATAN.IS_CURRENT = '1' AND T_PN.IS_ACTIVE = '1' AND T_PN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN) AS WAJIB_LAPOR,
                       (SELECT COUNT(DISTINCT T_PN.ID_PN) FROM T_PN JOIN T_PN_JABATAN ON T_PN.ID_PN = T_PN_JABATAN.ID_PN JOIN T_LHKPN ON T_PN.ID_PN = T_LHKPN.ID_PN JOIN T_LHKPN_JABATAN ON T_LHKPN.ID_LHKPN=T_LHKPN_JABATAN.ID_LHKPN WHERE T_PN.IS_ACTIVE = '1' AND T_LHKPN.IS_ACTIVE='1' AND T_LHKPN_JABATAN.IS_PRIMARY='1' AND T_LHKPN_JABATAN.ID_JABATAN = M_JABATAN.ID_JABATAN) AS SUDAH_LAPOR
					  ";
		$items 		= $this->mglobal->get_data_all('M_JABATAN', [['table' => 'T_PN_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN'],['table' => 'M_INST_SATKER', 'on' => 'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD'],['table' => 'M_BIDANG', 'on' => 'M_INST_SATKER.INST_BDG_ID = M_BIDANG.BDG_ID']], $where, $selected, NULL, NULL, 0, NULL, ['NAMA_JABATAN']);

		$this->db->flush_cache();
		
		$data = array(
				'cari'		=> @$this->CARI,
				'items' 	=> $items,
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Jabatan'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
			);
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	public function kepatuhan_provinsi(){
		$data = array(
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Provinsi'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);	
	}

	public function kepatuhan_lembaga($offset = 0){

		$data   = '';
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
		$this->base_url    = 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/';
		$this->uri_segment = 4;
		$this->offset      = $this->uri->segment($this->uri_segment);

		$this->db->select('*');           

        $this->db->start_cache();
        $this->db->from('M_INST_SATKER');

        $this->db->where('INST_NAMA !=', '---');

        $this->db->where('INST_NAMA !=', '[Unknown]');

        if(@$this->CARI['JENIS'] == '') {
        	$this->db->where('INST_BDG_ID', '');
        }

        if(@$this->CARI['JENIS']){
            $this->db->where('INST_BDG_ID', @$this->CARI['JENIS']);
        }

        if(@$this->CARI['NAMA']){
            $this->db->like('NAMA_JABATAN', @$this->CARI['NAMA']);
        }

        $this->db->order_by('INST_NAMA', 'asc');

        $this->total_rows = $this->db->get('')->num_rows();
        
        $query = $this->db->get('',$this->limit, $this->offset);

        $this->items = $query->result();
        $this->end = $query->num_rows();

        $this->db->flush_cache();

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'CARI'          => @$this->CARI,
            'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Lembaga'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
			'pagination'	=> call_user_func('ng::genPagination'),
		);
        
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);	
	}

	public function kepatuhan_detail($offset = 0){
		$data   = '';
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
		$this->base_url    = 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/';
		$this->uri_segment = 4;
		$this->offset      = $this->uri->segment($this->uri_segment);

		$this->db->select('T_LHKPNOFFLINE_PENERIMAAN.*
		    ,JABATAN.*
		    ,T_PN.*
		    ,T_LHKPN.* ');           

        $this->db->start_cache();
        $this->db->from('T_LHKPN');
        // $this->db->join('T_LHKPN_JABATAN', 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN', 'left');
        $this->db->join('(
                select  ID_LHKPN AS ID_LHKPN_DIJABATAN, 
                    group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_LHKPN_JABATAN.ID_JABATAN)),T_LHKPN_JABATAN.ID_JABATAN)) JABATAN, 
                    group_concat(
                    CONCAT(
                        IFNULL(T_LHKPN_JABATAN.ID,""),":58:",
                        IFNULL(T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                        IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                        IFNULL(T_LHKPN_JABATAN.LEMBAGA,""),":58:",
                        IFNULL(M_JABATAN.NAMA_JABATAN,""), 
                        "(", IFNULL(M_ESELON.ESELON,""), ") - ", 
                        IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ", 
                        IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                    )
                ) as NAMA_JABATAN
                    from T_LHKPN_JABATAN
                    LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN
                    LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA
                    LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_LHKPN_JABATAN.UNIT_KERJA
                    LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_LHKPN_JABATAN.ESELON
                    LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_LHKPN_JABATAN.ID_STATUS_AKHIR_JABAT
                    GROUP BY T_LHKPN_JABATAN.ID_LHKPN
                ) JABATAN', 'JABATAN.ID_LHKPN_DIJABATAN = T_LHKPN.ID_LHKPN', 'left');
        $this->db->join('T_PN', 'T_PN.ID_PN = T_LHKPN.ID_PN');
        $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN=T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'LEFT');
        $status = 4;
        $this->db->where('T_LHKPN.STATUS', $status);
        // $this->db->join('M_INST_SATKER', 'M_INST_SATKER.INST_SATKERKD = T_LHKPN.LEMBAGA', 'left');

//        if($this->isDataEntry()){
            // $this->db->join('T_LHKPNOFFLINE_PENUGASAN_ENTRY D', 'D.ID_LHKPN = T_LHKPN.ID_LHKPN', 'LEFT');
            // $this->db->where('USERNAME_ENTRI', $this->session->userdata('USERNAME'));
//        }

        if(@$this->CARI['TAHUN']){
            $this->db->where('YEAR(TGL_LAPOR)', $this->CARI['TAHUN']);
        }else{
            $this->db->where('YEAR(TGL_LAPOR)', date("Y"));
        }

        if(@$this->CARI['INST']){
        	$inst = explode('-', $this->CARI['INST']);
            $this->db->like('NAMA_JABATAN', urldecode(str_replace("_"," ",$inst[1])) );
            $this->CARI['INST'] = urlencode($this->CARI['INST']);
        }

        if(@$this->CARI['NAMA']){
            $this->db->like('T_PN.NAMA', $this->CARI['NAMA']);
        }

        if(@$this->CARI['UKER']){
        	$uker = explode('-', $this->CARI['UKER']);
            $this->db->like('NAMA_JABATAN', urldecode(str_replace("_"," ",$uker[1])) );
            $this->CARI['UKER'] = urlencode(@$this->CARI['UKER']);
        }

        $this->db->order_by('T_PN.NAMA', 'asc');

        $this->total_rows = $this->db->get('')->num_rows();
        
        $query = $this->db->get('',$this->limit, $this->offset);
        // display($this->db->last_query());
        $this->items = $query->result();
        $this->end = $query->num_rows();
//         echo $this->db->last_query();
        // echo "<pre>";
        // print_r ($query);
        // echo "</pre>";
        $this->db->flush_cache();

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'CARI'          => @$this->CARI,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
									'Kepatuhan Detail'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);
        
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);	
	}


    public function kepatuhan_per_bidang(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            // $tahun = 2015;
            $tahun = date('Y');
        }

        $data = array(
                'tahun' => $tahun,
                'list_data'	=> $this->mreport->report_per_bidang($tahun),		
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Bidang'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_per_bidang', $data);
    }

    public function kepatuhan_eksekutif_pusat(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        $data = array(
                'tahun' => $tahun,
                'list_data' => $this->mreport->report_eksekutif_pusat($tahun),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Eksekutif Pusat'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_eks_pusat', $data);
    }

    public function kepatuhan_eksekutif_daerah(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }
        if($_POST && $this->input->post('provinsi', TRUE) != ''){
            $prov = $this->input->post('provinsi');
        }else{
            $prov = "";
        }
        $data = array(
                'tahun'     => $tahun,
                'list_data' => $this->mreport->report_eksekutif_daerah($tahun, $prov),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Eksekutif Daerah'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_eks_daerah', $data);
    }

    public function kepatuhan_legislatif(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        if($_POST && $this->input->post('provinsi', TRUE) != ''){
            $prov = $this->input->post('provinsi');
        }else{
            $prov = "";
        }
        $data = array(
                'tahun'         => $tahun,
                'list_data'     => $this->mreport->report_legislatif($tahun,$prov),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Bidang Legislatif'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_legislatif', $data);
    }

    public function kepatuhan_yudikatif($tahun){        
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }
        $data = array(
                'tahun'     => $tahun,    
                'list_data' => $this->mreport->report_yudikatif($tahun),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Bidang Yudikatif'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_yudikatif', $data);
    }


    public function kepatuhan_bumn(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }
        $data = array(
                'tahun'         => $tahun,
                'list_data'     => $this->mreport->report_bumn($tahun),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan BUMN'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_bumn', $data);
    }

    public function kepatuhan_bumd(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }
        if($_POST && $this->input->post('provinsi', TRUE) != ''){
            $prov = $this->input->post('provinsi');
        }else{
            $prov = "";
        }
        $data = array(
                'tahun'         => $tahun,
                'list_data'     => $this->mreport->report_bumd($tahun,$prov),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan BUMD'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
        );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_bumd', $data);
    }

    public function kepatuhan_unit_kerja_1(){  
        if($_POST && $this->input->post('lembaga', TRUE) != ''){
            $uk_id = $this->input->post('lembaga');
        }else{
            $uk_id = NULL;
        }

        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        $data = array(
                'lembaga'       => $uk_id,
                'tahun'         => $tahun,    
                'list_data'     => $this->mreport->report_unit_kerja_1($uk_id, $tahun),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Unit Kerja 1'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_unit_kerja_1', $data);
    }

    public function kepatuhan_unit_kerja_2(){
        
        if($_POST && $this->input->post('unitkerja', TRUE) != ''){
            $uk_id = $this->input->post('unitkerja');
        }else{
            $uk_id = NULL;
        }

        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        $data = array(
                'lembaga'       => $uk_id,
                'tahun'         => $tahun,
                'list_data'     => $this->mreport->report_unit_kerja_2($uk_id,$tahun),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Unit Kerja 2'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_unit_kerja_2', $data);
    }

    public function kepatuhan_per_eselon(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        if($_POST && $this->input->post('lembaga', TRUE) != ''){
            $lembaga = $this->input->post('lembaga');
        }else{
            $lembaga = NULL;
        }

        $data = array(
                'tahun'     => $tahun,    
                'list_data' => $this->mreport->report_eselon($tahun, $lembaga),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Per Eselon'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_eselon', $data);
    }


    public function kepatuhan_belum_lapor(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        if($_POST && $this->input->post('lembaga', TRUE) != ''){
            $lembaga = $this->input->post('lembaga');
        }else{
            $lembaga = NULL;
        }

        if($_POST && $this->input->post('unitkerja', TRUE) != ''){
            $uk_id = $this->input->post('unitkerja');
        }else{
            $uk_id = NULL;
        }

        $data = array(
                'list_data'     => $this->mreport->report_belum_lapor($tahun, $lembaga, $uk_id),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Belum Lapor'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_belum_lapor', $data);
    }


    public function kepatuhan_sudah_lapor(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        if($_POST && $this->input->post('lembaga', TRUE) != ''){
            $lembaga = $this->input->post('lembaga');
        }else{
            $lembaga = NULL;
        }

        if($_POST && $this->input->post('unitkerja', TRUE) != ''){
            $uk_id = $this->input->post('unitkerja');
        }else{
            $uk_id = NULL;
        }

        $data = array(
                'tahun'         => $tahun,
                'list_data'     => $this->mreport->report_sudah_lapor_a($tahun,$lembaga,$uk_id),
                'list_data2'     => $this->mreport->report_sudah_lapor_b($tahun,$lembaga,$uk_id),
                'list_data3'     => $this->mreport->report_sudah_lapor_c($tahun,$lembaga,$uk_id),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Sudah Lapor'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_sudah_lapor', $data);
    }

    public function kepatuhan_seluruhnya(){
        if($_POST && $this->input->post('tahun', TRUE) != ''){
            $tahun = $this->input->post('tahun');
        }else{
            $tahun = date('Y');
        }

        $data = array(
                'tahun'         => $tahun,
                'list_data'     => $this->mreport->report_sudah_lapor_a($tahun,$lembaga,$uk_id),
                'list_data2'    => $this->mreport->report_sudah_lapor_b($tahun,$lembaga,$uk_id),
                'list_data3'    => $this->mreport->report_sudah_lapor_c($tahun,$lembaga,$uk_id),
                'list_data4'     => $this->mreport->report_belum_lapor($tahun, $lembaga, $uk_id),
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Sudah Lapor'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_kepatuhan_seluruhnya', $data);
    }

    public function history_laporan(){
        if($_POST && $this->input->post('NIK', TRUE) != ''){
            $nik = $this->input->post('NIK');
            $get_nik = $this->mreport->get_nik($nik);
            if($get_nik != false){
                $get_history = $this->mreport->get_history($get_nik->ID_PN);
            }else{
                $get_history = false;
            }
        }else{
            $nik = FALSE;
            $get_history = FALSE;
        }  
        
        $data = array(
                'nik'         => $nik,    
                'list_data'     => $get_history,
                'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-Report PN/WL'  => 'index.php/ereport/all_rpt_pnwl/',
                                    'Kepatuhan Sudah Lapor'  => 'index.php/ereport/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            );
        $this->load->view(strtolower(__CLASS__).'/all_rpt_pnwl_history_laporan', $data);
    }

}

/* End of file All_rpt_pnwl.php */
/* Location: ./application/modules/ereport/controllers/All_rpt_pnwl.php */