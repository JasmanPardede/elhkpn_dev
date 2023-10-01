<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Penyelenggara Negara
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pn extends CI_Controller {
	// num of records per page
	public $limit = 10;
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->username = $this->session->userdata('USERNAME');
        $this->makses->initialize();
        $this->makses->check_is_read();        
        $this->uri_segment  = 5;
        $this->offset      = $this->uri->segment($this->uri_segment);
        
        // prepare search
        foreach ((array)@$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];
        
        $this->act = $this->input->post('act', TRUE);
        $this->remapSegment();
    }

    private function remapSegment()
    {
        $segs = $this->uri->segment_array();
        $i=0;
        $map[] = 'index.php';
        foreach ($segs as $segment)
        {
            ++$i;
            $map[] = $segment;
            $this->segmentName[$i] = $segment;
            $this->segmentTo[$i] = implode('/', $map).'/';
        }
    }

    /** Penyelenggara Negara List
     * 
     * @return html Penyelenggara Negara List
     */
	public function index($offset = 0, $cetak=false)
	{
        // load session
        $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
        
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url  = 'index.php/ereg/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/';
        $this->uri_segment  = 4;
        $this->offset      = $this->uri->segment($this->uri_segment);
        
        if(in_array($cetak, ['pdf', 'excel', 'word'])){
            $this->iscetak = true;
            $this->limit = 0;
        }

        $this->db->start_cache();

        $this->db->select('T_PN.*, JABATAN.*, T_USER.ID_USER');
        $this->db->from('T_PN');
        $this->db->join('(
            select  T_PN_JABATAN.ID_PN AS ID_PN_DIJABATAN, 
                group_concat(CONCAT( REPEAT( "0", 5 - LENGTH( T_PN_JABATAN.LEMBAGA ) ), T_PN_JABATAN.LEMBAGA )) LEMBAGA,
                group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_PN_JABATAN.ID_JABATAN)),T_PN_JABATAN.ID_JABATAN)) JABATAN, 
                group_concat(
                    CONCAT(
                        IFNULL(T_PN_JABATAN.ID,""),":58:",
                        IFNULL(T_PN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                        IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                        IFNULL(T_MUTASI_PN.ID_PN_JABATAN,""),":58:",
                        IFNULL(T_PN_JABATAN.LEMBAGA,""),":58:",
                        IFNULL(M_JABATAN.NAMA_JABATAN,""), 
                        "(", IFNULL(M_ESELON.ESELON,""), ") - ", 
                        IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ", 
                        IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                    )
                ) as NAMA_JABATAN
            from T_PN_JABATAN
                LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN
                LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_PN_JABATAN.LEMBAGA
                LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_PN_JABATAN.UNIT_KERJA
                LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_PN_JABATAN.ESELON
                LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_PN_JABATAN.ID_STATUS_AKHIR_JABAT
                LEFT JOIN T_MUTASI_PN ON T_MUTASI_PN.ID_PN_JABATAN = T_PN_JABATAN.ID
                GROUP BY T_PN_JABATAN.ID_PN
            ) JABATAN', 'JABATAN.ID_PN_DIJABATAN = T_PN.ID_PN', 'left');
        $this->db->join('T_USER', 'T_USER.USERNAME = T_PN.NIK', 'left');
        
        $this->db->where("JABATAN.LEMBAGA like '%".str_repeat('0',5-strlen($INST_SATKERKD)).$INST_SATKERKD."%'", null, false);

        if(@$this->CARI['TEXT']){
            $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
        } 
        if(@$this->CARI['STATUS_PN'] && @$this->CARI['STATUS_PN']!=99){
            $this->db->like('T_PN.STATUS_PN', $this->CARI['STATUS_PN']);
        }

        $this->total_rows = $this->db->get('')->num_rows();

        $query = $this->db->get('',$this->limit, $this->offset);
        $this->items = $query->result();
        $this->end = $query->num_rows();
        $this->db->flush_cache();

        $data = array(
            'linkCetak'     => 'index.php/ereg/pn/index/0',
            'titleCetak'    => 'cetakpn',            
            'items'         => $this->items,
            'total_rows'    => $this->total_rows,
            'offset'        => $this->offset,
            'CARI'          => @$this->CARI,
            'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'E-reg'  => 'index.php/dashboard/efilling',
                                    'PN'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            'pagination'    => call_user_func('ng::genPagination'),
            'instansis'     => $this->mglobal->get_data_all('M_INST_SATKER'),
        );

        // load view
        if(@$this->iscetak){
            ng::exportDataTo($data, $cetak, strtolower(get_called_class()).'/'.strtolower(get_called_class()).'_'.'index'.'_'.'cetak', 'cetakpn');
        }else{        
            $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
        }
	}

	/** Process Insert, Update, Delete Penyelenggara Negara
     * 
     * @return boolean process Penyelenggara Negara
     */
    public function savepn(){
        $this->db->trans_begin();
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);

        if($this->input->post('act', TRUE)=='dorangkapjabatan'){
            $pejabat = array(
                'ID_PN' 			=> $this->input->post('ID_PN', TRUE),
                'ID_JABATAN' 	    => $this->input->post('JABATAN', TRUE),
                'LEMBAGA' 			=> $this->input->post('INST_SATKERKD', TRUE),
                'ESELON' 			=> $this->input->post('ESELON', TRUE),
                'UNIT_KERJA' 		=> $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' 	=> $this->input->post('ALAMAT_KANTOR', TRUE),
                'CREATED_TIME'     	=> time(),
                'CREATED_BY'     	=> 'admin', //$this->session->userdata('USR'),
                'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   en
            );
            $insert = $this->mpn->save_jabatan_pn($pejabat);
        }
        else if($this->input->post('act', TRUE)=='doinsert'){
            $password   = createRandomPassword(6);
            $pejabat = array(
                'ID_PN' 			=> $this->input->post('ID_PN', TRUE),
				'NIK' 				=> $this->input->post('NIK', TRUE),
				'NAMA' 				=> $this->input->post('NAMA', TRUE),
                'JNS_KEL'           => $this->input->post('JNS_KEL', TRUE),
				'TEMPAT_LAHIR' 		=> $this->input->post('TEMPAT_LAHIR', TRUE),
				'TGL_LAHIR' 		=> date('Y-m-d', strtotime($this->input->post('TGL_LAHIR', TRUE))),
				'ID_AGAMA' 			=> $this->input->post('ID_AGAMA', TRUE),
				'ID_STATUS_NIKAH' 	=> $this->input->post('ID_STATUS_NIKAH', TRUE),
				'ID_PENDIDIKAN' 	=> $this->input->post('ID_PENDIDIKAN', TRUE),
				'NPWP' 				=> $this->input->post('NPWP', TRUE),
				'ALAMAT_TINGGAL' 	=> $this->input->post('ALAMAT_TINGGAL', TRUE),
				'EMAIL' 			=> $this->input->post('EMAIL', TRUE),
				'NO_HP' 			=> $this->input->post('NO_HP', TRUE),
				'ID_JABATAN' 	    => $this->input->post('JABATAN', TRUE),
				'BIDANG' 			=> $this->input->post('BIDANG', TRUE),
				'LEMBAGA' 			=> $this->session->userdata('INST_SATKERKD'),
				'TINGKAT' 			=> $this->input->post('TINGKAT', TRUE),
				'UNIT_KERJA' 		=> $this->input->post('UNIT_KERJA', TRUE),
				'ALAMAT_KANTOR' 	=> $this->input->post('ALAMAT_KANTOR', TRUE),
				'IS_ACTIVE' 		=> '1',
                'STATUS_PN'             => $this->input->post('STATUS_PN', TRUE),
                'CREATED_TIME'     	=> time(),
                'CREATED_BY'     	=> 'admin', //$this->session->userdata('USR'),
                'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   en
            );
			if($this->input->post('FOTO')!=""){
					$config['upload_path']          = './foto/';
					$config['allowed_types']        = 'gif|jpg|png';
					$config['max_width']            = 1024;
					$config['max_height']           = 768;
				
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('FOTO'))
                {
                    $error = $this->upload->display_errors();
					print_r($error);
					exit();
                }
                else
                {
                    $data = array('upload_data' => $this->upload->data());
					$penjabat['FOTO'] = $data['ipload_data']['file_name'];
                }
			}
            $insert = $this->mpn->save($pejabat);

            $jabatan = array(
                'ID_PN' 			=> $this->input->post('ID_PN', TRUE),
                'ID_JABATAN' 	    => $this->input->post('JABATAN', TRUE),
                'LEMBAGA' 			=> $this->input->post('INST_SATKERKD', TRUE),
                'ESELON' 			=> $this->input->post('ESELON', TRUE),
                'UNIT_KERJA' 		=> $this->input->post('UNIT_KERJA', TRUE),
                'ALAMAT_KANTOR' 	=> $this->input->post('ALAMAT_KANTOR', TRUE),
                'CREATED_TIME'     	=> time(),
                'CREATED_BY'     	=> 'admin', //$this->session->userdata('USR'),
                'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                'IS_PRIMARY'        => 1
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   en
            );
            $insert_jabatan = $this->mpn->save_jabatan_pn($jabatan);

            $data_user  = array(
                'USERNAME' 		    => $this->input->post('NIK', TRUE),
                'NAMA' 		        => $this->input->post('NAMA', TRUE),
                'EMAIL'             => $this->input->post('EMAIL', TRUE),
                'HANDPHONE' 		=> $this->input->post('NO_HP', TRUE),
                'PASSWORD' 		    => sha1(md5($password)),
                'IS_ACTIVE' 		=> '1',
                'CREATED_TIME'      => time(),
                'CREATED_BY'        => $this->session->userdata('USR'),
                'CREATED_IP'        => $_SERVER["REMOTE_ADDR"],
                'ID_ROLE'           => ID_ROLE_PN,
                'INST_SATKERKD'     => $this->session->userdata('INST_SATKERKD')
            );
            if ( $insert ) {
                $this->muser->save($data_user);
                $kirim_info = $this->mpn->kirim_info_pn($this->input->post('EMAIL', TRUE), $this->input->post('NIK', TRUE), $password, 'Pemberitahuan Informasi Akun');
            }
        }else if($this->input->post('act', TRUE)=='doupdate'){
            $pejabat = array(
                'ID_PN' 		=> $this->input->post('ID_PN', TRUE),
				'NIK' 		=> $this->input->post('NIK', TRUE),
				'NAMA' 		=> $this->input->post('NAMA', TRUE),
                'JNS_KEL'           => $this->input->post('JNS_KEL', TRUE),
				'TEMPAT_LAHIR' 	=> $this->input->post('TEMPAT_LAHIR', TRUE),
				'TGL_LAHIR' 	=> date('Y-m-d', strtotime($this->input->post('TGL_LAHIR', TRUE)) ),
				'ID_AGAMA' 	=> $this->input->post('ID_AGAMA', TRUE),
				'ID_STATUS_NIKAH' => $this->input->post('ID_STATUS_NIKAH', TRUE),
				'ID_PENDIDIKAN' => $this->input->post('ID_PENDIDIKAN', TRUE),
				'NPWP' 	=> $this->input->post('NPWP', TRUE),
				'ALAMAT_TINGGAL' => $this->input->post('ALAMAT_TINGGAL', TRUE),
				'EMAIL' 	=> $this->input->post('EMAIL', TRUE),
				'NO_HP' 	=> $this->input->post('NO_HP', TRUE),
				'ID_JABATAN' 		=> $this->input->post('JABATAN', TRUE),
				'BIDANG' 		=> $this->input->post('BIDANG', TRUE),
				'TINGKAT' 		=> $this->input->post('TINGKAT', TRUE),
				'UNIT_KERJA' 		=> $this->input->post('UNIT_KERJA', TRUE),
				'ALAMAT_KANTOR' 		=> $this->input->post('ALAMAT_KANTOR', TRUE),
				'IS_ACTIVE' 		=> $this->input->post('IS_ACTIVE', TRUE),
                'STATUS_PN'             => $this->input->post('STATUS_PN', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            
			if($this->input->post('FOTO')!=""){
					$config['upload_path']          = './foto';
					$config['allowed_types']        = 'gif|jpg|png';
					$this->load->library('upload', $config);
				if (!$this->upload->do_upload('FOTO'))
                {
                        $error = $this->upload->display_errors();
						print_r($error);
						exit();
                }
                else
                {
                        $data = $this->upload->data();
						$fupload = array('FOTO' => $data['file_name'] );
						array_push($pejabat,$fupload);
                }
			}
            $data_user  = array(
                'USERNAME' 		    => $this->input->post('NIK', TRUE),
                'NAMA' 		        => $this->input->post('NAMA', TRUE),
                'EMAIL'             => $this->input->post('EMAIL', TRUE),
                'HANDPHONE' 		=> $this->input->post('NO_HP', TRUE),
                'IS_ACTIVE' 		=> '1'
            );
			$pejabat['ID_PN']    = $this->input->post('ID_PN', TRUE);
            $this->mpn->update($pejabat['ID_PN'], $pejabat);
            $this->muser->update($this->input->post('ID_USER', true), $data_user);
        }else if($this->input->post('act', TRUE)=='dodelete'){
            $pejabat['ID_PN']    = $this->input->post('ID_PN', TRUE);
            $get_pn    = $this->mpn->get_by_id($pejabat['ID_PN']);
            $id_user   = is_object($get_pn) ? $get_pn->row()->ID_USER : NULL;
            $this->muser->delete($id_user);
            $this->mpn->delete($pejabat['ID_PN']);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
    
    /** Form Tambah Penyelenggara Negara
     * 
     * @return html form tambah Penyelenggara Negara
     */
    public function addpn(){
        $this->load->model('mpn', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        $inst_satkerkd = $this->session->userdata('INST_SATKERKD');
        $data = array(
            'form'      => 'add',
            'instansis' => $this->minstansi->list_all()->result(),
            'jabatans'  => $this->mjabatan->list_all()->result(),
            'unitkerjas'    => $this->munitkerja->list_all($inst_satkerkd)->result()
        );
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit Penyelenggara Negara
     *
     * @return html form edit Penyelenggara Negara
     */
    public function editpn($id){
        $this->load->model('mpn', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);

        $joinJabatan = [
        	['table'=>'M_INST_SATKER', 'on'=>'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];        

        $data = array(
            'form'      => 'edit',
            'item'      => $this->mpn->get_by_id($id)->row(),
            'instansis'      => $this->minstansi->list_all()->result(),
            'jabatans' => $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "T_PN_JABATAN.ID_PN = '$id'"),
            'jabatans'  => $this->mjabatan->list_all()->result()
        );
        $data['unitkerjas'] = $this->munitkerja->list_all($data['item']->LEMBAGA)->result();
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
    }

    public function get_unit_kerja() {
        $this->load->model('munitkerja');
        $inst_satkerkd = $this->input->post('INST_SATKERKD');
        $inst_satkerkd = empty($inst_satkerkd) ? '999' : $inst_satkerkd;
        $unitkerja =  $this->munitkerja->list_all($inst_satkerkd)->result();
        echo json_encode(array('result' => $unitkerja));

    }

    public function getUnitKerja($lembaga = NULL , $id = NULL)
    {
        // $join = [
        //            ['table' => 'M_INST_SATKER'      , 'on' => 'INST_SATKERKD  = LEMBAGA'],
        //         ];
        if(is_null($id)){
            $q = $_GET['q'];

            $where  = ['UK_LEMBAGA_ID' => $lembaga];
            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, $where,
                'UK_ID, UK_NAMA', "UK_NAMA LIKE '%$q%'", null , null , '15');
            $res = [];
            foreach ($result as $row)
            {
                $res[] = ['id' => $row->UK_ID, 'name' => $row->UK_NAMA];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        }else{
            $where = ['UK_ID' => $id,'UK_LEMBAGA_ID' => $lembaga];

            $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, $where,
                'UK_ID, UK_NAMA', null, null , null , '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_UNIT_KERJA', null, null,
            //     'UK_ID, UK_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row)
            {
                $res[] = ['id' => $row->UK_ID, 'name' => $row->UK_NAMA];
            }

            echo json_encode($res);
        }
    }

    public function getJabatan($id = NULL)
    {
        // $join = [
        //            ['table' => 'M_INST_SATKER'      , 'on' => 'INST_SATKERKD  = LEMBAGA'],
        //         ];
        if(is_null($id)){
            $q = $_GET['q'];

            $where  = ['IS_ACTIVE' => '1'];
            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where,
                'ID_JABATAN, NAMA_JABATAN', "NAMA_JABATAN LIKE '%$q%'", null , null , '15');

            $res = [];
            foreach ($result as $row)
            {
                $res[] = ['id' => $row->ID_JABATAN, 'name' => $row->NAMA_JABATAN];
            }

            $data = ['item' => $res];

            echo json_encode($data);
        }else{
            $where = ['IS_ACTIVE' => '1', 'ID_JABATAN' => $id];

            $result = $this->mglobal->get_data_all('M_JABATAN', null, $where,
                'ID_JABATAN, NAMA_JABATAN', null, null , null , '15');

            $res = [];
            // if(empty($result))
            // {
            //     $result = $this->mglobal->get_data_all('M_INST_SATKER', null, null,
            //     'INST_SATKERKD, INST_NAMA', null, null , null , '15');
            // }
            foreach ($result as $row)
            {
                $res[] = ['id' => $row->ID_JABATAN, 'name' => $row->NAMA_JABATAN];
            }

            echo json_encode($res);
        }
    }

    /** Form Konfirmasi Hapus Penyelenggara Negara
     * 
     * @return html form konfirmasi hapus Penyelenggara Negara
     */
    public function deletepn($id){
        $this->load->model('mpn', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->mpn->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
    }

    /** Detail Penyelenggara Negara
     * 
     * @return html detail Penyelenggara Negara
     */    
    public function detailpn($id){
        $this->load->model('mpn', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->mpn->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
    }

    
    public function pensiun($offset = 0){
		// load model
		$this->load->model('mpn', '', TRUE);

		// prepare paging
		$this->base_url	 = site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 3;
		$this->offset	   = $this->uri->segment($this->uri_segment);
		
		// filter
		$cari             = '';
		$filter		     = '';
		if($_POST && $this->input->post('table_search', TRUE)!=''){
			$cari = $this->input->post('table_search', TRUE);
			$filter = array(
				'NAMA' => $this->input->post('table_search', TRUE),
			);			
		}

		// load and packing data
		$this->items		= $this->mpn->get_paged_list($this->limit, $this->offset, $filter)->result();
		$this->total_rows   = $this->mpn->count_all($filter);

		$this->load->model('minstansi', '', TRUE);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'E Registration' => 'index.php/welcome/eregistration',
									'Penyelenggara Negara'  => 'index.php/'.strtolower(__CLASS__).'/index',
									'Pensiun'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
			'instansis'      => $this->minstansi->list_all()->result(),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

    public function nonaktif($offset = 0 , $cetak=false)
    {
        // load session
        $INST_SATKERKD = $this->session->userdata('INST_SATKERKD');
        
        // load model
        $this->load->model('mpn', '', TRUE);

        // prepare paging
        $this->base_url  = 'index.php/ereg/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/';
        $this->uri_segment  = 4;
        $this->offset      = $this->uri->segment($this->uri_segment);
        

        $this->db->start_cache();

        $this->db->select('T_PN.*, JABATAN.*, T_USER.ID_USER');
        $this->db->from('T_PN');
        $this->db->join('(
            select  T_PN_JABATAN.ID_PN AS ID_PN_DIJABATAN, 
                group_concat(CONCAT( REPEAT( "0", 5 - LENGTH( T_PN_JABATAN.LEMBAGA ) ), T_PN_JABATAN.LEMBAGA )) LEMBAGA,
                group_concat(CONCAT(REPEAT("0", 5-LENGTH(T_PN_JABATAN.ID_JABATAN)),T_PN_JABATAN.ID_JABATAN)) JABATAN, 
                group_concat(
                    CONCAT(
                        IFNULL(T_PN_JABATAN.ID,""),":58:",
                        IFNULL(T_PN_JABATAN.ID_STATUS_AKHIR_JABAT,""),":58:",
                        IFNULL(T_STATUS_AKHIR_JABAT.STATUS,""),":58:",
                        IFNULL(T_MUTASI_PN.ID_PN_JABATAN,""),":58:",
                        IFNULL(T_PN_JABATAN.LEMBAGA,""),":58:",
                        IFNULL(M_JABATAN.NAMA_JABATAN,""), 
                        "(", IFNULL(M_ESELON.ESELON,""), ") - ", 
                        IFNULL(M_UNIT_KERJA.UK_NAMA,"")," - ", 
                        IFNULL(M_INST_SATKER.INST_AKRONIM,"")
                    )
                ) as NAMA_JABATAN
            from T_PN_JABATAN
                LEFT JOIN M_JABATAN ON M_JABATAN.ID_JABATAN = T_PN_JABATAN.ID_JABATAN
                LEFT JOIN M_INST_SATKER ON M_INST_SATKER.INST_SATKERKD = T_PN_JABATAN.LEMBAGA
                LEFT JOIN M_UNIT_KERJA ON M_UNIT_KERJA.UK_ID = T_PN_JABATAN.UNIT_KERJA
                LEFT JOIN M_ESELON ON M_ESELON.ID_ESELON = T_PN_JABATAN.ESELON
                LEFT JOIN T_STATUS_AKHIR_JABAT ON T_STATUS_AKHIR_JABAT.ID_STATUS_AKHIR_JABAT = T_PN_JABATAN.ID_STATUS_AKHIR_JABAT
                LEFT JOIN T_MUTASI_PN ON T_MUTASI_PN.ID_PN_JABATAN = T_PN_JABATAN.ID
                GROUP BY T_PN_JABATAN.ID_PN
            ) JABATAN', 'JABATAN.ID_PN_DIJABATAN = T_PN.ID_PN', 'left');
        $this->db->join('T_USER', 'T_USER.USERNAME = T_PN.NIK', 'left');
        $this->db->where('T_PN.IS_ACTIVE !=', '1');
        
        $this->db->where("JABATAN.LEMBAGA like '%".str_repeat('0',5-strlen($INST_SATKERKD)).$INST_SATKERKD."%'", null, false);

        if(@$this->CARI['TEXT']){
            $this->db->like('T_PN.NAMA', $this->CARI['TEXT']);
        } 

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
                                    'E-reg'  => 'index.php/dashboard/efilling',
                                    'PN'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            'pagination'    => call_user_func('ng::genPagination'),
            'instansis'     => $this->mglobal->get_data_all('M_INST_SATKER'),
        );

        // load view
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);

        return;

		// load model
		$this->load->model('mpn', '', TRUE);

		// prepare paging
		$this->base_url	 = site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 3;
		$this->offset	   = $this->uri->segment($this->uri_segment);
		
		// filter
		$cari             = '';
		$filter		     = '';
		if($_POST && $this->input->post('table_search', TRUE)!=''){
			$cari = $this->input->post('table_search', TRUE);
			$filter = array(
				'NAMA' => $this->input->post('table_search', TRUE),
			);			
		}

		// load and packing data
		$this->items		= $this->mpn->get_nonaktif_list($this->limit, $this->offset, $filter)->result();
		$this->total_rows   = $this->mpn->count_all($filter);

		$this->load->model('minstansi', '', TRUE);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'E Registration' => 'index.php/welcome/eregistration',
									'Penyelenggara Negara'  => 'index.php/'.strtolower(__CLASS__).'/index',
									'Non Aktif'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
			'instansis'      => $this->minstansi->list_all()->result(),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }
	
	public function mutasimasuk($offset = 0, $cetak=false){
    	// echo 'Masuk';
    	$this->load->model('mmutasi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
		// prepare paging
		$this->base_url	 = site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 3;
		$this->offset	   = $this->uri->segment($this->uri_segment);

		// filter
		$cari           = '';
		$filter		    = '';
		if($_POST && $this->input->post('table_search', TRUE)!=''){
			$cari = $this->input->post('table_search', TRUE);
			$filter = array(
				'NAMA' => $this->input->post('table_search', TRUE)
			);
		}

		// load and packing data
		$this->items		= $this->mmutasi->get_paged_list2($this->limit, $this->offset, $filter)->result();
		//var_dump($this->session->userdata);
		$this->total_rows   = $this->mmutasi->count_all($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb',
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'E Registration' => 'index.php/welcome/eregistration',
									'Penyelenggara Negara'  => 'index.php/'.strtolower(__CLASS__).'/index',
									'Mutasi Masuk'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

	//start update by sukma
	/** Mutasi Keluar Penyelenggara Negara
     * 
     * @return html Mutasi Keluar Penyelenggara Negara
     */  
    public function mutasikeluar($offset = 0){
    	// echo 'Pindah';
    	$this->load->model('mmutasi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('munitkerja', '', TRUE);
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
				'NAMA' => $this->input->post('cari', TRUE)
			);			
		}

		// load and packing data
		$this->items		= $this->mmutasi->get_paged_list($this->limit, $this->offset, $filter)->result();
		//var_dump($this->session->userdata);
		$this->total_rows   = $this->mmutasi->count_all($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'E Registration' => 'index.php/welcome/eregistration',
									'Penyelenggara Negara'  => 'index.php/'.strtolower(__CLASS__).'/index',
									'Mutasi Keluar'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }
	
	public function addmutasi(){
		$this->load->model('mmutasi', '', TRUE);
        $data = array(
            'form'      => 'addmutasi_keluar',
			'url_load_pejabat' => 'index.php/ereg/'.strtolower(__CLASS__).'/load_pejabat',
			'url_load_instansi' => 'index.php/ereg/'.strtolower(__CLASS__).'/load_instansi'
        );
		$this->load->view(strtolower(__CLASS__).'/'.'pn_mutasi_form',$data);
	}

    public function save_edit_mutasi() {
        if ( $_POST && $this->input->post('JABATAN') ) {
            $this->load->model('mmutasi');
            $this->load->model('muser');
            $this->load->model('mpn');
            $inst_asal      = $this->input->post('ID_INST_ASAL');
            $inst_tujuan    = $this->input->post('INST_TUJUAN');
            $username       = $this->input->post('USERNAME');
            $id_jabatan     = $this->input->post('JABATAN');
            $id_user        = $this->input->post('ID_USER');
            $id_pn          = $this->mmutasi->get_id_pn($username);

            if ( !empty($id_pn) ) {
                $data_update = array(
                    'ID_INST_TUJUAN'    => empty($inst_tujuan) ? NULL : $inst_tujuan,
                    'ID_JABATAN'        => $id_jabatan,
                );
                $save = $this->mmutasi->update($id_pn, $data_update);
                echo '1';
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }

	public function editmutasi($id){
		$this->load->model('mmutasi', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form'      => 'editmutasi_keluar',
			'url_load_pejabat' => 'index.php/'.strtolower(__CLASS__).'/load_pejabat',
			'url_load_instansi' => 'index.php/'.strtolower(__CLASS__).'/load_instansi',
			'items' => $this->mmutasi->get_mutasi_by_id($id)->row(),
            'instansis'      => $this->minstansi->list_all()->result(),
            'jabatans'  => $this->mjabatan->list_all()->result(),
        );

        $data['data_pn']    = $this->mpn->get_by_id($data['items']->ID_PN)->row();
		$data['sk_jab']     = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
		$this->load->view(strtolower(__CLASS__).'/'.'pn_mutasi_form',$data);
	}
	
	public function deletemutasi($id){
		$this->load->model('mmutasi', '', TRUE);
        $data = array(
            'form'      => 'deletemutasi_keluar',
			'url_load_pejabat' => 'index.php/'.strtolower(__CLASS__).'/load_pejabat',
			'url_load_instansi' => 'index.php/'.strtolower(__CLASS__).'/load_instansi',
			'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
		$this->load->view(strtolower(__CLASS__).'/'.'pn_mutasi_form',$data);
	}
	
	public function load_pejabat(){
		$this->load->model('mmutasi', '', TRUE);
		$post = $this->input->post_get('q');
		$result = $this->mmutasi->load_pejabat($post);
		$data = array();
		foreach($result as $val){
			// $_data['id'] = $val->ID_PN;
			// $_data['label'] = $val->NAMA;
			// $_data['value'] = $val->NIK;
			echo $val->NAMA . "|". $val->ID_PN."\n";
		}
		
		// echo ($_data);
	}
	
	public function load_instansi(){
		$this->load->model('mmutasi', '', TRUE);
		$post = $this->input->post_get('q');
		$result = $this->mmutasi->load_instansi($post);
		$data = array();
		foreach($result as $val){
			echo $val->INST_NAMA."|".$val->INST_SATKERKD."\n";
		}
		
		//	echo ($_data);
	}
    
    public function dodeletemutasi() {
        $this->load->model('mmutasi');
        $this->db->trans_begin();
        $pejabat['ID_MUTASI']    = $this->input->post('id_mutasi', TRUE);
        $this->mmutasi->delete($pejabat['ID_MUTASI']);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
	//save data
	/*function savemutasi(){
		$this->db->trans_begin();
        $this->load->model('mmutasi', '', TRUE);
		$this->load->model('mpn', '', TRUE);
		 //save data mutasi
		 if($this->input->post('act', TRUE)=='doinsert'){
			$status = 0;
			//status langsung approved jika mutasi satu instansi
			if($this->session->userdata('INST_SATKERKD') == $this->input->post('id_instansi')){
				$dt_update = array('JABATAN'=>$this->input->post('jabatan'));
				$this->mpn->update($this->input->post('id_pN'),$dt_update);
				$status = 1;
			}
			$dt_insert = array('ID_PN' => $this->input->post('id_pN'),
					'ID_INST_ASAL' => $this->session->userdata('INST_SATKERKD'),
					'ID_INST_TUJUAN' => $this->input->post('id_instansi'),
					'STATUS_APPROVAL' => $status,
					'JABATAN' => $this->input->post('jabatan'));
			$save = $this->mmutasi->save($dt_insert);			
			$dt_his_jab = array(
				'ID_PEJABAT' 	=> $this->input->post('id_pejabat'),
				'INS_SATKERKD' 	=> $this->input->post('id_instansi'),
				'NO_SK' 		=> $this->input->post('no_sk'),
				'TMT' 			=> $this->input->post('tmt'),
				'SD' 			=> $this->input->post('sd'),
				'NAMA_JABATAN' 	=> $this->input->post('jabatan'),
				'ID_MUTASI' 	=> $save
			);
			$this->mmutasi->insert_history_jabatan($dt_his_jab);
		
		// update mutasi	
		}else if($this->input->post('act', TRUE)=='doupdate'){
			//status langsung approved jika mutasi masih dalam satu instansi
			$status=0;
			if($this->session->userdata('INST_SATKERKD') == $this->input->post('id_instansi')){
				$dt_update = array('JABATAN'=>$this->input->post('jabatan'));
				$this->mpn->update($this->input->post('id_pN'),$dt_update);
				$status = 1;
			}
			$dt_insert = array('ID_PN' => $this->input->post('id_pN'),
					'ID_INST_ASAL' => $this->session->userdata('INST_SATKERKD'),
					'ID_INST_TUJUAN' => $this->input->post('id_instansi'),
					'JABATAN' => $this->input->post('jabatan'),
					'STATUS_APPROVAL' =>$status
					);
			$idm = 	$this->input->post('id_mutasi');	
			$this->mmutasi->update($idm,$dt_insert);
			
			$dt_his_jab = array('ID_PN' => $this->input->post('id_pN'),
					'INS_SATKERKD' => $this->input->post('id_instansi'),
					'NO_SK' => $this->input->post('no_sk'),
					'TMT' => $this->input->post('tmt'),
					'SD' => $this->input->post('sd'),
					'NAMA_JABATAN' => $this->input->post('jabatan'));
			$this->mmutasi->update_history_jabatan($idm,$dt_his_jab);
		
		//delete data mutasi 
		}else if($this->input->post('act', TRUE)=='dodelete'){
            $pejabat['ID_MUTASI']    = $this->input->post('id_mutasi', TRUE);
            $this->mmutasi->delete($pejabat['ID_MUTASI']);
			$this->mmutasi->del_jab_by_mutasi($pejabat['ID_MUTASI']);
        
		// update data jika diapprove	
		}else if($this->input->post('act', TRUE)=='doapprove'){
			
			$this->load->model('mpn', '', TRUE);
            $up_mutasi = array('STATUS_APPROVAL' => 1);
			$up_pejabat = array(
					'LEMBAGA' => $this->input->post('id_instansi'),
					'JABATAN' => $this->input->post('jabatan')
					);		
			$idm = 	$this->input->post('id_mutasi');
			$idp = 	$this->input->post('id_pN');						
			$this->mmutasi->update($idm,$up_mutasi);
			$this->mpn->update($idm,$up_pejabat);
		
		// update data jika mutasi di tolak	
		}else if($this->input->post('act', TRUE)=='tolakmutasi'){
			$this->load->model('mmutasi', '', TRUE);
			$up_mutasi = array('STATUS_APPROVAL' => -1);
			$idm = $this->input->post('id_mutasi'); 
			$this->mmutasi->update($idm,$up_mutasi);
		}

		if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
	} */
    
    public function savetolakmutasi() {
        $this->db->trans_begin();

        $this->load->model('mmutasi', '', TRUE);
        $up_mutasi = array('STATUS_APPROVAL' => -1);
        $idm = $this->input->post('id_mutasi');
        $this->mmutasi->update($idm,$up_mutasi);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

	// load page approved
	public function approvmutasi($id){
		$this->load->model('mmutasi', '', TRUE);
        $data = array(
            'form'      => 'approvmutasi',
			'url_load_pejabat' => 'index.php/'.strtolower(__CLASS__).'/load_pejabat',
			'url_load_instansi' => 'index.php/'.strtolower(__CLASS__).'/load_instansi',
			'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
		$data['sk_jab'] = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
		$this->load->view(strtolower(__CLASS__).'/'.'pn_mutasi_form',$data);
	}

    public function saveapprove() {
        $this->db->trans_begin();
        if($this->input->post('act')=='doapprove'){
            $this->load->model('mmutasi', '', TRUE);
            $this->load->model('muser', '', TRUE);
            $this->load->model('mpn', '', TRUE);
            $this->load->model('mglobal', '', TRUE);

            $id_mutasi      = $this->input->post('id_mutasi');
            $id_pn          = $this->input->post('id_pejabat');
            $data_pn        = $this->mpn->get_by_id($id_pn)->row();
            $id_user        = $this->muser->get_id_by_username($data_pn->NIK);
            $id_instansi    = $this->input->post('id_instansi');
            $id_jabatan     = $this->input->post('id_jabatan');
            $data_mutasi    = $this->mmutasi->get_mutasi_by_id($id_mutasi)->row();

            // insert jabatan baru
            $data_pn      = array(
                'ID_PN'         => $id_pn,
                'ID_JABATAN'    => $data_mutasi->ID_JABATAN_BARU,
                'LEMBAGA'       => $data_mutasi->ID_INST_TUJUAN,
                'UNIT_KERJA'    => $data_mutasi->UNIT_KERJA_BARU,
                'ESELON'        => $data_mutasi->ESELON_BARU,
            );
            $update_pn      = $this->mpn->save_jabatan_pn($data_pn);

            // update jabatan lama
            $mutasi = $this->mglobal->get_data_all('T_MUTASI_PN', null,null,'*', "ID_MUTASI = '$id_mutasi'")[0];
            
            $pnjabatan['SD'] = date('Y-m-d', strtotime($this->input->post('SD_MENJABAT')));
            $pnjabatan['ID_STATUS_AKHIR_JABAT'] = $mutasi->ID_STATUS_AKHIR_JABAT;
            $this->db->where('SD', '');
            $this->db->where('ID', $mutasi->ID_PN_JABATAN);
            // $this->db->where('ID_PN', $id_pn);
            $this->db->update('T_PN_JABATAN', $pnjabatan);

            // delete temp mutasi
            $this->mglobal->delete('T_MUTASI_PN', '1=1', "ID_MUTASI = '$id_mutasi'");
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());        
    }
	
	//load page tolak mutasi
	public function tolakmutasi($id){
		$this->load->model('mmutasi', '', TRUE);
        $data = array(
            'form'      => 'tolakmutasi',
			'url_load_pejabat' => 'index.php/'.strtolower(__CLASS__).'/load_pejabat',
			'url_load_instansi' => 'index.php/'.strtolower(__CLASS__).'/load_instansi',
			'items' => $this->mmutasi->get_mutasi_by_id($id)->row()
        );
		$data['sk_jab'] = $this->mmutasi->get_his_jab_last($data['items']->ID_PN);
		$this->load->view(strtolower(__CLASS__).'/'.'pn_mutasi_form',$data);
	}

	public function getUser($id = NULL)
	{
		$this->load->model('mglobal', '', TRUE);

		if(is_null($id)){
	        $q = $_GET['q'];

	        $where = 'IS_ACTIVE = 1';

	        $result = $this->mglobal->get_data_all('T_PN', null, $where,
	            'ID_PN, NAMA, NIK', "NAMA LIKE '%$q%' OR NIK LIKE '%$q%'");

	        $res = array();
	        foreach ($result as $row)
	        {
	            $res[] = array('id' => $row->ID_PN, 'name' => $row->NIK.' - '.$row->NAMA);
	        }

	        $data = array('item' => $res);

	        echo json_encode($data);
		}else{
	        $where = array('IS_ACTIVE' => '1', 'NIK' => $id);

	        $result = $this->mglobal->get_data_all('T_PN', null, $where,
	            'ID_PN, NAMA, NIK');

	        $res = array();
	        foreach ($result as $row)
	        {
	            $res[] = array('id' => $row->ID_PN, 'name' => $row->NIK.' - '.$row->NAMA);
	        }

	        echo json_encode($res);
		}
	}
    
    public function cek_user($username){
        $this->load->model('muser', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $check   = $this->muser->check_user_if_exist($username);
        if(!empty($check)){
            $data_pn = $this->mpn->get_by_nik($username)->row();
            echo json_encode(array('success' => 1, 'result' => $data_pn));
        }else{
            echo json_encode(array('success' => 0));
        }
    }
    
    public function cek_user_edit($username, $current_username){
        $this->load->model('muser', '', TRUE);
        $check = $this->muser->check_user_for_edit($username, $current_username);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }
    
    public function cek_email($email){
        $this->load->model('muser', '', TRUE);
        $decode = urldecode($email);
        $check  = $this->muser->check_email_if_exist($decode, 1);
        if(!empty($check)){
            echo '1';
        }else{
            echo '0';
        }
    }
    
    public function mts($idjabatan) {
        $this->makses->check_is_write();
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('mpn', '', TRUE);
        $this->load->model('mjabatan', '', TRUE);
        $join = [
                            ['table' => 'M_JABATAN as jabatan'                , 'on' => 'data.ID_JABATAN   = jabatan.ID_JABATAN'],
                        ];
        $select     = 'data.ID_PN as ID_PN, jabatan.NAMA_JABATAN as NAMA_JABATAN, data.id as ID';
        $pnjabatan = $this->mglobal->get_data_all('T_PN_JABATAN as data', $join, ['ID' => $idjabatan], $select)[0];
        $pn         = $this->mglobal->get_data_all('T_PN',null,['ID_PN' => $pnjabatan->ID_PN])[0];
        $user       = $this->muser->get_by_id($pnjabatan->ID)->row();
        $eselon = $this->mglobal->get_data_all('M_ESELON');
        $status_akhir = $this->mglobal->get_status_akhir();
        $instansi_asal = $this->mpn->get_instansi_pn($pn->ID_PN);

        $data = array(
            'id'        => $idjabatan,
            'status_akhir' =>$status_akhir,
            'form'      => 'mutasi',
            'item_data' => $user,
            'item_pn'   => $pn,
            'eselon'    => $eselon,
            'jabatan'   => $pnjabatan,
            'instansi_asal' => $instansi_asal,
            'instansis' => $this->minstansi->list_all()->result(),
            'jabatans'  => $this->mjabatan->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
        // $this->makses->check_is_write();
        // $this->load->model('muser', '', TRUE);
        // $this->load->model('minstansi', '', TRUE);
        // $this->load->model('mjabatan', '', TRUE);
        // $data = array(
        //     'form'      => 'mutasi',
        //     'item'      => $this->muser->get_by_id($id_user)->row(),
        //     'instansis'      => $this->minstansi->list_all()->result(),
        //     'jabatans'  => $this->mjabatan->list_all()->result(),
        // );
        // $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    public function savemutasi() {
        if ( $_POST && $this->input->post('JABATAN') ) {
            $this->load->model('mmutasi');
            $this->load->model('muser');
            $this->load->model('mpn');
            $inst_asal      = $this->input->post('ID_INST_ASAL');
            $inst_tujuan    = $this->input->post('INST_TUJUAN');
            $username       = $this->input->post('USERNAME');
            $id_jabatan     = $this->input->post('JABATAN');
            $id_user        = $this->input->post('ID_USER');
            $id_pn          = $this->mmutasi->get_id_pn($username);

            if ( !empty($id_pn) ) {
                $data_insert = array(
                    'ID_PN'             => $id_pn,
                    'ID_INST_ASAL'      => $inst_asal,
                    'ID_INST_TUJUAN'    => empty($inst_tujuan) ? NULL : $inst_tujuan,
                    'ID_JABATAN'        => $id_jabatan,
                );
                $save = $this->mmutasi->save($data_insert);
                $data_update_pn = array(
                    'ID_JABATAN'            => $id_jabatan
                );
                $update_pn = $this->mpn->update($id_pn, $data_update_pn);

                echo '1';
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }
    
    public function reset_password($id_user) {
        $this->makses->check_is_write();
        $this->load->model('muser', '', TRUE);
        $data = array(
            'form'      => 'reset_password',
            'item'      => $this->muser->get_by_id($id_user)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
    }
    
    public function do_reset_password() {
        $id_user = $this->input->post('ID_USER');
        $this->makses->check_is_write();
        $this->load->model('muser');
        $password = $this->muser->createRandomPassword(6);
        $data_user = $this->muser->get_by_id($id_user)->row();
        $kirim_info = $this->muser->kirim_info_akun($data_user->EMAIL, $data_user->USERNAME, $password, 'Pemberitahuan Reset Password');
        if ( $kirim_info ) {
            $data_update = array(
                'password' => sha1(md5($password))
            );
            $update = $this->muser->update($id_user, $data_update);
            echo 1 ;
        }
        else {
            echo 0;
        }
    }
    
    public function getInfoPn($id){
    	$this->load->model('mglobal');
    	$tmp = $this->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $id], 'FOTO, NAMA, NIK, JNS_KEL, TEMPAT_LAHIR, TGL_LAHIR, NPWP, ALAMAT_TINGGAL, EMAIL, NO_HP')[0];

    	$data['data']	= $tmp;
		$this->load->view('pn/'.strtolower(__CLASS__).'_detail', $data);
    }

	//end update
    public function editjabatan($id){
    	// echo "form edit jabatan ajaxFormEditJabatan";
        $this->load->model('mpn', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $joinJabatan = [
        	['table'=>'M_INST_SATKER', 'on'=>'T_PN_JABATAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD']
        ];        
        $data = array(
            'form'      => 'editJabatan',
            'item'      => $this->mpn->get_by_id($id)->row(),
            'instansis'      => $this->minstansi->list_all()->result(),
            'jabatans' => $this->mglobal->get_data_all('T_PN_JABATAN', $joinJabatan, NULL, '*', "T_PN_JABATAN.ID_PN = '$id'"),
        );    	
    	$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_form', $data);
    }
    
    public function savejabatan(){
    	display($_POST);
//     	Array
// (
//     [LEMBAGA1] => 1
//     [UNIT_KERJA1] => 
//     [ALAMAT_KANTOR1] => 
//     [EMAIL_KANTOR1] => 
//     [ID_PN] => 3
//     [act] => doaddjabatan
// )
    }	
}
