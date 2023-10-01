<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Role
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Role
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CI_Controller {
	// num of records per page
	public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        // $this->makses->initialize();
        // $this->makses->check_is_read();
        foreach ((array)@$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"]; 
    }

    /** Role List
     * 
     * @return html Role List
     */    
	public function index($offset = 0)
	{
		// load model
		$this->load->model('mglobal', '', TRUE);
        $this->load->model('mrole', '', TRUE);

		// prepare paging
        $this->base_url       = site_url('/admin/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
		$this->uri_segment    = 4;
		$this->offset	      = $this->uri->segment($this->uri_segment);
		
		// filter
		$cari             = '';
		$filter		      = array(
                                    'ID_ROLE !='=>ID_ROLE_ADMAPP,
                                    'ROLE like '=>'%'.@$this->CARI['role'].'%'
                                 );

		// load and packing data
		$this->items		= $this->mglobal->get_data_all('T_USER_ROLE',NULL,$filter,'*',NULL,['ROLE','ASC'], $this->offset , $this->limit);
        // echo $this->db->last_query();
		$this->total_rows   = $this->mglobal->count_data_all('T_USER_ROLE',NULL,$filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => @$this->CARI['role'],
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
									'Administrator'	 => 'index.php/dashboard/administrator',
									'Role'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);		
	}	

    function addrole(){
        $this->load->model('mrole');
        $data = array(
            'form' => 'add',
        );

        $this->load->view('role_form', $data);
    }

    function editrole($id){
        $this->load->model('mrole');
        $data = array(
            'form' => 'edit',
            'item' => $this->mrole->get_by_id($id)->row(),
        );

        $this->load->view('role_form', $data);
    }


    /** Process Insert, Update, Delete, update Setting 
     * 
     * @return boolean process data role
     */
    function saverole(){    
        $this->db->trans_begin();
        $this->load->model('mmenu', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('makses', TRUE);
		
		$IS_ACTIVE = $this->input->post('IS_ACTIVE');if($IS_ACTIVE != 1){ $IS_ACTIVE=0; }
		$IS_PN = $this->input->post('IS_PN');if($IS_PN != 1){ $IS_PN=0; }
		$IS_INSTANSI= $this->input->post('IS_INSTANSI');if($IS_INSTANSI != 1){ $IS_INSTANSI=0; }
		$IS_USER_INSTANSI =$this->input->post('IS_USER_INSTANSI');if($IS_USER_INSTANSI != 1){ $IS_USER_INSTANSI=0; }
		$IS_KPK=$this->input->post('IS_KPK');if($IS_KPK != 1){ $IS_KPK=0; }
		
        if($this->input->post('act') == 'doinsert'){
            $role = array(
                'ROLE'        => $this->input->post('ROLE'),
                'IS_ACTIVE'   => $IS_ACTIVE,
                'DESCRIPTION' => $this->input->post('DESCRIPTION'),
				'IS_PN' => $IS_PN,
                'IS_INSTANSI' => $IS_INSTANSI,
                'IS_USER_INSTANSI' => $IS_USER_INSTANSI,
                'IS_KPK' => $IS_KPK,
				'COLOR' => $this->input->post('color'),
            );
            if($_POST['IDENTIFIER']){
                foreach ($_POST['IDENTIFIER'] as $IDENTIFIER => $val) {
                    $role = array_merge($role, array($val=>1));
                }
            }

            $this->db->insert('T_USER_ROLE', $role);
			ng::logActivity('Tambah Role '.$this->input->post('ROLE').', id = '.$this->db->insert_id());
        }
		else if($this->input->post('act') == 'doupdate'){
            $id    = $this->input->post('ID_ROLE', TRUE);
            $is    = $this->mrole->get_by_id($id)->row();
            $role = array(
                'ROLE'        => $this->input->post('ROLE'),
                'IS_ACTIVE'   => $IS_ACTIVE,
                'DESCRIPTION' => $this->input->post('DESCRIPTION'),
				'IS_PN' => $IS_PN,
                'IS_INSTANSI' => $IS_INSTANSI,
                'IS_USER_INSTANSI' => $IS_USER_INSTANSI,
                'IS_KPK' => $IS_KPK,
				'COLOR' => $this->input->post('color'),
            );
            $this->db->where('ID_ROLE', $id);
            $this->db->update('T_USER_ROLE', $role);
			ng::logActivity('Edit Role, id = '.$id);
        }
		else if($this->input->post('act') == 'doupdaterolepermission'){
            $id    = $this->input->post('ID_ROLE', TRUE);
            $read   = $this->input->post('PERMISSION_READ');
            $write  = $this->input->post('PERMISSION_WRITE');
            $permission = array();
            $user_akses = [];
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
                    $user_akses[$id_menu] = [
                        'ID_ROLE' => $id,
                        'ID_MENU' => $id_menu,
                        'IS_READ'       => 1,
                        'IS_WRITE'      => 0
                    ];
                }
            }
            for ( $i=0; $i<count($write); $i++ ) {
                $id_menu    = $write[$i];
                if ( array_key_exists($id_menu, $permission) ) {
                    $permission[$id_menu]['IS_WRITE'] = 1;
                }
                if ( array_key_exists($id_menu, $user_akses) ) {
                    $user_akses[$id_menu]['IS_WRITE'] = 1;
                }
            }

            $role = array(
                'PERMISSION'    => json_encode($permission),
                'UPDATED_TIME'  => time(),
                'UPDATED_BY'    => $this->session->userdata('USR'),
                'UPDATED_IP'    => $_SERVER["REMOTE_ADDR"],
            );
            //update ke t_user_akses
            $this->makses->create_or_update($id, $user_akses);
            //update ke table role
            $this->mrole->update($this->input->post('ID_ROLE', TRUE), $role);
			ng::logActivity('Edit Permission untuk Role '.$this->input->post('ID_ROLE', TRUE));
        }

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
    function role_permission($id_role = 0){
        // load model
        $this->load->model('mmenu');
        $this->load->model('mrole');
        $this->load->model('makses');
        $role = $this->mrole->get_nama_role($id_role);
        $get_permission = $this->makses->get_permission_by_role($id_role);
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb',
                            array(
                                'Dashboard'  => 'index.php/welcome/dashboard',
                                'Administrator'	 => 'index.php/dashboard/administrator',
                                'Edit Hak Akses'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                            )),

            'form'      => 'edit',
            'menu'      => $this->mmenu->get_edit_menu(0, $get_permission),
            'id_role'   => $id_role,
            'role'      => $role
        );

        // load view
        $this->load->view(strtolower(__FUNCTION__), $data);
    }
}