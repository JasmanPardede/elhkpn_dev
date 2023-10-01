<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mverification extends CI_Model {

	protected $lhkpn = 't_lhkpn'; //status
	protected $imp_excel = 't_imp_xl_lhkpn'; //is_send
	protected $penerimaan = 't_lhkpnoffline_penerimaan'; //is_send

	function __construct() {
        parent::__construct();
    }

    function update_status_lhkpn($id_lhkpn){
    	$status = array(
    		'IS_ACTIVE' => '-1'
    	);
    	$this->db->where('id_lhkpn', $id_lhkpn);
    	$this->db->update($this->lhkpn, $status);
        if($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

    function update_status_penerimaan($id_lhkpn){
    	$status = array(
    		'IS_SEND' => 0,
            'IS_KEMBALI' => 1,
            'UPDATED_BY' => $this->session->userdata('USERNAME'),
            'UPDATED_TIME' => date('Y-m-d H:i:s')
    	);
    	$this->db->where('id_lhkpn', $id_lhkpn);
    	$this->db->update($this->penerimaan, $status);
        if($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

    function get_id_imp_xl_lhkpn($id_lhkpn)
    {
    	$this->db->where('id_lhkpn', $id_lhkpn);
        $result = $this->db->get($this->penerimaan);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $id = $row->ID_IMP_XL_LHKPN;

            return $id;
        }
        else{
            return null;
        }
    }

    function update_status_imp_xl_lhkpn($id_imp_xl_lhkpn)
    {
        $status = array(
            'is_send' => 0
        );
        $this->db->where('id_imp_xl_lhkpn', $id_imp_xl_lhkpn);
        $this->db->update($this->imp_excel, $status);
        if($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }
}