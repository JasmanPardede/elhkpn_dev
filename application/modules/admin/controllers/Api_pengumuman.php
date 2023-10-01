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

class Api_pengumuman extends MY_Controller {

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->instansi = $this->session->userdata('INST_SATKERKD');
        $this->load->model('mrole', '', TRUE);
        $this->load->model('minstansi', '', TRUE);
        $this->load->model('Mglobal', '', TRUE);
        $this->load->model('Mapi_pengumuman', '', TRUE);
    }

    public function index(){
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Peran Serta Masyarakat' => 'index.php/psm/telaah',
                'Telaah Pengaduan' => 'index.php/psm/telaah',
            )),
        );    
        $this->load->view('api_pengumuman_index', $data);
    }

    function load_data(){
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
        $response = $this->Mapi_pengumuman->list_all($currentpage, $keyword, $rowperpage,true);
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
        $this->load->view('api_pengumuman_form', $data);
    }

    function save_api(){
        $action = $this->input->post('act', TRUE);

        if(isset($_FILES['logo'])){
            //////////SISTEM KEAMANAN///////////
                $post_nama_file = 'logo'; 
                $extension_diijinkan = array("jpg", "png","jpeg");
                $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
        
                if($check_protect){
                    $method = __METHOD__;
                    $this->mglobal->recordLogAttacker($check_protect,$method);
                    $output = array('status'=>9,'data'=>$result);
                    echo json_encode($output);
                    return;
                }
            //////////SISTEM KEAMANAN///////////
        }
            

        $result = $this->Mapi_pengumuman->save_data($action);   
        echo $result;
        return;
    }

    function approve_form($id) {
        $this->makses->check_is_write();
        $data = array(
            'form' => 'approve',
            'item' => $this->Mglobal->get_data_by_id('api_pengumuman','ID',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view('api_pengumuman_form', $data);
    }
    function logo_form($id) {
        $this->makses->check_is_write();
        $data = array(
            'form' => 'logo',
            'item' => $this->Mglobal->get_data_by_id('api_pengumuman','ID',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view('api_pengumuman_form', $data);
    }
    function email_form($id) {
        $this->makses->check_is_write();
        $data = array(
            'form' => 'email',
            'item' => $this->Mglobal->get_data_by_id('api_pengumuman','ID',$id,false,true),
            'roles' => $this->mrole->list_all()->result(),
            'instansis' => $this->minstansi->list_all()->result(),
        );
        $this->load->view('api_pengumuman_form', $data);
    }
    function email_send(){
        $action = $this->input->post('act', TRUE);

        $email_penerima = $this->input->post('email', TRUE);
        $id_satker = $this->input->post('INST_SATKERKD', TRUE);
        $instansi = $this->Mglobal->get_data_by_id('m_inst_satker','INST_SATKERKD',$id_satker,false,true);
    
        $ID = $this->input->post('ID', TRUE);
        $get_api = $this->Mglobal->get_data_by_id('api_pengumuman','ID',$ID,false,true);

        $result = $this->Mapi_pengumuman->save_data($action); 
        if($result){     
            $result_email = $this->api_email_send($instansi->INST_NAMA,$email_penerima,$get_api);
            echo $result_email;
        }else{
            echo 0;
        }
        return;
    }


    function api_email_send($nama_instansi,$email_penerima,$get_api){
        ///////////////////////////////////////////////KIRIM EMAIL////////////////////////////////////////////
                        $pesan_valid = '
                        <html>
                            <head>
                                <style>
                                    table {
                                        border-collapse: collapse;
                                        width: 100%;
                                    }
                                    th, td {
                                        padding: 1px;
                                        vertical-align: text-top;
                                    }
                                    th {
                                        background-color: #4CAF50;
                                        color: white;
                                    }

                                </style>
                            </head>
                            <body>
                                <div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div>Yth. ' . $nama_instansi . '</div>
                                        </div>
                                    </div>

                                </div>
                                <div>di Tempat </div><br>
                                <div>Dasar hukum : </div>
                                <div style="padding-left:1em">
                                    <table>
                                        <tr>
                                            <td>1.</td>
                                            <td>Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggaraan Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme;</td>
                                        </td>
                                        <tr>
                                            <td>2.</td>
                                            <td>Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi sebagaimana telah dua kali diubah dengan perubahan terakhir Undang-Undang Nomor 19 Tahun 2019 tentang Perubahan Kedua Atas Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi.;</td>
                                        </td>
                                        <tr>
                                            <td>3.</td>
                                            <td>Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara sebagaimana diubah dengan Peraturan Komisi Pemberantasan Korupsi Nomor 02 Tahun 2020 tentang Perubahan atas Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara.</td>
                                        </td>
                                    </table>
                                </div>
                                <br>
                                <div style="text-align:justify">
                                    Berdasarkan ketentuan di atas, setiap Wajib LHKPN berkewajiban untuk melaporkan dan mengumumkan harta kekayaannya sebelum dan setelah menjabat. Pengumuman sebagaimana dimaksud dilakukan dengan menggunakan format yang ditetapkan oleh KPK melalui media elektronik maupun non elektronik. Dalam rangka memudahkan teknis pengumuman tersebut di website Instansi, berikut kami lampirkan Link I-Frame e-Announcement LHKPN dan Standard Button Link e-Announcement LHKPN untuk dipergunakan sesuai peruntukannya:
                                </div>
                                <br>
                                <div>
                                    '.$get_api->API.'
                                </div>
                                <br>
                                <div>
                                    Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau call center 198.
                                </div>
                                <div>
                                <br/>
                                <div>Atas kerjasama yang diberikan, Kami ucapkan terima kasih</div>
                                <br/>
                                <div>  Direktorat Pendaftaran dan Pemeriksaan LHKPN</div>
                                <div>  --------------------------------------------------------------</div>
                                <div> Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div>
                                <div> &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id | elhkpn.kpk.go.id | Layanan LHKPN 198</div>
                            </div>
                        ';
                        
                        $penerima = $email_penerima;
                        $subject = "I-Frame dan button link e-Announcement LHKPN";
                        $attach = 'portal-assets/img/lampiran_email_iframe.jpeg';
                        $result = ng::mail_send($penerima,$subject, $pesan_valid,null,$attach);
                        if($result){
                            return 1;
                        }else{
                            return 0;
                        }
    }

}
