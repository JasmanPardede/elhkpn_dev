<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Devissue
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devissue extends CI_Controller {
	// num of records per page
	public $limit = 10;
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
    }

    /** Devissue List
     * 
     * @return html Devissue List
     */
	public function index($offset = 0)
	{
		// load model
		$this->load->model('mdevissue', '', TRUE);

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
		$this->items		= $this->mdevissue->get_paged_list($this->limit, $this->offset, $filter)->result();
		$this->total_rows   = $this->mdevissue->count_all($filter);

		$data = array(
			'items'         => $this->items,
			'total_rows'    => $this->total_rows,
			'offset'        => $this->offset,
			'cari'          => $cari,
			'breadcrumb'	=> call_user_func('ng::genBreadcrumb', 
								array(
									'Dashboard'	 => 'index.php/welcome/dashboard',
                                    'Administrator'  => 'index.php/dashboard/administrator',
									'Development Issue'   => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
								)),
			'pagination'	=> call_user_func('ng::genPagination'),
		);

		// load view
		$this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	/** Process Insert, Update, Delete Development Issue
     * 
     * @return boolean process 
     */
    function savedevissue(){
        $this->db->trans_begin();
        // $this->security->csrf_verify();

        $this->load->model('mdevissue', '', TRUE);
     
        if($this->input->post('act', TRUE)=='doinsert'){
            
            // $CI =& get_instance();
            $FILE = 'file';
            $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx");
            $temp = explode(".", $_FILES[$FILE]["name"]);
            $extension = end($temp);

            $newName = ''.$_FILES[$FILE]["name"];

            $type = array(
                "image/gif",
                "image/jpeg",
                "image/jpg",
                "image/pjpeg",
                "image/x-png",
                "image/png",
                "application/msword",
                "application/doc",
                "application/txt",
                "application/pdf",
                "text/pdf",
            );

            $maxsize = 200000000;

            if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
                if ($_FILES[$FILE]["error"] > 0) {
                    // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
                } else {
                    if (file_exists("upload/" . $newName)) {
                        // echo $_FILES[$FILE]["name"] . " already exists. ";
                    } else {
                        // chmod('upload/', 0777);
                        move_uploaded_file($_FILES[$FILE]["tmp_name"], "./upload/" . $newName);
                    }
                }
            } else {
                // echo "Invalid file";
            }             
            $devissue = array(
                'ID_MENU'     => $this->input->post('ID_MENU', TRUE),
                'TAGS'     => $this->input->post('TAGS', TRUE),
                'TITLE'     => $this->input->post('TITLE', TRUE),
                'DESCRIPTION'     => $this->input->post('DESCRIPTION', TRUE),
                'PHOTO'     		=> $_FILES[$FILE]["name"],
                'RESOLUTION'     => $this->input->post('RESOLUTION', TRUE),
                'IS_DONE'     => $this->input->post('IS_DONE', TRUE),
                'DONE_TIME'     => date('Y-m-d H:i:s',strtotime($this->input->post('DONE_TIME', TRUE))),
                'CREATED_TIME'     => time(),
                'CREATED_BY'     => $this->session->userdata('USR'),
                'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                 
            );
            $this->mdevissue->save($devissue);
            
            
        }else if($this->input->post('act', TRUE)=='doupdate'){
            
            // $CI =& get_instance();
            $FILE = 'file';
            $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx");
            $temp = explode(".", $_FILES[$FILE]["name"]);
            $extension = end($temp);

            $newName = ''.$_FILES[$FILE]["name"];

            $type = array(
                "image/gif",
                "image/jpeg",
                "image/jpg",
                "image/pjpeg",
                "image/x-png",
                "image/png",
                "application/msword",
                "application/doc",
                "application/txt",
                "application/pdf",
                "text/pdf",
            );

            $maxsize = 200000000;

            if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
                if ($_FILES[$FILE]["error"] > 0) {
                    // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
                } else {
                    if (file_exists("upload/" . $newName)) {
                        // echo $_FILES[$FILE]["name"] . " already exists. ";
                    } else {
                        // chmod('upload/', 0777);
                        move_uploaded_file($_FILES[$FILE]["tmp_name"], "./upload/" . $newName);
                    }
                }
            } else {
                // echo "Invalid file";
            } 


            if($newName!=''){
                $newName = $_FILES[$FILE]["name"];
            }else{
                $newName = $this->input->post('PHOTO', TRUE); 
            }

            $devissue = array(
                'ID_MENU'     => $this->input->post('ID_MENU', TRUE),
                'TAGS'     => $this->input->post('TAGS', TRUE),
                'TITLE'     => $this->input->post('TITLE', TRUE),
                'DESCRIPTION'     => $this->input->post('DESCRIPTION', TRUE),
                'PHOTO'     		=> $newName,
                'RESOLUTION'     => $this->input->post('RESOLUTION', TRUE),
                'IS_DONE'     => $this->input->post('IS_DONE', TRUE),
                'DONE_TIME'     => date('Y-m-d H:i:s',strtotime($this->input->post('DONE_TIME', TRUE))),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],
            );
            $devissue['ID_ISSUE']    = $this->input->post('ID_ISSUE', TRUE);
            $this->mdevissue->update($devissue['ID_ISSUE'], $devissue);
            
                        
        }else if($this->input->post('act', TRUE)=='dodelete'){
            
            $devissue['ID_ISSUE']    = $this->input->post('ID_ISSUE', TRUE);
            $this->mdevissue->delete($devissue['ID_ISSUE']);
            
                      
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());       
    }
    
    /** Form Tambah Development Issue
     * 
     * @return html form tambah Development Issue
     */
    function adddevissue(){
        $this->load->model('mdevissue', '', TRUE);
        $data = array(
            'form'      => 'add',
            'parents'   => $this->mdevissue->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit Development Issue
     * 
     * @return html form edit Development Issue
     */
    function editdevissue($id){
        $this->load->model('mdevissue', '', TRUE);
        $data = array(
            'form'      => 'edit',
            'item'      => $this->mdevissue->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus Development Issue
     * 
     * @return html form konfirmasi hapus Development Issue
     */
    function deletedevissue($id){
        $this->load->model('mdevissue', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->mdevissue->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail Development Issue
     * 
     * @return html detail Development Issue
     */
    function detaildevissue($id){
        $this->load->model('mdevissue', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->mdevissue->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }
}
