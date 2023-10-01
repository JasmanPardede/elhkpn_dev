<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
	
/** 
 * Model Mlhkpnharta
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mlhkpnharta extends CI_Model
{

    private $table       = 'T_LHKPN_HARTA_TIDAK_BERGERAK';

    function __construct()
    {
        parent::__construct();
    }

    function list_all()
    {
        $this->db->order_by('ID_HARTA', 'asc');
        return $this->db->get($this->table);
    }
    function list_all_by($table)
    {
        $this->db->order_by('ID_HARTA', 'asc');
        return $this->db->get($table);
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
        $this->db->order_by('ID_HARTA', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_by_id($id)
    {
        $this->db->where('ID', $id);
		$this->db->where('IS_ACTIVE', '1');
		
		$query = $this->db->get($this->table); 
        return $query;  
    }
	
	function get_id_lhkpn_by_id($table_source,$id)
    {
		if($table_source == 1){
			$table = 't_lhkpn_harta_tidak_bergerak';
		}else if($table_source == 2) {
			$table = 't_lhkpn_harta_bergerak';
		}else if($table_source == 3) {
			$table = 't_lhkpn_harta_bergerak_lain';
		}else if($table_source == 4) {
			$table = 't_lhkpn_harta_surat_berharga';
		}else if($table_source == 5) {
			$table = 't_lhkpn_harta_kas';
		}
        $this->db->where('ID', $id);
		$this->db->where('IS_ACTIVE', '1');
		
		$query = $this->db->get($table); 
        return $query;  
    }
	
	function get_data_by($table, $id)
    {
        $this->db->where($table.'.ID', $id);
        $this->db->join('M_MATA_UANG', $table.'.MATA_UANG = M_MATA_UANG.ID_MATA_UANG', 'left');
        return $this->db->get($table);
    }

    function get_data_kk($table, $id)
    {
        $this->db->select('a.ID,a.ID_LHKPN,a.FILE_BUKTI,b.ID_PN,c.ID_PN,c.NIK');
        $this->db->join('T_LHKPN b', 'a.ID_LHKPN = b.ID_LHKPN', 'LEFT');
        $this->db->join('T_PN c', 'b.ID_PN = c.ID_PN', 'LEFT');
        $this->db->where('a.ID', $id);
        return $this->db->get($table);
    }

    function save($lhkpnharta , $tabel)
    {
        $this->db->insert($tabel, $lhkpnharta);
        return $this->db->insert_id();
    }

    function update($id, $lhkpnharta , $tabel)
    {
        $this->db->where('ID', $id);
        $this->db->update($tabel, $lhkpnharta);
    }

    function delete($id , $tabel)
    {
        $this->db->where('ID', $id);
        $this->db->delete($tabel);
    }

    //provinsi
	function get_provinsi_by($id)
    {
        $this->db->order_by('NAME', 'asc');
        $this->db->where('LEVEL', '1');
		$this->db->where('IDPROV', $id);
        $this->db->select('IDPROV, NAME');
        $data = $this->db->get('M_AREA')->result();
        return $data;
    }
	
    function get_provinsi()
    {
        $this->db->order_by('NAME', 'asc');
        $this->db->where('LEVEL', '1');
        $this->db->select('IDPROV, NAME');
        $data = $this->db->get('M_AREA')->result();
        return $data;
    }
     function get_provinsi_new()
    {
        $this->db->order_by('NAME', 'asc');
        $this->db->where('LEVEL', '1');
        $this->db->select('ID_PROV, NAME');
        $data = $this->db->get('M_AREA_PROV')->result();
        return $data;
    }

    function get_negara()
    {
        $this->db->order_by('NAMA_NEGARA', 'asc');
        $this->db->select('KODE_ISO3, NAMA_NEGARA');
        $data = $this->db->get('M_NEGARA')->result();
        return $data;
    }

    function get_MONEY()
    {
        $this->db->order_by('NAMA_MATA_UANG', 'asc');
        $this->db->select('ID_MATA_UANG, SINGKATAN');
        $data = $this->db->get('M_MATA_UANG')->result();
        return $data;
    }

    function get_kabkot($id_prov)
    {
        $this->db->order_by('NAME', 'asc');
        $this->db->where('LEVEL', '2');
        $this->db->where('IDPROV', $id_prov);
        $this->db->select('IDKOT, NAME');
        $data = $this->db->get('M_AREA')->result();
        $this->db->last_query();
        return $data;
    }

    function get_kec($id_prov, $id_kabkot)
    {
        $this->db->order_by('NAME', 'asc');
        // $this->db->where('LEVEL', '1');
        $this->db->where('IDPROV', $id_prov);
        $this->db->where('CAST(IDKOT AS UNSIGNED) =', $id_kabkot);
        $this->db->where('IDKEC <>', '');
        $this->db->where('IDKEL', '');
        $this->db->select('IDKEC, NAME');
        $data = $this->db->get('M_AREA')->result();
        return $data;
    }

    function get_kel($id_prov, $id_kabkot, $id_kec)
    {
        $this->db->order_by('NAME', 'asc');
        // $this->db->where('LEVEL', '1');
        $this->db->where('IDPROV', $id_prov);
        $this->db->where('CAST(IDKOT AS UNSIGNED) =', $id_kabkot);
        $this->db->where('IDKEC', $id_kec);
        $this->db->where('IDKEL <>', '');
        $this->db->select('IDKEL, NAME');
        $data = $this->db->get('M_AREA')->result();
        return $data;
    }

    function get_unit($id)
    {
        $this->db->order_by('UK_NAMA', 'asc');
        // $this->db->where('LEVEL', '1');
        $this->db->where('UK_LEMBAGA_ID', $id);
        $this->db->select('UK_ID, UK_NAMA');
        $data = $this->db->get('M_UNIT_KERJA')->result();
        return $data;
    }

    function get_pemanfaatan($gol)
    {
        $this->db->order_by('NOMOR_KODE', 'asc');
        // $this->db->where('LEVEL', '1');
        $this->db->like('GOLONGAN_HARTA', $gol);
        $this->db->select('ID_PEMANFAATAN, PEMANFAATAN, NOMOR_KODE');
        $data = $this->db->get('M_PEMANFAATAN')->result();
        return $data;
    }

    function get_bukti($gol)
    {
        $this->db->order_by('ID_JENIS_BUKTI', 'ASC');
        // $this->db->where('LEVEL', '1');
        $this->db->like('GOLONGAN_HARTA', $gol);
        $this->db->select('ID_JENIS_BUKTI, JENIS_BUKTI');
        $data = $this->db->get('M_JENIS_BUKTI')->result();
        return $data;
    }

    function get_harta($gol)
    {
        $this->db->order_by('ID_JENIS_HARTA', 'ASC');
        // $this->db->where('LEVEL', '1');
        $this->db->like('GOLONGAN', $gol);
        $this->db->select('ID_JENIS_HARTA, NAMA');
        $data = $this->db->get('M_JENIS_HARTA')->result();
        return $data;
    }

    function get_delete_harta($table, $id)
    {
        $this->db->join('M_MATA_UANG', 'MATA_UANG = ID_MATA_UANG');
        $this->db->where('ID', $id);
        return $this->db->get($table);
    }
}

?>
