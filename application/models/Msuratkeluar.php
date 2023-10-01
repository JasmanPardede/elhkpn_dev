<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
	
/** 
 * Model Msuratkeluar
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Msuratkeluar extends CI_Model
{

    private $table = 'T_SURAT_KELUAR';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('ID_SURAT', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '')
    {
        if (is_array($filter)) {
            $useLike['NAMA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        return $this->db->get($this->table)->num_rows();
        // return $this->db->count_all($this->table);
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '')
    {
        if (is_array($filter)) {
            $useLike['NAMA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID_SURAT', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_by_id($id)
    {
        $this->db->where('ID_SURAT', $id);
        return $this->db->get($this->table);
    }

    function save($suratkeluar)
    {
        $this->db->insert($this->table, $suratkeluar);
        return $this->db->insert_id();
    }

    function update($id, $suratkeluar)
    {
        $this->db->where('ID_SURAT', $id);
        $this->db->update($this->table, $suratkeluar);
    }

    function delete($id)
    {
        $this->db->where('ID_SURAT', $id);
        $this->db->delete($this->table);
    }
    
    function send_message($pengirim, $idUser, $penerima, $subject, $pesan, $send_to_mail = TRUE, $file = FALSE, $trusted_msg = FALSE, $idlhkpn = NULL, $idkeluarga = NULL){
        $this->db->trans_begin();
        
        $pesan = $trusted_msg ? $pesan : htmlspecialchars($pesan);
        
        $data = array(
            'ID_PENGIRIM' => $pengirim,
            'ID_PENERIMA' => $idUser,
            'SUBJEK' => $subject,
            'PESAN' => $pesan,
            'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
            'IS_ACTIVE' => '1',
            'ID_LHKPN' => $idlhkpn,
            'ID_KELUARGA' => $idkeluarga
        );
        
        if($file){
            $data['FILE'] = $file;
        }

        $data2 = $data;
        $result = $this->mglobal->insert('T_PESAN_KELUAR', $data);

        if ($result) {
            $data2['TANGGAL_MASUK'] = date('Y-m-d H:i:s');
            unset($data2['TANGGAL_KIRIM']);
            $this->mglobal->insert('T_PESAN_MASUK', $data2);
        }

        if ($send_to_mail) {
            ng::mail_send($penerima, $subject, $pesan);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return intval($this->db->trans_status());
    }
}

?>