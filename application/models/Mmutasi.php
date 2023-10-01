<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Model Mmutasikeluar
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mmutasi extends CI_Model
{

    private $table = 'T_MUTASI_PN';
	private $table_pajabat = 'T_PN';
    private $table_jabatan = 'M_JABATAN';
    private $table_pn_jabatan = 'T_PN_JABATAN';
	private $table_inst = 'M_INST_SATKER';
	private $table_histoty_jab = 'T_HISTORY_JABATAN';

    function __construct()
    {
        parent::__construct();
    }
	function get_id_pn($nik = '') {
        $this->db->select('ID_PN')->from($this->table_pajabat)->where('NIK', $nik);
        $this->db->last_query();exit();
        $query = $this->db->get();
        if ( is_object($query) ) {
            $data = $query->row();
            if ( is_object($data) ) {
                return $data->ID_PN;
            }
        }
        return $query;
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
        if ($this->session->userdata('IS_KPK') !== '1') {
            // $this->db->where('ID_INST_TUJUAN',$this->session->userdata('INST_SATKERKD'))
        	// $this->db->where('ID_INST_ASAL',$this->session->userdata('INST_SATKERKD'))
        	// ->where('ID_INST_TUJUAN IS NOT NULL',NULL, false);
    	}
        $this->db->select('A.ID_MUTASI,
							A.ID_PN,
							A.ID_INST_ASAL,
							A.ID_INST_TUJUAN,
							A.STATUS_APPROVAL,
							A.ID_JABATAN,
							A.ID_JABATAN_BARU,
                            A.ID_STATUS_AKHIR_JABAT,
                            C.STATUS as STATUS_JABAT,
							NIK,
							NAMA,
							UNIT_KERJA_LAMA,
							UNIT_KERJA_BARU')
				->from('T_MUTASI_PN A')
				->join('T_PN B','A.ID_PN=B.ID_PN','left')
                ->join('T_STATUS_AKHIR_JABAT C','A.ID_STATUS_AKHIR_JABAT=C.ID_STATUS_AKHIR_JABAT','left')
				//->where('ID_INST_ASAL',$this->session->userdata('INST_SATKERKD'))
                //->where('ID_INST_TUJUAN !=',$this->session->userdata('INST_SATKERKD'))
                //->where('ID_INST_TUJUAN IS NOT NULL',NULL, false)
				->where('( STATUS_APPROVAL IS NULL OR STATUS_APPROVAL = 0)',NULL, false)
				->limit($limit,$offset);
		$this->db->order_by('B.NAMA', 'asc');
        return $this->db->get();
    }
	
	function get_paged_list2($limit = 10, $offset = 0, $filter = '' , $type = 'mutasi')
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
        if ($this->session->userdata('IS_KPK') !== '1') {
        	$this->db->where('ID_INST_TUJUAN',$this->session->userdata('INST_SATKERKD'));
    	}
        $this->db->select('A.ID_MUTASI,
							A.ID_PN,
							A.ID_INST_ASAL,
							A.ID_INST_TUJUAN,
							A.STATUS_APPROVAL,
							A.ID_JABATAN AS JABATAN_LAMA,
							A.ID_JABATAN_BARU AS JABATAN_BARU,
							A.UNIT_KERJA_LAMA,
							A.UNIT_KERJA_BARU,
                            A.ID_STATUS_AKHIR_JABAT,
                            C.STATUS as STATUS_JABAT,
							NIK,
							NAMA
							')//ada fild UNIT_KERJA (dihapus)
				->from('T_MUTASI_PN A')
				->join('T_PN B','A.ID_PN=B.ID_PN','left')
                ->join('T_STATUS_AKHIR_JABAT C','A.ID_STATUS_AKHIR_JABAT=C.ID_STATUS_AKHIR_JABAT','left')
				// ->where('ID_INST_TUJUAN',$this->session->userdata('INST_SATKERKD'))
				->limit($limit,$offset);
        if ($this->session->userdata('IS_KPK') == 1) {
            $this->db->where('( STATUS_APPROVAL IS NULL OR STATUS_APPROVAL = 0) AND ID_INST_ASAL != '.$this->session->userdata('INST_SATKERKD'), NULL, false);
        }
        else
        {
            $this->db->where('( STATUS_APPROVAL IS NULL OR STATUS_APPROVAL = 0) AND ID_INST_TUJUAN = '.$this->session->userdata('INST_SATKERKD'), NULL, false);

        }
		$this->db->order_by('B.NAMA', 'asc');
        $data = $this->db->get();
        return $data;
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
        $this->db->select('A.ID_MUTASI,
							A.ID_PN,
							A.ID_INST_ASAL,
							A.ID_INST_TUJUAN,
							A.STATUS_APPROVAL,
							A.JABATAN,
							NIK,
							NAMA')
				->from('T_MUTASI_PN A')
				->join('T_PN B','A.ID_PN=B.ID_PN','left')
				->where('ID_INST_TUJUAN',$this->session->userdata('INST_SATKERKD'))
				->where('STATUS_APPROVAL ',0);
		$this->db->order_by('B.NIK', 'asc');
        $query = $this->db->get();
		return $query->num_rows(); 
    }
    function count_all_keluar($filter = '')
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
        $this->db->select('A.ID_MUTASI,
							A.ID_PN,
							A.ID_INST_ASAL,
							A.ID_INST_TUJUAN,
							A.STATUS_APPROVAL,
							A.JABATAN,
							NIK,
							NAMA')
				->from('T_MUTASI_PN A')
				->join('T_PN B','A.ID_PN=B.ID_PN','left')
				->where('ID_INST_ASAL',$this->session->userdata('INST_SATKERKD'))
                ->where('ID_INST_TUJUAN !=',$this->session->userdata('INST_SATKERKD'))
				->where('STATUS_APPROVAL ',0);
		$this->db->order_by('B.NIK', 'asc');
        $query = $this->db->get();
		return $query->num_rows(); 
	}
	function count_all2($filter = '')
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
        $this->db->select('A.ID_MUTASI,
							A.ID_PN,
							A.ID_INST_ASAL,
							A.ID_INST_TUJUAN,
							A.STATUS_APPROVAL,
							LEMBAGA,
							A.JABATAN,
							NIK,
							NAMA,
							')//ada fild UNIT_KERJA (dihapus)
				->from('T_MUTASI_PN A')
				->join('T_PN B','A.ID_PN=B.ID_PN','left')
				->where('ID_INST_TUJUAN',$this->session->userdata('INST_SATKERKD'))
				->where('STATUS_APPROVAL ',0);
		$this->db->order_by('B.NIK', 'asc');
        $query = $this->db->get();
		return $query->num_rows(); 
    }
	
	function load_pejabat($search)
	{
        $where = "ID_PN IN (SELECT ID_PN FROM ".$this->table_pn_jabatan." WHERE LEMBAGA = '".$this->session->userdata('INST_SATKERKD')."')
                  AND (LOWER(NAMA) LIKE '%".$search."%' OR LOWER(NIK) LIKE '%".$search."%')";
		$this->db->select('ID_PN,NIK,NAMA')
					->where($where, NULL, false);
		$query = $this->db->get($this->table_pajabat);
		return $query->result();
	}
	
	function load_instansi($search)
	{
		$this->db->select('INST_SATKERKD,INST_NAMA')
					->like('INST_NAMA',$search);
		$query = $this->db->get($this->table_inst);
		return $query->result();
	}
	
	function save($mutasi)
    {
        $this->db->insert($this->table, $mutasi);
        return $this->db->insert_id();
    }

    function update($id, $mutasi)
    {
        $this->db->where('ID_MUTASI', $id);
        $this->db->update($this->table, $mutasi);
    }

    function delete($id)
    {
        $this->db->where('ID_MUTASI', $id);
        $this->db->delete($this->table);
    }
	
	function get_nm_instansi($id)
    {
        $this->db->where('INST_SATKERKD', $id);
        $query = $this->db->get($this->table_inst);
		if($query->num_rows() > 0){
			$dt = $query->row();
			$result = $dt->INST_NAMA;
		}else{
			$result = "-";
		}
		return $result;
    }

    function get_his_jab_last($id_pn = '') {
        $this->db->select($this->table_jabatan.'.ID_JABATAN, '.$this->table_jabatan.'.NAMA_JABATAN')
                 ->from($this->table_pajabat)
                 ->join('T_PN_JABATAN', 'T_PN_JABATAN.ID_PN = '.$this->table_pajabat.'.id_pn')	
                 ->join($this->table_jabatan, 'T_PN_JABATAN.ID_JABATAN = '.$this->table_jabatan.'.ID_JABATAN')
                 ->where($this->table_pajabat.'.ID_PN', $id_pn);
        $query = $this->db->get();
        if ( is_object($query) ) {
            $data = $query->row();
            if ( is_object($data) )
                return $data;
        }
        return false;
    }

    function get_nm_jabatan($id)
    {
        $this->db->select('NAMA_JABATAN')
                 ->from($this->table_jabatan)
                 ->where('ID_JABATAN', $id);
        $query = $this->db->get();
        if(is_object($query)){
            $dt = $query->row();
            if ( is_object($dt) )
                return $dt->NAMA_JABATAN;
        }
        return NULL;
    }
	
	function get_nm_pejabat($id)
    {
        $this->db->where('ID_PN', $id);
        $query = $this->db->get($this->table_pajabat);
		if($query->num_rows() > 0){
			$dt = $query->row();
			$result = $dt->NAMA;
		}else{
			$result = "-";
		}
		return $result;
    }
	
	function get_mutasi_by_id($id)
    {
        $this->db->where('ID_MUTASI', $id);
        $query = $this->db->get($this->table);
		if($query->num_rows() > 0){
			$result = $query;
		}else{
			$result = false;
		}
		return $result;
    }
	
	function insert_history_jabatan($data)
	{
		$this->db->insert($this->table_histoty_jab,$data);
		return true;
	}
	
	function update_history_jabatan ($id,$data)
	{
		$this->db->where('ID_MUTASI',$id);
		$this->db->update($this->table_histoty_jab,$data);
		return true;
	}
	
	function del_jab_by_mutasi($id_mutasi)
	{
		$this->db->where('ID_MUTASI',$id_mutasi);
		$this->db->delete($this->table_histoty_jab);
	}
}