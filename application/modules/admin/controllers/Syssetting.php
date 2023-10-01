<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller System Setting
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Syssetting
 */
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Syssetting extends Nglibs {
    protected $tts_called = FALSE;
    protected $direkturpp_called = FALSE;
    protected $direkturpencegahan_called = FALSE;
    private $__skip_function_when = [
        "tts_called",
        "direkturpencegahan_called",
        "direkturpp_called",
    ];
	public function __construct()
	{
            parent::__construct();            
            $this->makses->initialize();
            $this->load->model('msyssetting');
        // $this->makses->check_is_read();	
	}
        
        protected function beforeQuery($method) {
            $panggil_function = FALSE;
            $method_called = $this->called_class . "_called";

            if (!in_array($method_called, $this->__skip_function_when)) {
                parent::beforeQuery($method);
            }
        }

        protected function afterQuery($method = FALSE) {
            $panggil_function = FALSE;
            $method_called = $this->called_class . "_called";

            if (!in_array($method_called, $this->__skip_function_when)) {
                parent::afterQuery();
            }
        }

	/** 
     * Setting tts
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
	public function tts($type='',$id='')
	{
            $sql1 = "SELECT ID_SETTING FROM `CORE_SETTING` WHERE OWNER = 'app.lhkpn' AND SETTING = 'tts'";
            $rs = $this->db->query($sql1);
            $id = $rs->row()->ID_SETTING;
            
//            $id = 10;
            $this->tts_called = TRUE;
            $this->data['tbl'] = 'CORE_SETTING';
            $this->data['pk'] = 'ID_SETTING';

            if($type=='lista'){
//                $this->db->select('*');
//                $this->db->from('CORE_SETTING');
//                $this->db->where('1=1', null, false);
//                $this->db->where('OWNER', 'app.lhkpn');
//                $this->db->where('SETTING', 'tts');
//
//                if(@$this->CARI['TEXT']){
//                        $this->db->like('CORE_SETTING.SETTING', $this->CARI['TEXT']);
//                }
//
//                if(@$this->CARI['IS_ACTIVE']==99){
//                        // all
//                }else if(@$this->CARI['IS_ACTIVE']==-1){
//                        // deleted
//                        $this->db->where('IS_ACTIVE', -1);
//                }else if(@$this->CARI['IS_ACTIVE']){
//                        // by status
//                        $this->db->where('IS_ACTIVE', $this->CARI['IS_ACTIVE']);
//                }else{
//                        // active
//                        $this->db->where('IS_ACTIVE', 1);
//                }
//                $this->data['title'] = 'Setting Logo KPK';
//
//                $breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
//                $breadcrumbitem[] = [ucwords(strtolower(get_called_class())) => @$this->segmentTo[2]];
//
//                $breadcrumbdata = [];
//                foreach ($breadcrumbitem as $list) {
//                        $breadcrumbdata = array_merge($breadcrumbdata,$list);
//                }			
//                $this->data['breadcrumb'] 	= call_user_func('ng::genBreadcrumb', $breadcrumbdata);

            }else if($type=='list'){
                $this->data['item'] = $id?(object)$this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0]:'';
                
            }else if($type=='save'){
                if($this->act=='doupdatesetting'){

                    $OWNER	= $this->input->post('OWNER', TRUE);
                    $SETTING	= $this->input->post('SETTING', TRUE);	
                    $sql = "SELECT * FROM `CORE_SETTING` WHERE OWNER = '$OWNER' AND SETTING = '$SETTING'";
                    $rs = $this->db->query($sql);
                    
                    $item = json_decode($rs->row()->VALUE);

                    $target_dir = "images/";
                    $target_file = $target_dir . basename($_FILES["LOGO"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                    // Check if image file is a actual image or fake image
            
                    //////////SISTEM KEAMANAN///////////
                    $post_nama_file = 'LOGO';
                    $extension_diijinkan = array('jpg','png','jpeg');
                    $check_protect = protectionDocument($post_nama_file,$extension_diijinkan);
                    if($check_protect){
                        $method = __METHOD__;
                        $this->load->model('mglobal');
                        $this->mglobal->recordLogAttacker($check_protect,$method);
                        echo 'INGAT DOSA WAHAI PARA HACKER';
                        return;
                    }   
                    //////////SISTEM KEAMANAN///////////

                    if(!empty($_FILES["LOGO"]["tmp_name"])) {
                            $check = getimagesize($_FILES["LOGO"]["tmp_name"]);
                            if($check !== false) {
                                // echo "File is an image - " . $check["mime"] . ".";
                                $uploadOk = 1;
                            } else {
                                // echo "File is not an image.";
                                $uploadOk = 0;
                            }
                    }
                    // Check if file already exists
                    // if (file_exists($target_file)) {
                    //     echo "Sorry, file already exists.";
                    //     $uploadOk = 0;
                    // }
                    // Check file size
                    if ($_FILES["LOGO"]["size"] > 500000) {
                        // echo "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                        $uploadOk = 0;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                            $_POST['LOGO'] = $item->LOGO; // sebelumnya
                        // echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["LOGO"]["tmp_name"], $target_file) == true) {
                            echo "The file ". basename( $_FILES["LOGO"]["name"]). " has been uploaded.";
                            if($_FILES['LOGO']["name"]!=''){
                                    $_POST['LOGO'] = $_FILES['LOGO']["name"];
                            }
                        } else {
                            // echo "Sorry, there was an error uploading your file.";
                        }
                    }



                    $data = array(
                            'OWNER'         	=> $this->input->post('OWNER', TRUE),
                            'SETTING'         	=> $this->input->post('SETTING', TRUE),
                            'VALUE'         	=> json_encode($_POST),
                            'UPDATED_TIME'     	=> time(),
                            'UPDATED_BY'     	=> $this->session->userdata('USR'),
                            'UPDATED_IP'     	=> $this->input->ip_address(), 
                    );
                    $data[$this->data['pk']] = $rs->row()->ID_SETTING;
                    $this->db->where($this->data['pk'], $rs->row()->ID_SETTING);
                    $this->db->update($this->data['tbl'], $data);

                    ng::logActivity('Edit Setting = '.$data[$this->data['pk']]);
                }
            }
	}
        
//        function load_data_daftar_master_setting($page_mode = NULL) {
//
//
//        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();
//
//        if (!$page_mode)
//            $page_mode = $this->input->get('page_mode');
//
//
//        foreach ((array) @$this->input->get('CARI') as $k => $v) {
//            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
//        }
//
//        if (!property_exists($this, 'CARI')) {
//            $this->CARI = NULL;
//        }
//
//        $this->load->model('msyssetting');
//
//        $response = $this->msyssetting->get_daftar_master_setting($currentpage, $keyword, $rowperpage, $this->CARI);
//
//        $dtable_output = array(
//            "sEcho" => intval($this->input->get("sEcho")),
//            "iTotalRecords" => intval($response->total_rows),
//            "iTotalDisplayRecords" => intval($response->total_rows),
//            "aaData" => $response->result
//        );
//                    
//            
//            $this->to_json($dtable_output);
//    }

	public function direkturpp($type='',$id='')
	{
            $sql1 = "SELECT ID_SETTING FROM `CORE_SETTING` WHERE OWNER = 'app.lhkpn' AND SETTING = 'direkturpp'";
            $rs = $this->db->query($sql1);
            $id = $rs->row()->ID_SETTING;
            
		$this->data['tbl'] = 'CORE_SETTING';
		$this->data['pk'] = 'ID_SETTING';

		if($type=='lista'){
			$this->db->select('*');
			$this->db->from('CORE_SETTING');
			$this->db->where('1=1', null, false);
			$this->db->where('OWNER', 'app.lhkpn');
			$this->db->where('SETTING', 'direkturpp');

			$this->data['title'] = 'Setting Direktur PP LHKPN';

			$breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
			$breadcrumbitem[] = [ucwords(strtolower(get_called_class())) => @$this->segmentTo[2]];

			$breadcrumbdata = [];
			foreach ($breadcrumbitem as $list) {
				$breadcrumbdata = array_merge($breadcrumbdata,$list);
			}
			$this->data['breadcrumb'] 	= call_user_func('ng::genBreadcrumb', $breadcrumbdata);

		}else if($type=='list'){
			$this->data['item'] = $id?(object)$this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0]:'';
		}else if($type=='save'){
			if($this->act=='doupdatesetting'){
				$data = array(
					'VALUE'         	=> $this->input->post('PN'),
					'UPDATED_TIME'     	=> time(),
					'UPDATED_BY'     	=> $this->session->userdata('USR'),
					'UPDATED_IP'     	=> $this->input->ip_address(),
				);

                $OWNER	    = $this->input->post('OWNER', TRUE);
                $SETTING	= $this->input->post('SETTING', TRUE);
                $sql = "SELECT * FROM `CORE_SETTING` WHERE OWNER = '$OWNER' AND SETTING = '$SETTING'";
                $rs = $this->db->query($sql);

				$this->db->where($this->data['pk'], $rs->row()->ID_SETTING);
				$this->db->update($this->data['tbl'], $data);

				ng::logActivity('Edit Setting = '.$data[$this->data['pk']]);
			}
		}
	}

	public function direkturpencegahan($type='',$id='')
	{
            $sql1 = "SELECT ID_SETTING FROM `CORE_SETTING` WHERE OWNER = 'app.lhkpn' AND SETTING = 'direkturpencegahan'";
            $rs = $this->db->query($sql1);
            $id = $rs->row()->ID_SETTING;
            
		$this->data['tbl'] = 'CORE_SETTING';
		$this->data['pk'] = 'ID_SETTING';

		if($type=='lista'){
			$this->db->select('*');
			$this->db->from('CORE_SETTING');
			$this->db->where('1=1', null, false);
			$this->db->where('OWNER', 'app.lhkpn');
			$this->db->where('SETTING', 'direkturpencegahan');

			$this->data['title'] = 'Setting Direktur Bidang PencegahanLHKPN';

			$breadcrumbitem[] = ['Dashboard' => 'index.php/welcome/dashboard'];
			$breadcrumbitem[] = [ucwords(strtolower(get_called_class())) => @$this->segmentTo[2]];

			$breadcrumbdata = [];
			foreach ($breadcrumbitem as $list) {
				$breadcrumbdata = array_merge($breadcrumbdata,$list);
			}
			$this->data['breadcrumb'] 	= call_user_func('ng::genBreadcrumb', $breadcrumbdata);

		}else if($type=='list'){
			$this->data['item'] = $id?(object)$this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0]:'';
		}else if($type=='save'){
			if($this->act=='doupdatesetting'){
				$data = array(
					'VALUE'         	=> $this->input->post('PN'),
					'UPDATED_TIME'     	=> time(),
					'UPDATED_BY'     	=> $this->session->userdata('USR'),
					'UPDATED_IP'     	=> $this->input->ip_address(),
				);

                $OWNER	    = $this->input->post('OWNER', TRUE);
                $SETTING	= $this->input->post('SETTING', TRUE);
                $sql = "SELECT * FROM `CORE_SETTING` WHERE OWNER = '$OWNER' AND SETTING = '$SETTING'";
                $rs = $this->db->query($sql);

				$this->db->where($this->data['pk'], $rs->row()->ID_SETTING);
				$this->db->update($this->data['tbl'], $data);

				ng::logActivity('Edit Setting = '.$data[$this->data['pk']]);
			}
		}
	}
}