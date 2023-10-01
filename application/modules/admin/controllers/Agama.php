<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Jabatan
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Jabatan
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agama extends CI_Controller {

    // num of records per page
    public $limit = 10;

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
    }

    /** Jabatan List
     * 
     * @return html Jabatan List
     */
    public function index($offset = 0) {
        // load model
        $this->load->model('magama', '', TRUE);

        // prepare paging
        $this->base_url = site_url('admin/'.strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');
        $this->uri_segment = 4;
        $this->offset = $this->uri->segment($this->uri_segment);

        // filter
        $cari = '';
        $filter = '';
        if ($_POST && $this->input->post('cari', TRUE) != '') {
            $cari = $this->input->post('cari', TRUE);
            $filter = array(
                    'AGAMA'   => $this->input->post('cari', TRUE),
                    // 'ASAL_SURAT'    => $this->input->post('cari', TRUE),
                    // 'PERIHAL'      => $this->input->post('cari', TRUE),
            );
        }

        // load and packing data
        $this->items = $this->magama->get_paged_list($this->limit, $this->offset, $filter)->result();
        $this->total_rows = $this->magama->count_all($filter);

        $data = array(
            'items' => $this->items,
            'total_rows' => $this->total_rows,
            'offset' => $this->offset,
            'cari' => $cari,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Administrator'  => 'index.php/dashboard/administrator',
                'Agama' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
            'pagination' => call_user_func('ng::genPagination'),
        );

        // load view
        $this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    /** Process Insert, Update, Delete Jabatan
     * 
     * @return boolean process Jabatan
     */
    function savejabatan() {
        $this->db->trans_begin();
        $this->load->model('mjabatan', '', TRUE);

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $jabatan = array(
                'ID_JABATAN' => $this->input->post('ID_JABATAN', TRUE),
                'NAMA_JABATAN' => $this->input->post('NAMA_JABATAN', TRUE),
                'IS_ACTIVE' => 1,
                'CREATED_TIME' => time(),
                'CREATED_BY' => $this->session->userdata('USR'),
                'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    // 'UPDATED_TIME'     => time(),
                    // 'UPDATED_BY'     => $this->session->userdata('USR'),
                    // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
            );
            $this->mjabatan->save(jabatan);
        } else if ($this->input->post('act', TRUE) == 'doupdate') {
            $jabatan = array(
                'ID_JABATAN' => $this->input->post('ID_JABATAN', TRUE),
                'NAMA_JABATAN' => $this->input->post('NAMA_JABATAN', TRUE),
                // 'IS_ACTIVE' => $this->input->post('IS_ACTIVE', TRUE),
                // 'CREATED_TIME'     => time(),
                // 'CREATED_BY'     => $this->session->userdata('USR'),
                // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $jabatan['ID_JABATAN'] = $this->input->post('ID_JABATAN', TRUE);
            $this->mjabatan->update($jabatan['ID_JABATAN'], $jabatan);
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $jabatan = array(
                'IS_ACTIVE' => -1,
                'UPDATED_TIME' => time(),
                'UPDATED_BY' => $this->session->userdata('USR'),
                'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
            );
            $jabatan['ID_JABATAN'] = $this->input->post('ID_JABATAN', TRUE);
            $this->mjabatan->update($jabatan['ID_JABATAN'], $jabatan);            
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Form Tambah Jabatan
     * 
     * @return html form tambah Jabatan
     */
    function addjabatan() {
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form' => 'add',
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Edit Jabatan
     * 
     * @return html form edit Jabatan
     */
    function editjabatan($id) {
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form' => 'edit',
            'item' => $this->mjabatan->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Konfirmasi Hapus Jabatan
     * 
     * @return html form konfirmasi hapus Jabatan
     */
    function deletejabatan($id) {
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form' => 'delete',
            'item' => $this->mjabatan->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Detail Jabatan
     * 
     * @return html detail Jabatan
     */
    function detailjabatan($id) {
        $this->load->model('mjabatan', '', TRUE);
        $data = array(
            'form' => 'detail',
            'item' => $this->mjabatan->get_by_id($id)->row(),
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

}
