<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Instansi
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Instansi
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instansi extends CI_Controller {
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
        $this->load->model('minstansi', '', TRUE);

        // prepare paging
        $this->base_url  = site_url('admin/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__).'/');
        $this->uri_segment  = 4;
        $this->offset      = $this->uri->segment($this->uri_segment);
        
        // filter
        $cari             = '';
        $filter          = '';
        if($_POST && $this->input->post('cari', TRUE)!=''){
            $cari = $this->input->post('cari', TRUE);
            $filter = array(
                'INST_NAMA' => $this->input->post('cari', TRUE),
            );          
        }

        // load and packing data
        $this->items        = $this->minstansi->get_paged_list($this->limit, $this->offset, $filter)->result();
        $this->total_rows   = $this->minstansi->count_all($filter);

        $data = array(
            'items'         => $this->items,
            'total_rows'    => $this->total_rows,
            'offset'        => $this->offset,
            'cari'          => $cari,
            'breadcrumb'    => call_user_func('ng::genBreadcrumb', 
                                array(
                                    'Dashboard'  => 'index.php/welcome/dashboard',
                                    'Administrator'  => 'index.php/dashboard/administrator',
                                    'Instansi'  => 'index.php/'.strtolower(__CLASS__).'/'.strtolower(__FUNCTION__),
                                )),
            'pagination'    => call_user_func('ng::genPagination'),
        );

        // load view
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }

    /** Process Insert, Update, Delete Instansi
     * 
     * @return boolean process Instansi
     */
    function saveinstansi(){
        $this->db->trans_begin();
        $this->load->model('minstansi', '', TRUE);
     
        if($this->input->post('act', TRUE)=='doinsert'){
            $instansi = array(
                'INST_SATKERKD'         => $this->input->post('INST_SATKERKD', TRUE),
                'INST_NAMA'         => $this->input->post('INST_NAMA', TRUE),
                'INST_AKRONIM'      => $this->input->post('INST_AKRONIM', TRUE),
                'INST_LEVEL'        => $this->input->post('INST_LEVEL', TRUE),
                'IS_ACTIVE'        => 1,
                'CREATED_TIME'     => time(),
                'CREATED_BY'     => $this->session->userdata('USR'),
                'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                // 'UPDATED_TIME'     => time(),
                // 'UPDATED_BY'     => $this->session->userdata('USR'),
                // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $this->minstansi->save($instansi);
        }else if($this->input->post('act', TRUE)=='doupdate'){
            $instansi = array(
                'INST_SATKERKD'         => $this->input->post('INST_SATKERKD', TRUE),
                'INST_NAMA'         => $this->input->post('INST_NAMA', TRUE),
                'INST_AKRONIM'      => $this->input->post('INST_AKRONIM', TRUE),
                'INST_LEVEL'        => $this->input->post('INST_LEVEL', TRUE),
                // 'IS_ACTIVE'        => $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            $instansi['INST_SATKERKD']    = $this->input->post('INST_SATKERKD', TRUE);
            $this->minstansi->update($instansi['INST_SATKERKD'], $instansi);
        }else if($this->input->post('act', TRUE)=='dodelete'){
            $instansi = array(
                'IS_ACTIVE'        => -1,
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME'     => time(),
                'UPDATED_BY'     => $this->session->userdata('USR'),
                'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
            );
            $instansi['INST_SATKERKD']    = $this->input->post('INST_SATKERKD', TRUE);
            $this->minstansi->update($instansi['INST_SATKERKD'], $instansi);            
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }
    
    /** Form Tambah Instansi
     * 
     * @return html form tambah Instansi
     */
    function addinstansi(){
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'      => 'add',
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Edit Instansi
     * 
     * @return html form edit Instansi
     */
    function editinstansi($id){
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'      => 'edit',
            'item'      => $this->minstansi->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Form Konfirmasi Hapus Instansi
     * 
     * @return html form konfirmasi hapus Instansi
     */
    function deleteinstansi($id){
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'  => 'delete',
            'item'  => $this->minstansi->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }

    /** Detail Instansi
     * 
     * @return html detail Instansi
     */    
    function detailinstansi($id){
        $this->load->model('minstansi', '', TRUE);
        $data = array(
            'form'  => 'detail',
            'item'  => $this->minstansi->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__).'_form', $data);
    }
}
