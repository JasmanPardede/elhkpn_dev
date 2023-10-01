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
 * @author Rizky Awlia Fajrin (Evan Sumangkut) - PT.Waditra Reka Cipta
 * @package Controllers
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reg_sos extends MY_Controller {

    const ENABLE_LOG_MESSAGE = FALSE;

    // num of records per page
    public $limit = 200;
    private $limit_write_records = 5;

    /**
     * max execute all
     * min execute just 1
     * @var type
     */
    private $__execution_time = 'max';

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('mglobal');
        $this->instansi = $this->session->userdata('INST_SATKERKD');
        $this->uk_id = $this->session->userdata('UK_ID');
        $this->role = $this->session->userdata('ID_ROLE');
        $this->load->model('muser', '', TRUE);
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('Mglobal', '', TRUE);
        $this->load->model('Mmpnwn', '', TRUE);
    }

   ///////////////////////////////REGULASI////////////////////////////////
    public function index($offset = 0){ 
        $cari = '';
        $filter = array();
        $instansi = '';
        $data = array(
            'offset' => $this->offset,
            'cari' => $cari,
            'instansis' => $this->instansis,
            'instansi' => $instansi,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/dashboard/eregistration',
                'Regulasi/Sosialisasi' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
        );
        $this->load->view(strtolower(__CLASS__) . '/regulasi', $data);
    }
    
    function load_data_regulasi(){
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mmpnwn->load_data_regulasi($currentpage, $keyword, $rowperpage,true);

        if($response){
            $dir = null;
           
            foreach($response->result as $list){
                $link_html = '';
                if($list->FILE_REGULASI != null){
                    $file_regulasi = json_decode($list->FILE_REGULASI);

                    foreach($file_regulasi as $key => $tmp_name){
                        if($key == 0){
                            $dt = explode(",", $tmp_name);

                            foreach($dt as $v){
                                $dir = explode(".", $v);
                            } 
                            
                            $dir = $dir[1]; 
                           
                            $path = 'uploads/data_regulasi/'.$dir.'/';
                        }         

                        //parsing data to modal for detail
                        $secretName = base64_encode($tmp_name);
                        $secretId = base64_encode($list->ID_REGULASI);
                        $ext = strtolower(end(explode('.',$tmp_name)));

                        $classIcon = 'fa-file-pdf-o';

                        //get data from minio
                        $checkFile = linkFromMinio($path.$tmp_name,null,'t_lhkpn_regulasi','ID_REGULASI',$list->ID_REGULASI);

                        if($checkFile){ 
                            $link = $checkFile;

                            $link_html .= " <a id='".$secretId."' ext='".$ext."' href='".$link."' target=_blank secretname='".$secretName."' class='files edit-action-document' title='pdf'><i class='fa ".$classIcon." fa-2x'></i></a> ";
                        }else{ 
                          if (file_exists($path.$tmp_name) && $tmp_name!="") {
                                $tmp_name = $path.$tmp_name;
                                
                                $link = base_url() .$tmp_name;

                                $link_html .= " <a id='".$secretId."' ext='".$ext."' href='".$link."' target=_blank secretname='".$secretName." class='files edit-action-document' title='pdf'><i class='fa ".$classIcon." fa-2x'></i></a> ";
                            }
                        }  
                    }
                }
                $list->btn_file_regulasi = $link_html;
            } 
        } 

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );
        $this->to_json($dtable_output);
    }
    function add_form() {
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'add',
            'list_instansi' => $list_instansi,
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add', $data);
    }
    function edit_form($id) {
        $list_instansi = $this->minstansi->list_all()->result();
        $dataState = $this->Mglobal->get_data_by_id('t_lhkpn_regulasi','ID_REGULASI',$id,false,true);
        $uk_name = $this->Mglobal->get_data_by_id('m_unit_kerja','UK_ID',$dataState->UK_ID,false,true);
        $data = array(
            'form' => 'edit',
            'item' => $dataState,
            'list_instansi' => $list_instansi,
            'roles' => $this->mrole->list_all()->result(),
            'uk_nama' => $uk_name->UK_NAMA,
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add', $data);
    }
    function delete_form($id) {
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'delete',
            'item' => $this->Mglobal->get_data_by_id('t_lhkpn_regulasi','ID_REGULASI',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add', $data);
    }
    function approve_form($id) {
        $this->makses->check_is_write();
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'approve',
            'item' => $this->Mglobal->get_data_by_id('t_lhkpn_regulasi','ID_REGULASI',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add', $data);
    }
    function save_regulasi() {   
        $action = $this->input->post('act', TRUE);

        $result = $this->Mmpnwn->save_data_regulasi($action);

         //////////SISTEM KEAMANAN///////////
         $post_nama_file = 'file_regulasi';
         $extension_diijinkan = array("pdf");
         $check_protect = protectionMultipleDocument($post_nama_file,$extension_diijinkan);
         if($check_protect){
             $method = __METHOD__;
             $this->load->model('mglobal');
             $this->mglobal->recordLogAttacker($check_protect,$method);
             echo 'INGAT DOSA WAHAI PARA HACKER';
             return;
         }   

         //////////SISTEM KEAMANAN///////////

        $file_regulasi = $_FILES['fileRegulasi'];
        $cek_file = $file_regulasi['name'][0];

        if($cek_file != ''){
            if($action == "doinsert"){ 
                $dt_regulasi = $this->Mmpnwn->get_last_regulasi(); //ambil data regulasi terakhir
                $id_regulasi = $dt_regulasi->ID_REGULASI;

                $this->checkDir($id_regulasi);
            }else if($action == "doupdate"){ 

                $id_regulasi = $this->input->post('ID_REGULASI');
//                removeDirectory('uploads/data_regulasi/'.$id_regulasi);
                $this->checkDir($id_regulasi); 
            }

            $dt_item = $this->uploadFileRegulasi($file_regulasi['tmp_name'], $id_regulasi);
            $dt_upload = json_encode($dt_item);

            $result = $this->Mmpnwn->save_data_regulasi("doupdate", $id_regulasi, $dt_upload);
        }

        echo $result;
        return;
    }

    function uploadFileRegulasi($data = [], $id_regulasi){
        $dt_item = [];
        $maxsize = 10000000; //10Mb

        foreach($data as $key => $tmp_name){
            $time = time();
            $ext = end((explode(".", $_FILES['fileRegulasi']['name'][$key])));
            $ext = strtolower($ext);//extensi dirubah ke huruf kecil

            $file_name = $key . $time . '.' . $id_regulasi.'.'.$ext;

            $uploaddir = 'uploads/data_regulasi/'.$id_regulasi. '/' . $file_name;
            $uploadext = '.' . $ext; 

            $dt_item[] = $file_name;

            $storage_minio =  storageDiskMinio();

            if ($uploadext == '.pdf' && $_FILES['fileRegulasi']['size'][$key] <= $maxsize) {
                if(switchMinio()){
                    //upload to minio
                    $uploadDirMinio = 'uploads/data_regulasi/'.$id_regulasi.'/';
                    $resultMinio = uploadMultipleToMinio($_FILES['fileRegulasi']['tmp_name'][$key],$_FILES['fileRegulasi']['type'][$key],$file_name,$uploadDirMinio,$storage_minio);
                    $rst = false;
                    if($resultMinio){
                      $rst = true;
                    }
                }else{
                    //upload to local
                    $rst = (move_uploaded_file($_FILES['fileRegulasi']['tmp_name'][$key], $uploaddir));
                }
            }
        }

        return $dt_item;
    }

    function checkDir($id_regulasi=null){
        if (!file_exists('uploads/data_regulasi/' . $id_regulasi)) {
            mkdir('uploads/data_regulasi/' . $id_regulasi);
            /* --- IBO ADD -- */
        }  

    }


    ///////////////////////////////SOSIALISASI////////////////////////////////
    public function sosialisasi($offset = 0){
        
        $cari = '';
        $filter = array();
        $instansi = '';
        $data = array(
            'offset' => $this->offset,
            'cari' => $cari,
            'instansis' => $this->instansis,
            'instansi' => $instansi,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'E Registration' => 'index.php/dashboard/eregistration',
                'Regulasi/Sosialisasi' => 'index.php/' . strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__),
            )),
        );
        $this->load->view(strtolower(__CLASS__) . '/sosialisasi', $data);
    }
    function load_data_sosialisasi(){
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mmpnwn->load_data_sosialisasi($currentpage, $keyword, $rowperpage,true);
        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );
        $this->to_json($dtable_output);
    }
    function add_form_sosialisasi() {
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'add',
            'list_instansi' => $list_instansi,
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add_sosialisasi', $data);
    }
    function edit_form_sosialisasi($id) {
        $list_instansi = $this->minstansi->list_all()->result();
        $dataState = $this->Mglobal->get_data_by_id('t_lhkpn_sosialisasi','ID_SOSIALISASI',$id,false,true);
        $uk_name = $this->Mglobal->get_data_by_id('m_unit_kerja','UK_ID',$dataState->UK_ID,false,true);
        $data = array(
            'form' => 'edit',
            'item' => $dataState,
            'list_instansi' => $list_instansi,
            'roles' => $this->mrole->list_all()->result(),
            'uk_nama' => $uk_name->UK_NAMA,
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add_sosialisasi', $data);
    }
    function delete_form_sosialisasi($id) {
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'delete',
            'item' => $this->Mglobal->get_data_by_id('t_lhkpn_sosialisasi','ID_SOSIALISASI',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add_sosialisasi', $data);
    }
    function approve_form_sosialisasi($id) {
        $this->makses->check_is_write();
        $list_instansi = $this->minstansi->list_all()->result();
        $data = array(
            'form' => 'approve',
            'item' => $this->Mglobal->get_data_by_id('t_lhkpn_sosialisasi','ID_SOSIALISASI',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view(strtolower(__CLASS__) . '/form_add_sosialisasi', $data);
    }
    function save_sosialisasi() {
        $action = $this->input->post('act', TRUE);
        $result = $this->Mmpnwn->save_data_sosialisasi($action);   
        echo $result;
        return;
    }

}
