<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
	
/** 
 * Controller Mail
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {
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
		$this->load->model('mmail', '', TRUE);

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
			);			
		}

		// load and packing data
		$this->items		= $this->mmail->get_paged_list($this->limit, $this->offset, $filter)->result();
		$this->total_rows   = $this->mmail->count_all($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
									'Mail'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	/** Process Insert, Update, Delete Mail
     * 
     * @return boolean process Mail
     */
    function savemail(){
        $this->db->trans_begin();
        $this->load->model('mmail', '', TRUE);
     
        if($this->input->post('act', TRUE)=='doinsert'){
            $mail = array(
                'ID_MAIL' 		=> $this->input->post('ID_MAIL', TRUE),
				'NOMOR' 		=> $this->input->post('NOMOR', TRUE),
				'TANGGAL' 		=> $this->input->post('TANGGAL', TRUE),
				'DARI' 		=> $this->input->post('DARI', TRUE),
				'KEPADA' 		=> $this->input->post('KEPADA', TRUE),
				'PERIHAL' 		=> $this->input->post('PERIHAL', TRUE),
				'ATTACHMENT' 		=> $this->input->post('ATTACHMENT', TRUE),
				'NEED_RESPONSE' 		=> $this->input->post('NEED_RESPONSE', TRUE),
				'RESPONSE_STATUS' 		=> $this->input->post('RESPONSE_STATUS', TRUE),
				'MAIL_STATUS' 		=> $this->input->post('MAIL_STATUS', TRUE),
                'CREATED_TIME'     => time(),
                'CREATED_BY'     => $this->session->userdata('USR'),
                'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $this->mmail->save($mail);
        }else if($this->input->post('act', TRUE)=='doupdate'){
            $mail = array(
                'ID_MAIL' 		=> $this->input->post('ID_MAIL', TRUE),
				'NOMOR' 		=> $this->input->post('NOMOR', TRUE),
				'TANGGAL' 		=> $this->input->post('TANGGAL', TRUE),
				'DARI' 		=> $this->input->post('DARI', TRUE),
				'KEPADA' 		=> $this->input->post('KEPADA', TRUE),
				'PERIHAL' 		=> $this->input->post('PERIHAL', TRUE),
				'ATTACHMENT' 		=> $this->input->post('ATTACHMENT', TRUE),
				'NEED_RESPONSE' 		=> $this->input->post('NEED_RESPONSE', TRUE),
				'RESPONSE_STATUS' 		=> $this->input->post('RESPONSE_STATUS', TRUE),
				'MAIL_STATUS' 		=> $this->input->post('MAIL_STATUS', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            $mail['ID_MAIL']    = $this->input->post('ID_MAIL', TRUE);
            $this->mmail->update($mail['ID_MAIL'], $mail);
        }else if($this->input->post('act', TRUE)=='dodelete'){
            $mail['ID_MAIL']    = $this->input->post('ID_MAIL', TRUE);
            $this->mmail->delete($mail['ID_MAIL']);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
    
    /** Form Tambah Mail
     * 
     * @return html form tambah Mail
     */
    function addmail(){
        $this->load->model('mmail', '', TRUE);
        $data = array(
            'form'      => 'add',
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit Mail
     * 
     * @return html form edit Mail
     */
    function editmail($id){
        $this->load->model('mmail', '', TRUE);
        $data = array(
            'form'      => 'edit',
            'item'      => $this->mmail->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus Mail
     * 
     * @return html form konfirmasi hapus Mail
     */
    function deletemail($id){
        $this->load->model('mmail', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->mmail->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail Mail
     * 
     * @return html detail Mail
     */    
    function detailmail($id){
        $this->load->model('mmail', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->mmail->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }
}
