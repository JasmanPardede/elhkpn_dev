<?php
if(!defined('APPPATH')){
    exit;
}
?>
<?php

class Msuratmasuk extends CI_Model
{

    private $table = 'T_PESAN_MASUK';

    function __construct()
    {
        parent::__construct();
    }
    
    public function __get_all_configure_cari($cari){
        if ($cari) {
            $this->db->like('T_USER.NAMA', $cari);
            $this->db->or_like('T_PESAN_MASUK.SUBJEK', $cari);
            $this->db->or_like('m_inst_satker.INST_NAMA', $cari);
        }
    }
    
    public function __get_all_configure_join_and_where($id_user){
        $this->db->where('T_PESAN_MASUK.IS_ACTIVE', '1');
        $this->db->where('T_PESAN_MASUK.ID_PENERIMA', $id_user);
        $this->db->order_by('T_PESAN_MASUK.TANGGAL_MASUK', 'DESC');
        $this->db->join('T_USER', 'T_USER.ID_USER = T_PESAN_MASUK.ID_PENGIRIM');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
    }
    
    public function get_all($id_user, $cari = FALSE, $iDisplayLength = 10, $iDisplayStart = 0, $show_jumlah = TRUE){
        
        $this->__get_all_configure_cari($cari);
        $this->__get_all_configure_join_and_where($id_user);
        
        $this->db->limit($iDisplayLength, $iDisplayStart);
        
        $obj = $this->db->get($this->table)->result();
        
        if(!$show_jumlah){
            return $obj;
        }
        
        $this->__get_all_configure_cari($cari);
        $this->__get_all_configure_join_and_where($id_user);
        $jml = $this->db->get($this->table)->num_rows();
        
        return [$obj, $jml];
    }
    
    public function get_detail($id = FALSE){
        if(!$id){
            return FALSE;
        }
        
        $this->db->where('ID', $id);
        $this->db->join('t_user', 't_user.ID_USER = '.$this->table.'.ID_PENERIMA');
        return $this->db->get($this->table)->row();
    }
}