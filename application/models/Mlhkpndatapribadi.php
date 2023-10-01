<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * Model Mlhkpndatapribadi
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mlhkpndatapribadi extends CI_Model {

    private $table = 'T_LHKPN_DATA_PRIBADI';

    function __construct() {
        parent::__construct();
    }

    function list_all() {
        $this->db->order_by('ID_DATA_PRIBADI', 'asc');
        return $this->db->get($this->table);
    }

    function get_by_id_lhkpn($id_lhkpn = FALSE) {
        $response = FALSE;
        if ($id_lhkpn) {
            
             $sql_select = "t_lhkpn.entry_via, t_lhkpn_data_pribadi.ID, t_lhkpn_data_pribadi.ID_DATA_PRIBADI, t_lhkpn_data_pribadi.ID_LHKPN, t_lhkpn_data_pribadi.GELAR_DEPAN, t_lhkpn_data_pribadi.GELAR_BELAKANG, t_lhkpn_data_pribadi.NAMA_LENGKAP, t_lhkpn_data_pribadi.JENIS_KELAMIN, t_lhkpn_data_pribadi.TEMPAT_LAHIR, DATE_FORMAT( t_lhkpn_data_pribadi.TANGGAL_LAHIR, '%Y-%m-%d') as TANGGAL_LAHIR, t_lhkpn_data_pribadi.NIK, t_lhkpn_data_pribadi.NIP, t_lhkpn_data_pribadi.NPWP, t_lhkpn_data_pribadi.NO_KK, t_lhkpn_data_pribadi.STATUS_PERKAWINAN, t_lhkpn_data_pribadi.AGAMA, t_lhkpn_data_pribadi.JABATAN, t_lhkpn_data_pribadi.JABATAN_LAINNYA, t_lhkpn_data_pribadi.ALAMAT_RUMAH, t_lhkpn_data_pribadi.EMAIL_PRIBADI, t_lhkpn_data_pribadi.PROVINSI, t_lhkpn_data_pribadi.KABKOT, t_lhkpn_data_pribadi.KECAMATAN, t_lhkpn_data_pribadi.KELURAHAN, t_lhkpn_data_pribadi.TELPON_RUMAH, t_lhkpn_data_pribadi.HP, t_lhkpn_data_pribadi.HP_LAINNYA, t_lhkpn_data_pribadi.FOTO, t_lhkpn_data_pribadi.FILE_NPWP, t_lhkpn_data_pribadi.FILE_KTP, t_lhkpn_data_pribadi.IS_ACTIVE, t_lhkpn_data_pribadi.CREATED_TIME, t_lhkpn_data_pribadi.CREATED_BY, t_lhkpn_data_pribadi.CREATED_IP, t_lhkpn_data_pribadi.UPDATED_TIME, t_lhkpn_data_pribadi.UPDATED_BY, t_lhkpn_data_pribadi.UPDATED_IP, t_lhkpn_data_pribadi.KD_ISO3_NEGARA, t_lhkpn_data_pribadi.NEGARA, t_lhkpn_data_pribadi.ALAMAT_NEGARA, t_lhkpn_data_pribadi.formulirid_migrasi, t_lhkpn_data_pribadi.pnid_migrasi, "
             . "   m_negara.KODE_ISO3,.m_negara.NAMA_NEGARA,m_area_prov.*,m_area_kab.*";
            $this->db->select($sql_select);
            $this->db->limit(1);
            $this->db->where('T_LHKPN_DATA_PRIBADI.ID_LHKPN', $id_lhkpn);
            $this->db->group_by('t_lhkpn_data_pribadi.ID');
            $this->db->order_by('t_lhkpn_data_pribadi.ID', 'DESC');
            $this->db->where('t_lhkpn_data_pribadi.IS_ACTIVE', '1');
            $this->db->join('m_negara', 'm_negara.KODE_ISO3 = t_lhkpn_data_pribadi.KD_ISO3_NEGARA', 'LEFT');
            $this->db->join('m_area_prov', 'm_area_prov.NAME = t_lhkpn_data_pribadi.PROVINSI', 'LEFT');
            $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = t_lhkpn_data_pribadi.KABKOT', 'LEFT');
            $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
            $response = $this->db->get($this->table)->row();
        }

        return $response;
    }

    function count_all($filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA_LENGKAP'] = 'both';
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

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA_LENGKAP'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID_DATA_PRIBADI', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_by_id($id) {
        $this->db->where('ID_DATA_PRIBADI', $id);
        return $this->db->get($this->table);
    }

    function save($lhkpndatapribadi) {
        $this->db->insert($this->table, $lhkpndatapribadi);
        $id_data_pribadi = $this->db->insert_id();
        $this->db->where('ID', $id_data_pribadi);
        $this->db->update($this->table, ['ID_DATA_PRIBADI' => $id_data_pribadi]);
        return $this->db->insert_id();
    }

    function update($id, $lhkpndatapribadi) {
        $this->db->where('ID', $id);
        $this->db->update($this->table, $lhkpndatapribadi);
    }

    function delete($id) {
        $this->db->where('ID_DATA_PRIBADI', $id);
        $this->db->delete($this->table);
    }

}

?>