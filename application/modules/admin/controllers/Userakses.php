<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller User Akses
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Userakses
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userakses extends CI_Controller {
	// num of records per page
	public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->makses->check_is_read();
    }

    /** Role List
     * 
     * @return html Role List
     */    
	public function index($offset = 0)
	{
		// load model
		$this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);

		// prepare paging
		$this->base_url	 = site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment  = 3;
		$this->offset	   = $this->uri->segment($this->uri_segment);
		
		// filter
		$cari             = '';
		$filter		     = array();

		// load and packing data
		$this->items		= $this->muser->get_list_alluser($this->limit, $this->offset, $filter, 'ID_ROLE != '.ID_ROLE_ADMAPP)->result();
		$this->total_rows   = $this->muser->count_alluser($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
									'Administrator'	 => 'index.php/dashboard/administrator',
									'User Akses'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);		
	}	


    /** Process Insert, Update, Delete, update Setting 
     * 
     * @return boolean process data role
     */
    function savepermission(){
        $this->makses->check_is_write();
        $this->db->trans_begin();
        $this->load->model('mmenu', '', TRUE);
        $this->load->model('muser', '', TRUE);
        $read   = $this->input->post('PERMISSION_READ');
        $write  = $this->input->post('PERMISSION_WRITE');
        $permission = array();
        for ( $i=0; $i<count($read); $i++ ) {
            $id_menu    = $read[$i];
            $data_menu  = $this->mmenu->get_data_menu($id_menu, 'CONTROLLER, METHOD');
            if ( $data_menu ) {
                $permission[$id_menu] = array(
                    'CONTROLLER'    => $data_menu->CONTROLLER,
                    'METHOD'        => $data_menu->METHOD,
                    'IS_READ'       => 1,
                    'IS_WRITE'      => 0
                );
            }
        }
        for ( $i=0; $i<count($write); $i++ ) {
            $id_menu    = $write[$i];
            if ( array_key_exists($id_menu, $permission) ) {
                $permission[$id_menu]['IS_WRITE'] = 1;
            }
        }

        $role = array(
            'PERMISSION'    => json_encode($permission),
            'UPDATED_TIME'  => time(),
            'UPDATED_BY'    => $this->session->userdata('USR'),
            'UPDATED_IP'    => $_SERVER["REMOTE_ADDR"],
        );
        $this->muser->update($this->input->post('ID_USER', TRUE), $role);
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Role
     * 
     * @return html form tambah role
     */
    function userakses_permission($id_user = 0){
        // load model
        $this->load->model('mmenu');
        $get_permission = $this->makses->get_permission_by_user($id_user);
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb',
                            array(
                                'Dashboard'  => 'index.php/welcome/dashboard',
                                'Administrator'	 => 'index.php/dashboard/administrator',
                                'Edit Hak Akses'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                            )),

            'form'      => 'edit',
            'menu'      => $this->mmenu->get_edit_menu(0, $get_permission),
            'id_user'   => $id_user
        );

        // load view
        $this->load->view(strtolower(__FUNCTION__), $data);
    }
}