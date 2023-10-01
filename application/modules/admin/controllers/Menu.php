<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Menu
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Menu
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {
	// num of records per page
	public $limit = 1000;

    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
    }
    
    /** Manu List
     * 
     * @return html Manu List
     */    
	public function index($offset = 0)
	{
		// load model
		$this->load->model('mmenu', '', TRUE);

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
				// 'NOMOR'	 => $this->input->post('cari', TRUE),
				// 'ASAL_SURAT'	   => $this->input->post('cari', TRUE),
				// 'PERIHAL'	  => $this->input->post('cari', TRUE),
			);			
		}

		// load and packing data
		$this->items		= $this->mmenu->get_paged_list($this->limit, $this->offset, $filter)->result();
		$this->total_rows   = $this->mmenu->count_all($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
                                    'Administrator'  => 'index.php/dashboard/administrator',
									'Menu'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	/** Process Insert, Update, Delete Menu
     * 
     * @return boolean process 
     */
    function savemenu(){
        $this->db->trans_begin();
        $this->load->model('mmenu', '', TRUE);
     
        if($this->input->post('act', TRUE)=='doinsert'){
            
            $menu = array(
                'PARENT'     => $this->input->post('PARENT', TRUE),
                'WEIGHT'     => $this->input->post('WEIGHT', TRUE),
                'ICON'       => $this->input->post('ICON', TRUE),
                'ICON_COLOR' => $this->input->post('ICON_COLOR', TRUE),
                'MENU'       => $this->input->post('MENU', TRUE),
                'URL'        => $this->input->post('URL', TRUE),
                'IS_ACTIVE'  => $this->input->post('IS_ACTIVE', TRUE),
                'SYSNAME'    => $this->input->post('SYSNAME', TRUE),
                'MODULE'    => $this->input->post('MODULE', TRUE),
                'CONTROLLER'    => $this->input->post('CONTROLLER', TRUE),
                'METHOD'    => $this->input->post('METHOD', TRUE),
                'CREATED_TIME'     => time(),
                'CREATED_BY'     => $this->session->userdata('USR'),
                'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                 
            );
            $this->mmenu->save($menu);
            ng::logActivity('Tambah Menu '.$this->input->post('MENU', TRUE).', id = '.$this->db->insert_id());
            
        }else if($this->input->post('act', TRUE)=='doupdate'){
            
            $menu = array(
                'PARENT'     => $this->input->post('PARENT', TRUE),
                'WEIGHT'     => $this->input->post('WEIGHT', TRUE),
                'ICON'       => $this->input->post('ICON', TRUE),
                'ICON_COLOR' => $this->input->post('ICON_COLOR', TRUE),
                'MENU'       => $this->input->post('MENU', TRUE),
                'URL'        => $this->input->post('URL', TRUE),
                'IS_ACTIVE'  => $this->input->post('IS_ACTIVE', TRUE),
                'SYSNAME'    => $this->input->post('SYSNAME', TRUE),
                'MODULE'    => $this->input->post('MODULE', TRUE),
                'CONTROLLER'    => $this->input->post('CONTROLLER', TRUE),
                'METHOD'    => $this->input->post('METHOD', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                 
            );
            $menu['ID_MENU']    = $this->input->post('ID_MENU', TRUE);
            $this->mmenu->update($menu['ID_MENU'], $menu);
            ng::logActivity('Edit Menu, id = '.$menu['ID_MENU']);
                        
        }else if($this->input->post('act', TRUE)=='dodelete'){
            
            $menu['ID_MENU']    = $this->input->post('ID_MENU', TRUE);
            $this->mmenu->delete($menu['ID_MENU']);
			ng::logActivity('Hapus Menu, id = '.$menu['ID_MENU']);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());             
    }
    
    /** Form Tambah Menu
     * 
     * @return html form tambah Menu
     */
    function addmenu(){
        $this->load->model('mmenu', '', TRUE);
        $data = array(
            'form'      => 'add',
            'parents'   => $this->mmenu->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit Menu
     * 
     * @return html form edit Menu
     */
    function editmenu($id){
        $this->load->model('mmenu', '', TRUE);
        $data = array(
            'form'      => 'edit',
            'item'      => $this->mmenu->get_by_id($id)->row(),
            'parents'   => $this->mmenu->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus Menu
     * 
     * @return html form konfirmasi hapus Menu
     */
    function deletemenu($id){
        $this->load->model('mmenu', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->mmenu->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail Menu
     * 
     * @return html detail Menu
     */    
    function detailmenu($id){
        $this->load->model('mmenu', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->mmenu->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }
}
