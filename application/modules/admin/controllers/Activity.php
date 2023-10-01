<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Activity
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Activity
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends CI_Controller {
	// num of records per page
	public $limit = 10;
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
    }
	
	public function index($offset = 0)
	{
		// load model
		$this->load->model('mactivity', '', TRUE);

		// prepare paging
		$this->base_url	 = site_url('/admin/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 4;
		$this->offset	   = $this->uri->segment($this->uri_segment);
		
		// filter
		$filter		     = '';
        $cari_activity = $this->input->post('cari', TRUE);
        $cari_username = $this->input->post('cari_username', TRUE);
        $cari_waktu    = $this->input->post('cari_waktu', TRUE);
		
		if($cari_username != ''){
			$filter = array(
				'ACTIVITY'          => $cari_activity,
                'USERNAME'          => $cari_username
			);
		}

		if($cari_waktu != ''){
			$filter['CREATED_TIME'] = $cari_waktu;
		}
                
                if($cari_activity != ''){
			$filter['ACTIVITY'] = $cari_activity;
		}

		// load and packing data		
		$this->items		= $this->mactivity->get_paged_list($this->limit, $this->offset, $filter)->result();
		/*print_r($filter);
		display($this->db->last_query());*/
		$this->total_rows   = $this->mactivity->count_all($filter);

		$data = array(
			'items'         => $this->items,
            'ACTIVITY'      => $cari_activity,
            'ROLE'          => $this->input->post('role'),
            'USERNAME'      => $cari_username,
            'CREATED_TIME'  => $cari_waktu,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'Activity'  => 'index.php/admin/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
			'roles' 		=> $this->mactivity->list_role()->result(),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	/** Process Insert, Update, Delete Activity
     * 
     * @return boolean process Activity
     */
    function saveactivity(){
        $this->db->trans_begin();
        $this->load->model('mactivity', '', TRUE);
     
        if($this->input->post('act', TRUE)=='doinsert'){
            $activity = array(
                'ID_ACTIVITY' 		=> $this->input->post('ID_ACTIVITY', TRUE),
				'USERNAME' 		=> $this->input->post('USERNAME', TRUE),
				'ACTIVITY' 		=> $this->input->post('ACTIVITY', TRUE),
                'CREATED_TIME'     => time(),
                'CREATED_BY'     => $this->session->userdata('USR'),
                'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $this->mactivity->save($activity);
        }else if($this->input->post('act', TRUE)=='doupdate'){
            $activity = array(
                'ID_ACTIVITY' 		=> $this->input->post('ID_ACTIVITY', TRUE),
				'USERNAME' 		=> $this->input->post('USERNAME', TRUE),
				'ACTIVITY' 		=> $this->input->post('ACTIVITY', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            $activity['ID_ACTIVITY']    = $this->input->post('ID_ACTIVITY', TRUE);
            $this->mactivity->update($activity['ID_ACTIVITY'], $activity);
        }else if($this->input->post('act', TRUE)=='dodelete'){
            $activity['ID_ACTIVITY']    = $this->input->post('ID_ACTIVITY', TRUE);
            $this->mactivity->delete($activity['ID_ACTIVITY']);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
    
    /** Form Tambah Activity
     * 
     * @return html form tambah Activity
     */
    function addactivity(){
        $this->load->model('mactivity', '', TRUE);
        $data = array(
            'form'      => 'add',
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit Activity
     * 
     * @return html form edit Activity
     */
    function editactivity($id){
        $this->load->model('mactivity', '', TRUE);
        $data = array(
            'form'      => 'edit',
            'item'      => $this->mactivity->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus Activity
     * 
     * @return html form konfirmasi hapus Activity
     */
    function deleteactivity($id){
        $this->load->model('mactivity', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->mactivity->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail Activity
     * 
     * @return html detail Activity
     */    
    function detailactivity($id){
        $this->load->model('mactivity', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->mactivity->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }
	
	function daftar_role(){
        $data = [];
        $this->load->model('mactivity', '', TRUE);
        $role = $this->mactivity->get_role();
        foreach ($role as $key) {
            $data[$key->ID_ROLE] = $key->ROLE;
        }
        echo json_encode($data);
    }
	
	function daftar_user($id_role){
        $data = [];
        $this->load->model('mactivity', '', TRUE);
        if(!$id_role){
            $data[$key->USERNAME] = '-Pilih Username-';
        }
        else
        {
            $user = $this->mactivity->get_user($id_role);
    //        array_push($data,  "-Pilih Username-");
    //        print_r("data: ".$data[$key->USERNAME]);
            foreach ($user as $key) {
                $data[$key->USERNAME] = $key->USERNAME;
            }
        }
        echo json_encode($data);
        
    }
}
