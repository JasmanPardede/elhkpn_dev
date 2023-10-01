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

class Telaah extends MY_Controller {

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
        $this->load->model('Mlhkpn', '', TRUE);
    }

    public function index(){
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Peran Serta Masyarakat' => 'index.php/psm/telaah',
                'Telaah Pengaduan' => 'index.php/psm/telaah',
            )),
        );    
        $this->load->view(strtolower(__CLASS__) . '/telaah_index', $data);
    }

    function load_data(){
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mlhkpn->load_data_pelaporan($currentpage, $keyword, $rowperpage,true);
        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );
        $this->to_json($dtable_output);
    }

    function detail_form($id) {
        $id = encrypt_username($id,'d');
        if(!$id){
           redirect('/Errcontroller');
        }
        $get_pelaporan = $this->mglobal->get_by_id('t_lhkpn_pelaporan','ID_PELAPORAN',$id);
        $get_dokumen = $this->mglobal->get_data_all('t_lhkpn_pelaporan_dokumen',null,['ID_PELAPORAN'=>$get_pelaporan->ID_PELAPORAN]);
        $get_lhkpn = $this->mglobal->get_detail_pn_lhkpn($get_pelaporan->ID_LHKPN,true,true);

        $file_uploads = $get_dokumen[0]->FILE;
        $file_arr = json_decode($file_uploads);

        $link = [];

        foreach($file_arr as $key => $file){
            $no = $key+1;
            $explode_string = explode("/", $file);
            
            $file_name = end($explode_string);
            $dir = prev($explode_string);
            $path = 'uploads/pelaporan_lhkpn/'.$dir.'/';

            $secretName = base64_encode($file_name);
            $secretId = base64_encode($get_dokumen[0]->ID);
            $ext = strtolower(end(explode('.',$file_name)));

            $classIcon = 'fa-file';

             //get data from minio
            $checkFile = linkFromMinio($path.$file_name,null,'t_lhkpn_pelaporan_dokumen','ID',$get_dokumen[0]->ID);

            if($checkFile){
                $link = $checkFile;

                $link_html[] = " <a id='".$secretId."' ext='".$ext."' href='".$link."' target=_blank secretname='".$secretName."' class='files edit-action-document' title='file'><i class='fa ".$classIcon." '> file$no &nbsp;&nbsp;</i></a> ";

            }else{ 
                if (file_exists($path.$file_name) && $file_name!="") {
                    $file_name = $path.$file_name;
                    $link = base_url() .$file_name;
                    
                    $link_html[] = " <a id='".$secretId."' ext='".$ext."' href='".$link."' target=_blank secretname='".$secretName." class='files edit-action-document' title='file'><i class='fa ".$classIcon." '> file$no &nbsp;&nbsp;</i></a> ";
                }
            }

        }

        $data = array(
            'get_pelaporan' => $get_pelaporan,
            'get_dokumen' => $get_dokumen,
            'get_lhkpn' => $get_lhkpn,
            'get_link' => $link_html
        );

        $this->load->view(strtolower(__CLASS__) . '/form_detail', $data);
    }

    function save_telaah() {
        $action = $this->input->post('act', TRUE);
        $result = $this->Mlhkpn->save_data_telaah($action);   
        echo $result;
        return;
    }

    ////// analisa harta kas ///////
    public function analisa_kas(){
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Peran Serta Masyarakat' => 'index.php/psm/telaah',
                'Telaah Pengaduan' => 'index.php/psm/telaah',
            )),
        ); 
        $this->load->view(strtolower(__CLASS__) . '/analisa_kas_page', $data);
    }

    function load_data_kas(){
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mlhkpn->load_data_analisa_kas($currentpage, $keyword, $rowperpage,true);
        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );
        $this->to_json($dtable_output);
    }
    ///////  ---  end analisa harta kas --- ////////////

}
