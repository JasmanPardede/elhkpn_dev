<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller{

	var $obj;
	var $iTotalRecords;
    var $iTotalDisplayRecords;
    var $iDisplayStart;
    var $iDisplayLength;
    var $iSortingCols;
    var $sSearch;
    var $sEcho;

	function __Contruct(){
		parent::__Contruct(); 
		 call_user_func('ng::islogin');
        $this->makses->initialize();
	}

	function index(){
		$data = array(
			'title'=>'Faq'
		);
		$this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
	}

	function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	function add(){
		$data = array(
			'title'=>'Tambah Data',
		);
		$this->load->view(strtolower(__CLASS__).'_form', $data);
	}

	function save(){
		$method = $this->input->post('METHOD');
		if($method=='save'){
			$this->insert();
		}else{
			$this->update();
		}
	}

	function insert(){
		if($this->input->post('is_published')){
			$publish = 1;
		}else{
			$publish = 0;
		}
		$data = array(
			'PERTANYAAN'=>$this->input->post('txtPertanyaan'),
			'JAWABAN'=>$this->input->post('txtJawaban'),
			'IS_PUBLISH'=>$publish,
			'CREATED_TIME'=>date("Y-m-d H:i:s"),
			'CREATED_BY'=>$this->session->userdata('NAMA'),
			'CREATED_IP'=>$this->get_client_ip(),
			'UPDATED_TIME'=>date("Y-m-d H:i:s"),
			'UPDATED_BY'=>$this->session->userdata('NAMA'),
			'UPDATED_IP'=>$this->get_client_ip(),
		);
		$result = $this->db->insert('t_faq',$data);
		if($result){
			echo "1";
		}else{
			echo "0";
		}
	}

	function update(){
		if($this->input->post('is_published')){
			$publish = 1;
		}else{
			$publish = 0;
		}
		$data = array(
			'PERTANYAAN'=>$this->input->post('txtPertanyaan'),
			'JAWABAN'=>$this->input->post('txtJawaban'),
			'IS_PUBLISH'=>$publish,
			'UPDATED_TIME'=>date("Y-m-d H:i:s"),
			'UPDATED_BY'=>$this->session->userdata('NAMA'),
			'UPDATED_IP'=>$this->get_client_ip(),
		);
		$this->db->where('ID_FAQ',$this->input->post('ID_FAQ'));
		$result = $this->db->update('t_faq',$data);
		if($result){
			echo "1";
		}else{
			echo "0";
		}
	}

	function edit($id){
		$this->db->where('ID_FAQ',$id);
		$data = $this->db->get('t_faq')->row();
		echo json_encode(array($data));
	}

	function Delete($id){
		$this->db->where('ID_FAQ',$id);
		$result = $this->db->delete('t_faq');
		if($result){
			echo "1";
		}else{
			echo "0";
		}
	}

	function ListFaq(){
		$iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->limit($iDisplayLength,$iDisplayStart);
        $this->db->order_by('ID_FAQ','DESC');
        $obj =  $this->db->get('t_faq')->result();
        if ($obj) {
        	foreach ($obj as $list) {
        		 $i++;

        		 if($list->IS_PUBLISH=='1'){
        		 	$notif = "<label class='label label-info'>Sudah Terpublish</label>";
        		 }else{
        		 	$notif = "<label class='label label-danger'>Tidak Terpublish</label>";
        		 }

        		 $edit = "<a style='margin-right:5px;' id='".$list->ID_FAQ."'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action'><i class='fa fa-pencil'></i></a>";
        		 $delete = "<a id='".$list->ID_FAQ."'  href='javascript:void(0)' class='btn btn-danger btn-sm delete-action'><i class='fa fa-trash'></i></a>";

        		  $aaData[] = array(
                    $i,
                    $list->CREATED_TIME,
                    $list->PERTANYAAN,
                    substr($list->JAWABAN, 0,50),
                    $notif,
                    $edit.''.$delete 
                );
        	}
        }
         $jml = $this->db->get('t_faq')->num_rows();
         $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
	}
}