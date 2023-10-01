<?php
/*
 * Author : Arif Kurniawan
 * Email  : arif.kurniawan86@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Makses extends CI_Model
{
	var $is_login           = false;
    var $is_read            = false;
    var $is_write           = false;
    private $t_user_akses   = 'T_USER_AKSES';
    private $t_user_role    = 'T_USER_ROLE';
    private $t_user         = 'T_USER';
    private $t_menu         = 'T_MENU';
    var $controller    = 'welcome';
    var $method         = 'index';

    function __construct() {
        parent::__construct();
    }

    function initialize(){

        if ( $this->session->userdata('logged_in') ) {
            $this->is_login = true;
            $id_user    = $this->session->userdata('ID_USER');
            $controller = $this->router->fetch_class();
            $method     = $this->router->fetch_method();
            // $method     = ($method=='index') ? '' : $method;
            // $method = ($method=='edit' || $method=='save'?'index':$method).'/'.$this->uri->segment(4);
            $method = ($method=='add' || $method=='edit' || $method=='delete' || $method=='detail' || $method=='display' || $method=='save'?'index':$method).'/'.$this->uri->segment(4);
            $user_role  = $this->session->userdata('ID_ROLE');
            // echo $user_role.' : '.$controller.'/'.$method.'/'.$this->uri->segment(4);
            //check hak akses
            $permission = $this->get_permission_by_user($id_user); 
            

            if ( count($permission) > 0 ) {
                $arr_method     = array();
                $arr_akses      = array();
                foreach ( $permission as $dt ) {
                    if ( strtolower($dt['CONTROLLER']) == strtolower($controller) ) {
                        if ( !empty($dt['METHOD']) )
                            $arr_method[strtolower($dt['METHOD'])] = array('IS_READ'=>$dt['IS_READ'], 'IS_WRITE'=>$dt['IS_WRITE']);
                        else
                            $arr_akses = array('IS_READ'=>$dt['IS_READ'], 'IS_WRITE'=>$dt['IS_WRITE']);
                    }
                }

                if ( !empty($method) && array_key_exists($method, $arr_method) ) {
                    $this->is_read  = empty($arr_method[$method]['IS_READ']) ? false : true;
                    $this->is_write = empty($arr_method[$method]['IS_WRITE']) ? false : true;
                } else if ( !empty($method) && !array_key_exists($method, $arr_method) ) {
                    if ( array_key_exists('IS_READ', $arr_akses) ) {
                        $this->is_read  = empty($arr_akses['IS_READ']) ? false : true;
                        $this->is_write = empty($arr_akses['IS_WRITE']) ? false : true;
                    }
                } else if ( empty($method) ) {
                    if ( array_key_exists('IS_READ', $arr_akses) ) {
                        $this->is_read  = empty($arr_akses['IS_READ']) ? false : true;
                        $this->is_write = empty($arr_akses['IS_WRITE']) ? false : true;
                    }
                }
            }

            $this->accessByConfig($user_role, $controller);
        }else{
            $this->output->set_status_header('501'); // Lempar ke halaman login
            die();
        }
        if ( $user_role == ID_ROLE_ADMAPP ) {
            $this->is_read  = true;
            $this->is_write = true;
        }
    }

    private  function accessByConfig($user_role, $method)
    {
        $config_akses = [];
        foreach(explode(',', $user_role) as $row) {
            if(isset($this->config->item('AKSES_ROLE')[$row])) {
                $tmp = explode(',', $this->config->item('AKSES_ROLE')[$row]);
                foreach($tmp as $rows){
                    $config_akses[$rows] = '1';
                }
            }
        }

        if(isset($config_akses[$method])){
            $this->is_read  = true;
            $this->is_write = true;
        }

    }
    function check_is_first() {
        $controller = $this->router->fetch_class();
        $method     = $this->router->fetch_method();
        $is_first   = $this->session->userdata('IS_FIRST');
        $is_kpk     = $this->session->userdata('IS_KPK');

        if ( $is_kpk <> 1  && $is_first == 1 && (strtolower($controller) != 'welcome'
            ||  (strtolower($controller) == 'welcome' && strtolower($method) != 'changepassword')) ) {
            //redirect(site_url().'/welcome/changepassword');
            redirect('portal/user/validation');
        }
    }
    function check_is_read() {
        // $this->is_read = true; return;
        
        if ( !$this->is_read ) {
            show_error('Anda tidak memiliki akses untuk melihat data ini !', 401);
            exit();
        }
    }
    function check_is_write() {
        // $this->is_write = true; return;

        if ( !$this->is_write ) {
            show_error('Anda tidak memiliki akses untuk melakukan tindakan ini !', 401);
            exit();
        }
    }
    function get_permission_by_role($id_role = 0) {
        $where = "1=1";
        $id_role = explode(',',$id_role);
        $where .= " AND (";
        for ( $i=0; $i<count($id_role); $i++ ) {
            $where .= "ID_ROLE = '".$id_role[$i]."' OR ";
        }
        $where = substr($where, 0, strlen($where)-4);
        $where .= ")";
        $this->db->select('PERMISSION')
            ->from($this->t_user_role)
            ->where($where);
        $query    = $this->db->get(); 
        $return_array = array();

        if ( is_object($query) ) {
            $data = $query->result(); //print_r($data);exit;
            foreach ( $data as $dt ) {
                if($dt->PERMISSION!=''){
                    $result = json_decode($dt->PERMISSION, true);
                    foreach ( $result as $key=>$val ) {
                        if ( !array_key_exists($key, $return_array) ) {
                            $return_array[$key] = $val;
                        } else {
                            foreach ( $val as $key_val=>$val_val ) {
                                if ( $key_val == 'IS_READ' && $return_array[$key][$key_val] == 0 )
                                    $return_array[$key][$key_val] = 1;
                                else if ( $key_val == 'IS_WRITE' && $return_array[$key][$key_val] == 0 )
                                    $return_array[$key][$key_val] = 1;
                            }
                        }
                    }
                }
            }
        }
        return $return_array;
    }
    function get_permission_by_user($id_user = 0) {
        $this->db->select('PERMISSION, ID_ROLE')
            ->from($this->t_user)
            ->where('ID_USER', $id_user);
        $data = $this->db->get()->row(); 

        if ( !empty($data->PERMISSION) ){
            
             return json_decode($data->PERMISSION, true); 
        }else{
            return $this->get_permission_by_role($data->ID_ROLE);
        }
           
       
            
    }

    public function create_or_update($role_id, $array_data) {
        if (is_array($array_data)) {
            //delete data di user_akses berdasarkan role
            $delete = $this->db->delete('t_user_akses', array('ID_ROLE' => (int)$role_id));
            if ($delete) {
                // insert data ke user_akses
                if ($this->db->insert_batch($this->t_user_akses, $array_data)) {
                    return $array_data;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}