<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_harta extends MY_Controller {

    function __Construct() {
        parent::__Construct();
        $this->check_akses();
    }
    function index() {
        $options = array(); 

        $this->load->view('portal/filing/data_harta', $options);
    }

    function delete($ID, $TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        $PK = $data[0]['Column_name'];
        $this->db->where($PK, $ID);
        echo $this->db->delete($TABLE);
    }

    function penetapan($ID, $TABLE) {
        $result = 0;
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        $PK = $data[0]['Column_name'];

        $this->db->where($PK, $ID);
        $check = $this->db->get($TABLE)->row();

        if ($TABLE == 't_lhkpn_harta_kas') {
            $NILAI = 'NILAI_EQUIVALEN';
        } else if ($TABLE == 't_lhkpn_hutang') {
            $NILAI = 'SALDO_HUTANG';
        } else {
            $NILAI = 'NILAI_PELAPORAN';
        }

        $nilai_lepas = $check->$NILAI;

        if ($check) {
            if ($check->IS_PELEPASAN == '1') {
                $t_pelepasan = str_replace('t_lhkpn_', 't_lhkpn_pelepasan_', $TABLE);
                $this->db->where('ID_HARTA', $ID);
                $pelepasan = $this->db->get($t_pelepasan)->row();
                $nilai_lepas = $pelepasan->NILAI_PELEPASAN;
            }
        }


        $this->db->where($PK, $ID);
        $id_harta = $this->db->update($TABLE, array(
            'STATUS' => '1',
            'IS_PELEPASAN' => '0',
            'IS_CHECKED' => '1',
            $NILAI => $nilai_lepas
        ));

        if ($id_harta) {
            $result = 1;
        }
        echo $result;
    }

    function pk($TABLE) {
        $sql = "SHOW KEYS FROM " . $TABLE . " WHERE Key_name = 'PRIMARY'";
        $data = $this->db->query($sql)->result_array();
        return $data[0]['Column_name'];
    }

    function do_update($TABLE, $DATA, $ID = NULL) {
        if ($ID) {
            $this->db->where('ID', $ID);
            $result = $this->db->update($TABLE, $DATA);
        } else {
            $result = $this->db->insert($TABLE, $DATA);
        }
        if ($result) {
            if ($ID) {
                return $ID;
            } else {
                return $this->db->insert_id();
            }
        } else {
            return "0";
        }
    }

    function action($ID, $TABLE, $IS_COPY) {
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $data = $this->db->get($TABLE)->row();


        $nilai = 0;

        if ($TABLE == 't_lhkpn_harta_kas') {
            $key = 'NILAI_EQUIVALEN';
        } else if ($TABLE == 't_lhkpn_hutang') {
            $key = 'SALDO_HUTANG';
        } else {
            $key = 'NILAI_PELAPORAN';
        }
        $nilai = $data->$key;

        $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $data->ID_HARTA . '" />';
        $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $data->STATUS . '" />';

        if ($IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
            $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
            $delete = "<a id='" . $ID . "' href='javascript:void(0)' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";
        } else { // KONDISI KEDUA , KETIGA dst
            if ($data->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                if ($data->STATUS == '1') { // JIKA TETAP
                    $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                    $delete = "<a id='" . $ID . "' data-nilai=[" . $nilai . "]  href='javascript:void(0)' class='btn btn-danger btn-sm pelepasan-action'title='Pelepasan'><i class='fa fa-upload'></i></a>";
                } else { // JIKA BUKAN TETAP & LEPAS
                    if ($data->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                        $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                        $delete = "<a id='" . $ID . "'  data-nilai=[" . $nilai . "] href='javascript:void(0)' class='btn btn-danger btn-sm pelepasan-action' title='Pelepasan'><i class='fa fa-upload'></i></a>";
                        if ($data->STATUS == '3') {
                            $delete .= "<a id='" . $ID . "'  data-nilai=[" . $nilai . "] href='javascript:void(0)' class='btn btn-primary btn-sm penetapan-action' title='Tetap'><i class='fa fa-link'></i></a>";
                        }
                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                        $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                        $delete = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";
                    }
                }
            } else { // JIKA DATA DITETAPKAN
                $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Undo'><i class='fa fa-repeat'></i></a>";
//                $delete .= "<a id='" . $ID . "'  data-nilai=[" . $nilai . "] href='javascript:void(0)' class='btn btn-primary btn-sm penetapan-action' title='Penetapan'><i class='fa fa-link'></i></a>";
            }
        }
        return $edit . '' . $delete . '' . $id_ht;
    }

    function get_option($table, $name, $value) {
        $this->db->order_by($name);
        $data = $this->db->get($table)->result();
        echo "<option></option>";
        foreach ($data as $row) {
            echo "<option value='" . $row->$value . "'>" . strtoupper($row->$name) . "</option>";
        }
    }

    function get_option_hutang($table, $name, $value) {
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        echo "<option></option>";
        foreach ($data as $row) {
            echo "<option value='" . $row->$value . "'>" . strtoupper($row->$name) . "</option>";
        }
        exit;
    }

    function get_option_matauang($table, $name, $value) {
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        echo "<option></option>";
        foreach ($data as $row) {
            echo "<option value='" . $row->$value . "'>" . strtoupper($row->$name) . "</option>";
        }
        exit;
    }

    function parse_json($result) {
        $data = array();
        if ($result) {
            foreach ($result as $key => $value) {
                $data[$key] = trim($value);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function get_form($table, $id, $value, $name) {
        $result = NULL;
        $this->db->where($id, $value);
        $this->db->limit(1);
        $data = $this->db->get($table)->row();
        if ($data) {
            $result = $data->$name;
        }
        return $result;
        exit;
    }

    function get_asal_usul_with_data() { //diubah oleh Ferry
        $post = $this->input->post('data');  
		$data_json = json_decode($post); 
		$posted_data = $data_json->asal_usul; display($data_json);exit;
		$ID = $data_json->ID;
        $arr_data = array();
        if ($posted_data) {
            $arr_data = explode(",", $posted_data);
        }

        $value = 'ID_ASAL_USUL';
        $table = 'm_asal_usul';
        $object = 'ASAL_USUL';
        $name = 'ASAL_USUL';
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result(); 
		
		$this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result(); 
		
        $i = 1;
        foreach ($data as $row) {
			foreach($asal_usul as $dom){ 
				if($row->$value == $dom->ID_ASAL_USUL){ 
					$tanggal_transaksi = $dom->TANGGAL_TRANSAKSI;
					$besar_nilai = $dom->NILAI_PELEPASAN;
					$keterangan = $dom->URAIAN_HARTA;
					$pihak2_nama = $dom->NAMA;
					$pihak2_alamat = $dom->ALAMAT;
					
				}else{
					$tanggal_transaksi = '';
					$besar_nilai = '';
					$keterangan = '';
					$pihak2_nama = '';
					$pihak2_alamat = '';
				}
				$property = str_replace(' ', '-', $row->$name);
				$property = strtolower($property);
				$label = strtoupper($row->$name);
	
				$checked = !empty($arr_data) && in_array($row->$value, $arr_data) ? "checked=checked" : ""; 
				
				if($checked != ''){
					$ahref = '<a href="javascript:void(0)" id="view-to-pilih-' . $property . '" class="btn btn-view btn-xs btn-info">Lihat</a>';
					$label2 = '<label class="label label-primary">'.$asal_usul['0']->NILAI_PELEPASAN.'</label>';
				}else{
					$ahref = '';
					$label2 = '';
				}
				echo '
				<tr>
						<td><input type="checkbox" class="pilih order-' . $row->IS_OTHER . ' pilih-asal-usul" id="pilih-' . $property . '" name="' . $object . '[]" value="' . $row->$value . '" ' . $checked . ' /> ' . $i . '. ' . $label . '</td>
						<td id="view-pilih-' . $property . '">'.$ahref.'</td>
						<td id="result-pilih-' . $property . '">'.$label2.'</td>
						<input type="hidden" name="asal_tgl_transaksi[' . $row->$value . ']" id="asal-tgl_transaksi-pilih-' . $property . '" value="'.$tanggal_transaksi.'"/>
						<input type="hidden" name="asal_besar_nilai[' . $row->$value . ']" id="asal-besar_nilai-pilih-' . $property . '" value="'.$besar_nilai.'" />
						<input type="hidden" name="asal_keterangan[' . $row->$value . ']" id="asal-keterangan-pilih-' . $property . '" value="'.$keterangan.'"/>
						<input type="hidden" name="asal_pihak2_nama[' . $row->$value . ']" id="asal-pihak2_nama-pilih-' . $property . '" value="'.$pihak2_nama.'"/>
						<input type="hidden" name="asal_pihak2_alamat[' . $row->$value . ']" id="asal-pihak2_alamat-pilih-' . $property . '" value="'.$pihak2_alamat.'"/>
					</tr>
				';
				$i++;
				
			}
        }
        exit;
    }

    function get_asal_usul() {
        $value = 'ID_ASAL_USUL';
        $table = 'm_asal_usul';
        $object = 'ASAL_USUL';
        $name = 'ASAL_USUL';
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        $i = 1;
        foreach ($data as $row) {
            $property = str_replace(' ', '-', $row->$name);
            $property = strtolower($property);
            $label = strtoupper($row->$name);
            echo '
			   <tr>
                    <td><input type="checkbox" class="pilih order-' . $row->IS_OTHER . ' pilih-asal-usul" id="pilih-' . $property . '" name="' . $object . '[]" value="' . $row->$value . '"/> ' . $i . '. ' . $label . '</td>
                    <td id="view-pilih-' . $property . '"></td>
                    <td id="result-pilih-' . $property . '"></td>
                    <input type="hidden" name="asal_tgl_transaksi[' . $row->$value . ']" id="asal-tgl_transaksi-pilih-' . $property . '" />
                    <input type="hidden" name="asal_besar_nilai[' . $row->$value . ']" id="asal-besar_nilai-pilih-' . $property . '" />
                    <input type="hidden" name="asal_keterangan[' . $row->$value . ']" id="asal-keterangan-pilih-' . $property . '" />
                    <input type="hidden" name="asal_pihak2_nama[' . $row->$value . ']" id="asal-pihak2_nama-pilih-' . $property . '" />
                    <input type="hidden" name="asal_pihak2_alamat[' . $row->$value . ']" id="asal-pihak2_alamat-pilih-' . $property . '" />
                </tr>
			';
            $i++;
        }
        exit;
    }

    function get_jenis_bukti_with_data($gol = NULL) {

        $posted_data = $this->input->post('data');

        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by('ID_JENIS_BUKTI');
        if ($gol) {
            $this->db->where('GOLONGAN_HARTA', $gol);
        }
        $data = $this->db->get('m_jenis_bukti')->result(); 
        echo "<option></option>";
		foreach ($data as $row) {

            $selected = $posted_data == $row->ID_JENIS_BUKTI ? "selected" : "";

            echo "<option " . $selected . " value='" . $row->ID_JENIS_BUKTI . "'>" . strtoupper($row->JENIS_BUKTI) . "</option>";
        }
        exit;
        //foreach ($data as $key_value => $row) {
//      //      $selected = $posted_data == $row->ID_JENIS_BUKTI ? "selected" : "";
        //    $selected = $posted_data == ($key_value + 1) ? "selected" : "";
        //
//      //      echo "<option " . $selected . " value='" . $row->ID_JENIS_BUKTI . "'>" . strtoupper($row->JENIS_BUKTI) . "</option>";
        //    echo "<option " . $selected . " value='" . ($key_value + 1) . "'>" . strtoupper($row->JENIS_BUKTI) . "</option>";
        //}
        //exit;
    }

    function get_jenis_bukti($gol = NULL) {
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by('ID_JENIS_BUKTI');
        if ($gol) {
            $this->db->where('GOLONGAN_HARTA', $gol);
        }
        $data = $this->db->get('m_jenis_bukti')->result();
        echo "<option></option>";
        foreach ($data as $row) {
            echo "<option value='" . $row->ID_JENIS_BUKTI . "'>" . strtoupper($row->JENIS_BUKTI) . "</option>";
        }
        exit;
    }

    function get_jenis_harta_with_data($gol = NULL) {
        $this->db->order_by('ID_JENIS_HARTA');

        $posted_data = $this->input->post('data');

        if ($gol) {
            $this->db->where('GOLONGAN', $gol);
        }
        $data = $this->db->get('m_jenis_harta')->result();
        echo "<option></option>";
        foreach ($data as $row) {

            $selected = $posted_data == $row->ID_JENIS_HARTA ? "selected" : "";

            echo "<option " . $selected . " value='" . $row->ID_JENIS_HARTA . "'>" . strtoupper($row->NAMA) . "</option>";
        }
        exit;
    }
    
    function get_jenis_hutang_with_data() {
        $this->db->order_by('ID_JENIS_HUTANG');

        $posted_data = $this->input->post('data');

        $data = $this->db->get('m_jenis_hutang')->result();
        echo "<option></option>";
        foreach ($data as $row) {

            $selected = $posted_data == $row->ID_JENIS_HUTANG ? "selected" : "";

            echo "<option " . $selected . " value='" . $row->ID_JENIS_HUTANG . "'>" . strtoupper($row->NAMA) . "</option>";
        }
        exit;
    }
    
    function get_jenis_pelepasan_with_data() {
        $this->db->order_by('ID');

        $posted_data = $this->input->post('data');

        $data = $this->db->get('m_jenis_pelepasan_harta')->result();
        echo "<option></option>";
        foreach ($data as $row) {

            $selected = $posted_data == $row->ID ? "selected" : "";

            echo "<option " . $selected . " value='" . $row->ID . "'>" . strtoupper($row->JENIS_PELEPASAN_HARTA) . "</option>";
        }
        exit;
    }

    function get_mata_uang_with_data() {
        $this->db->order_by('ID_MATA_UANG');

        $posted_data = $this->input->post('data');

        $data = $this->db->get('m_mata_uang')->result();
        echo "<option></option>";
        foreach ($data as $row) {

            $selected = $posted_data == $row->ID_MATA_UANG ? "selected" : "";

            echo "<option " . $selected . " value='" . $row->ID_MATA_UANG . "'>" . strtoupper($row->NAMA_MATA_UANG) . "</option>";
        }
        exit;
    }

    function get_jenis_harta($gol = NULL) {
        $this->db->order_by('ID_JENIS_HARTA');
        if ($gol) {
            $this->db->where('GOLONGAN', $gol);
        }
        $data = $this->db->get('m_jenis_harta')->result();
        echo "<option></option>";
        foreach ($data as $row) {
            echo "<option value='" . $row->ID_JENIS_HARTA . "'>" . strtoupper($row->NAMA) . "</option>";
        }
        exit;
    }

    function get_pemanfaatan_harta_begerak() {
        $this->db->where('GOLONGAN_HARTA', 2);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by('ID_PEMANFAATAN', 'ASC');
        $data = $this->db->get('m_pemanfaatan')->result();
        if ($data) {
            echo "<option></option>";
            foreach ($data as $row) {
                echo "<option value='" . $row->ID_PEMANFAATAN . "'>" . $row->PEMANFAATAN . "</option>";
            }
        }
        exit;
    }

    function get_pasangan_anak($id = '') {  
		if ($id == NULL){
			$this->load->model('mlhkpn'); 
			$ID_PN = $this->session->userdata('ID_PN');
			$history = $this->mlhkpn->get_by_id_pn($ID_PN);
			$id_lhkpn = $history[0]->ID_LHKPN;
		}else{ 
			$this->load->model('mlhkpnharta');
			$history = $this->mlhkpnharta->get_by_id($id);
			$id_lhkpn = $history->result()[0]->ID_LHKPN;
		}
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->order_by('ID_KELUARGA', 'ASC');
        $data = $this->db->get('t_lhkpn_keluarga')->result();
        if ($data) {
            echo "<option></option>";
            foreach ($data as $row) {
                echo "<option value='" . $row->ID_KELUARGA . "'>" . $row->NAMA . "</option>";
            }
        }
        exit;
    }

    function get_pemanfaatan_with_data($golongan_harta) {

        $posted_data = $this->input->post('data');
        $arr_data = array();
        if ($posted_data) {
            $arr_data = explode(",", $posted_data);
        }

        $value = 'ID_PEMANFAATAN';
        $table = 'm_pemanfaatan';
        $object = 'PEMANFAATAN';
        $name = 'PEMANFAATAN';

        $ob = explode('x', $golongan_harta);
        if (count($ob) > 1) {
            $condition = str_replace('x', ', ', $golongan_harta);
            $this->db->like('GOLONGAN_HARTA', $condition);
        } else {
            $this->db->where('GOLONGAN_HARTA', $golongan_harta);
        }

        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        $i = 1;
        foreach ($data as $key_value => $row) {
            $property = str_replace(' ', '-', $row->$name);
            $property = strtolower($property);
            $label = strtoupper($row->$name);

//            $checked = !empty($arr_data) && in_array($row->$value, $arr_data) ? "checked=checked" : "";
            $checked = !empty($arr_data) && in_array(($key_value + 1), $arr_data) ? "checked=checked" : "";

            echo '
			   <tr>
                    <td><input type="'.($golongan_harta == 2 ? 'radio' : 'checkbox').'" class="pilih pilih-pemanfaatan" id="pilih-' . $property . '" name="' . $object . '[]" value="' . ($key_value + 1) . '" ' . $checked . ' /> ' . $i . '. ' . $label . '</td>
                    <td></td>
                    <td></td>
                </tr>
			';
            $i++;
        }
        exit;
    }

    function get_pemanfaatan($golongan_harta) {
        $value = 'ID_PEMANFAATAN';
        $table = 'm_pemanfaatan';
        $object = 'PEMANFAATAN';
        $name = 'PEMANFAATAN';

        $ob = explode('x', $golongan_harta);
        if (count($ob) > 1) {
            $condition = str_replace('x', ', ', $golongan_harta);
            $this->db->like('GOLONGAN_HARTA', $condition);
        } else {
            $this->db->where('GOLONGAN_HARTA', $golongan_harta);
        }

        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        $i = 1;
        foreach ($data as $row) {
            $property = str_replace(' ', '-', $row->$name);
            $property = strtolower($property);
            $label = strtoupper($row->$name);
            echo '
			   <tr>
                    <td><input type="checkbox" class="pilih pilih-pemanfaatan" id="pilih-' . $property . '" name="' . $object . '[]" value="' . $row->$value . '"/> ' . $i . '. ' . $label . '</td>
                    <td></td>
                    <td></td>
                </tr>
			';
            $i++;
        }
        exit;
    }

    function get_next_id($table) {
        $sql = "
			SELECT AUTO_INCREMENT
			FROM information_schema.tables
			WHERE table_name = '" . $table . "'
			AND table_schema = DATABASE( ) ;
		";
        $data = $this->db->query($sql)->row();
        return $data->AUTO_INCREMENT;
        exit;
    }

    function ATAS_NAMA($index) {
        $data = array();
        $data[1] = 'PN YANG BERSANGKUTAN';
        $data[2] = 'PASANGAN/ANAK';
        $data[3] = 'LAINNYA';
        return $data[$index];
        exit;
    }

    function GetNegara() {
        $key = $this->input->post_get('q');
        $this->db->limit(10);
        $this->db->where('ID !=', '2');
        $this->db->like('NAMA_NEGARA', $key);
        $this->db->order_by('NAMA_NEGARA', 'ASC');
        $result = $this->db->get('m_negara')->result();
        $array = array();
        foreach ($result as $row) {
            $array[] = array(
                'id' => $row->ID,
                'text' => $row->NAMA_NEGARA
            );
        }
        header('Content-type: application/json');
        echo json_encode($array);
        exit;
    }

    function IS_COPY($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn')->row();
        if ($data) {
            if ($data->IS_COPY == 1) {
                echo "<a id='" . $ID_LHKPN . "'  href='javascript:void(0)' class='btn btn-warning btn-sm updated-action'><i class='fa fa-refresh'></i> Load Data</a>";
            }
        }
        exit;
    }

    function STATUS_HARTA($id, $is_pelepasan) {
        $result = null;
        $data = array();
        $data[1] = '<label class="label label-primary">Tetap</label>';
        $data[2] = '<label class="label label-success">Ubah</label>';
        $data[3] = '<label class="label label-success">Baru</label>';
        if ($is_pelepasan == '1') {
            $result = '<label class="label label-inverse pelepasan">Lepas</label>';
        } else {
            $result = $data[$id];
        }
        return $result;
        exit;
    }

    function column($table) {
        $sql = "DESC " . $table;
        $data = $this->db->query($sql)->result();
        $arr = array();
        $i = 1;
        if ($data) {
            foreach ($data as $row) {
                if ($i > 1) {
                    $arr[] = $row->Field;
                }
                $i++;
            }
        }
        return $arr;
        exit;
    }

    // HARTA TIDAK BERGERAK

    private function __set_join_grid_harta_tidak_bergerak($TABLE, $PK) {
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = ' . $TABLE . '.ID_NEGARA ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");
        $this->db->group_by($TABLE . '.' . $PK);
    }

    function grid_harta_tidak_bergerak($ID_LHKPN) {

        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $sql_like = "";
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {

            $sql_like = " (JALAN LIKE '%" . $cari . "%' OR "
                    . " KEL LIKE '%" . $cari . "%' OR "
                    . " KEC LIKE '%" . $cari . "%' OR "
                    . " KAB_KOT LIKE '%" . $cari . "%' OR "
                    . " NAMA_NEGARA LIKE '%" . $cari . "%' OR "
                    . " LUAS_BANGUNAN LIKE '%" . $cari . "%' OR "
                    . " LUAS_TANAH LIKE '%" . $cari . "%' OR "
                    . " m_jenis_bukti.JENIS_BUKTI LIKE '%" . $cari . "%' OR "
                    . " NOMOR_BUKTI LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	`m_jenis_bukti`.*,
                `m_mata_uang`.*,
                `t_lhkpn`.*,
                `t_lhkpn`.`STATUS` `STATUS_LHKPN`,
                `t_lhkpn_harta_tidak_bergerak`.*,

                `t_lhkpn_harta_tidak_bergerak`.`STATUS` AS `STATUS_HARTA`,
                CASE 
                      WHEN `t_lhkpn_harta_tidak_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_tidak_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
                `m_jenis_bukti`.`JENIS_BUKTI` AS `JENIS_BUKTI_HARTA`
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_tidak_bergerak($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();

        if ($data) {
            foreach ($data as $list) {
                $i++;
                $this->db->where('ID', $list->ID_NEGARA);
                $ng = $this->db->get('m_negara')->row();
                if ($list->ID_NEGARA == '2') {
                    $JALAN = $list->JALAN;
                    $KEL = $list->KEL;
                    $KEC = $list->KEC;
                    $KOTA = $list->KAB_KOT;
                    $NEGARA = $ng->NAMA_NEGARA;
//                    $NEGARA = $list->aNEGARA;
                    $lokasi = "
						<table class='table table-child table-condensed'>
	                        <tr>
	                            <td><b>Jalan / No</b></td>
	                            <td>:</td>
	                            <td>" . $JALAN . "</td>
	                         </tr>
	                         <tr>
	                            <td><b>Kel / Desa</b></td>
	                            <td>:</td>
	                            <td>" . $KEL . "</td>
	                        </tr>
	                         <tr>
	                            <td><b>Kecamatan</b></td>
	                            <td>:</td>
	                            <td>" . $KEC . "</td>
	                        </tr>
	                         <tr>
	                            <td><b>Kab/Kota</b></td>
	                            <td>:</td>
	                            <td>" . $KOTA . "</td>
	                        </tr>
	                         <tr>
	                            <td><b>Prov / Negara</b></td>
	                            <td>:</td>
	                            <td>" . $list->PROV . ' / ' . $NEGARA . "</td>
	                        </tr>
	                    </table>
					";
                } else {
                    $JALAN = $list->JALAN;
                    $KEL = '';
                    $KEC = '';
                    $KOTA = '';
                    $NEGARA = $ng->NAMA_NEGARA;
                    $lokasi = "
					  <table class='table table-child table-condensed'>
					  	  <tr>
	                        <td><b>Jalan</b></td>
	                        <td>:</td>
	                        <td>" . $JALAN . "</td>
	                      </tr>
	                       <tr>
	                            <td><b>Negara</b></td>
	                            <td>:</td>
	                            <td>" . $NEGARA . "</td>
	                        </tr>
					  </table>
					";
                }

                $luas = "
					<table class='table table-child table-condensed'>
                        <tr>
                            <td><b>Tanah</b></td>
                            <td>:</td>
                            <td>" . str_replace('.', ',', $list->LUAS_TANAH) . " m <sup>2</sup></td>
                         </tr>
                         <tr>
                            <td><b>Bangunan</b></td>
                            <td>:</td>
                            <td>" . str_replace('.', ',', $list->LUAS_BANGUNAN) . " m <sup>2</sup></td>
                         </tr>
                    </table>
				";

                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result();
                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {
                        if ($asul) {
                            $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                        } else {
                            foreach ($asul as $a) {
                                $list_asul .= '' . $a->ASAL_USUL . ' , ';
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }


                $this->db->where_in('ID_PEMANFAATAN', explode(",", $list->PEMANFAATAN));
                $pm = $this->db->get('m_pemanfaatan')->result();
                $list_pm = NULL;
                if ($pm) {
                    if (count($pm) == 1) {
                        foreach ($pm as $p) {
                            $list_pm .= '' . $p->PEMANFAATAN . '  ';
                        }
                    } else {
                        foreach ($pm as $p) {
                            $list_pm .= '' . $p->PEMANFAATAN . ' , ';
                        }
                    }
                }

                
                $get_atas_nama = $list->ATAS_NAMA;
                $atas_nama = '';              
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $list->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$list->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$list->KET_LAINNYA.')' ;
                    }
                }
                
                $kepemilikan = "
					<table class='table table-child table-condensed'>
                        <tr>
                            <td><b>Jenis Bukti</b></td>
                            <td>:</td>
                            <td>" . $list->JENIS_BUKTI_HARTA . "</td>
                         </tr>
                         <tr>
                            <td><b>Nomor Bukti</b></td>
                            <td>:</td>
                            <td>" . $list->NOMOR_BUKTI . "</td>
                         </tr>
                         <tr>
                            <td><b>Atas Nama</b></td>
                            <td>:</td>

                            <td>" . $atas_nama . "</td>
                         </tr>
                         <tr>
                            <td><b>Asal Usul Harta</b></td>
                            <td>:</td>
                            <td>
                            	 " . $list_asul . "
                            </td>
                         </tr>
                         <tr>
                            <td><b>Pemanfaatan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list_pm . "
                            </td>
                         </tr>
                         <tr>
                            <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list->TAHUN_PEROLEHAN_AWAL . "
                            </td>
                         </tr>
                    </table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $lokasi,
                        $luas,
                        $kepemilikan,
                        'Rp. ' . number_rupiah($list->NILAI_PEROLEHAN) . '',
                        'Rp. ' . number_rupiah($list->NILAI_PELAPORAN) . '',
                        $list->STATUS_LHKPN == '0' || $list->STATUS_LHKPN == '2' && $list->entry_via == '0' ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);

        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_harta_tidak_bergerak($TABLE, $PK);
        $jml = $this->db->get($TABLE)->num_rows();
        /*
          //        display($this->db->last_query());exit;
          //        if ($cari){
          //            $query = "SELECT
          //                        COUNT(*) AS JML
          //                        FROM `t_lhkpn_harta_tidak_bergerak`
          //                        LEFT JOIN `m_jenis_bukti` ON `m_jenis_bukti`.`ID_JENIS_BUKTI` = `t_lhkpn_harta_tidak_bergerak`.`JENIS_BUKTI`
          //                        WHERE `t_lhkpn_harta_tidak_bergerak`.`IS_ACTIVE` = '1'
          //                        AND `t_lhkpn_harta_tidak_bergerak`.`ID_LHKPN` = '$ID_LHKPN' AND $sql_like
          //                        GROUP BY `t_lhkpn_harta_tidak_bergerak`.`ID` ORDER BY `ID` DESC";
          //        }else{
          //            $query = "SELECT
          //                        COUNT(*) AS JML
          //                        FROM `t_lhkpn_harta_tidak_bergerak`
          //                        LEFT JOIN `m_jenis_bukti` ON `m_jenis_bukti`.`ID_JENIS_BUKTI` = `t_lhkpn_harta_tidak_bergerak`.`JENIS_BUKTI`
          //                        WHERE `t_lhkpn_harta_tidak_bergerak`.`IS_ACTIVE` = '1'
          //                        AND `t_lhkpn_harta_tidak_bergerak`.`ID_LHKPN` = '$ID_LHKPN'
          //                        GROUP BY `t_lhkpn_harta_tidak_bergerak`.`ID` ORDER BY `ID` DESC";
          //        }
          //
          //        $query_cnt = $this->db->query($query);
          //        $jml_found = 0;
          //        if ($query_cnt) {
          //            $result = $query_cnt->row();
          //            if ($result) {
          //                $jml_found = $result->JML;
          //            }
          //        }
          //        $jml = $jml_found;
         */
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_harta_tidak_begerak($ID) {
        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = $this->pk($TABLE);
        $this->db->select('
			' . $TABLE . '.*,
			m_jenis_bukti.*,
			m_asal_usul.*,
			m_pemanfaatan.*,
			m_mata_uang.*,
			m_negara.*,
			m_area_prov.*,
			m_area_kab.*,
			' . $TABLE . '.ID AS REAL_ID,
			' . $TABLE . '.PEMANFAATAN AS ARR_PEMANFAATAN,
			' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
		');
        $this->db->where($TABLE . '.' . $PK, $ID);
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = ' . $TABLE . '.ASAL_USUL ', 'left');
        $this->db->join('m_pemanfaatan', 'm_pemanfaatan.ID_PEMANFAATAN = ' . $TABLE . '.PEMANFAATAN ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = ' . $TABLE . '.ID_NEGARA', 'LEFT');
        $this->db->join('m_area_prov', 'm_area_prov.NAME = ' . $TABLE . '.PROV', 'LEFT');
        $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = ' . $TABLE . '.KAB_KOT', 'LEFT');
        $result = $this->db->get($TABLE)->row();


        $this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result(); 

        $data = array(
            'result' => $result,
            'asal_usul' => $asal_usul
        );

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    function delete_condition($ID, $ID_HARTA_ASL) {
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
    }

    function __get_id_harta($TABLE_HARTA) {
        $this->db->select('ID_HARTA');
        $this->db->from($TABLE_HARTA);
        return $this->db->get()->num_rows();
    }

    function update_harta_tidak_bergerak() { 
        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');
        if ($this->input->post('NEGARA') == '1') {
            $ID_NEGARA = 2;
        } else {
            $ID_NEGARA = $this->input->post('ID_NEGARA');
        }


        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL'); 
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }


        $PEMANFAATAN_INPUT = $this->input->post('PEMANFAATAN');
        if (count($PEMANFAATAN_INPUT) > 1) {
            $PEMANFAATAN = join(',', $PEMANFAATAN_INPUT);
        } else {
            $PEMANFAATAN = $PEMANFAATAN_INPUT[0];
        }


        if (!$ID) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $ID);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

        $data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);
        
        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $this->input->post('ID_LHKPN'),
            'NEGARA' => $this->input->post('NEGARA'),
            'ID_NEGARA' => $ID_NEGARA,
            'JALAN' => $this->input->post('JALAN'),
            'KEL' => $this->input->post('KEL'),
            'KEC' => $this->input->post('KEC'),
            'KAB_KOT' => $this->get_form('m_area_kab', 'ID_KAB', $this->input->post('KAB_KOT'), 'NAME_KAB'),
            'PROV' => $this->get_form('m_area_prov', 'ID_PROV', $this->input->post('PROV'), 'NAME'),
            'LUAS_TANAH' => str_replace(',', '.', $this->input->post('LUAS_TANAH')),
            'LUAS_BANGUNAN' => str_replace(',', '.', $this->input->post('LUAS_BANGUNAN')),
            'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI'),
            'NOMOR_BUKTI' => $this->input->post('NOMOR_BUKTI'),

            'ATAS_NAMA' => $data_atas_nama,
            'ASAL_USUL' => $ASAL_USUL,
            'PEMANFAATAN' => $PEMANFAATAN,
            'KET_LAINNYA' => $this->input->post('KET_LAINNYA_AN'),
            'PASANGAN_ANAK' => $data_pasangan_anak,
//             'PASANGAN_ANAK' => str_replace('"",', '',json_encode($this->input->post('PASANGAN_ANAK'))),
            //'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            //'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR'),
            'MATA_UANG' => 1,
            'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN'),
            'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            'IS_ACTIVE' => 1,
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );

        $ID_HARTA_ASL = $this->do_update($TABLE, $data, $ID);

        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_tidak_bergerak');

        if ($id_data_harta != 0) {
            $this->delete_condition($ID, $ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_tidak_bergerak');
        }

        $this->delete_condition($ID, $ID_HARTA_ASL);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak');
        if ($ID_HARTA_ASL != 0) {
            if ($ASAL_USUL_INPUT) {
                foreach ($ASAL_USUL_INPUT as $asl) {
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $ID_HARTA_ASL,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        );
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak', $asl_array);
                    }
                }
            }
            echo "1";
        } else {
            echo "0";
        }
    }

    // HARTA BERGERAK
    private function __set_join_grid_harta_bergerak($TABLE, $PK) {
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");

        $this->db->group_by($TABLE . '.' . $PK);
    }

    function grid_harta_bergerak($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_bergerak';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $sql_like = "";
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {

            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " MEREK LIKE '%" . $cari . "%' OR "
                    . " MODEL LIKE '%" . $cari . "%' OR "
                    . " TAHUN_PEMBUATAN LIKE '%" . $cari . "%' OR "
                    . " NOPOL_REGISTRASI LIKE '%" . $cari . "%' OR "
                    . " m_jenis_bukti.JENIS_BUKTI LIKE '%" . $cari . "%' OR "
                    . " KET_LAINNYA LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	m_jenis_harta.*,
        	m_jenis_bukti.*,
        	t_lhkpn_harta_bergerak.*,
        	m_jenis_harta.NAMA AS JENIS_HARTA,
        	t_lhkpn_harta_bergerak.STATUS as STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_bukti.JENIS_BUKTI as JENIS_BUKTI_VALUE,
        	t_lhkpn.*
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_bergerak($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
//        echo $this->db->last_query();exit;
        if ($data) {
            foreach ($data as $list) {
                $i++;
                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_bergerak.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_bergerak')->result();
                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {
                        if ($asul) {
                            $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                        } else {
                            foreach ($asul as $a) {
                                $list_asul .= '' . $a->ASAL_USUL . ' , ';
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }

                $this->db->where_in('ID_PEMANFAATAN', explode(",", $list->PEMANFAATAN));
                $pm = $this->db->get('m_pemanfaatan')->result();
                $list_pm = NULL;
                if ($pm) {
                    if (count($pm) == 1) {
                        foreach ($pm as $p) {
                            $list_pm .= '' . $p->PEMANFAATAN . '';
                        }
                    } else {
                        foreach ($pm as $p) {
                            $list_pm .= '' . $p->PEMANFAATAN . ' , ';
                        }
                    }
                }
                $uraian = "
					<table class='table table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->JENIS_HARTA . "</td>
						 </tr>
						 <tr>
						    <td><b>Merek</b></td>
                            <td>:</td>
                            <td>" . $list->MEREK . "</td>
						 </tr>
						 <tr>
						    <td><b>Tipe/Model</b></td>
                            <td>:</td>
                            <td>" . $list->MODEL . "</td>
						 </tr>
						 <tr>
						    <td><b>Tahun Pembuatan</b></td>
                            <td>:</td>
                            <td>" . $list->TAHUN_PEMBUATAN . "</td>
						 </tr>
						 <tr>
						    <td><b>No Pol / Registrasi</b></td>
                            <td>:</td>
                            <td>" . $list->NOPOL_REGISTRASI . "</td>
						 </tr>
					</table>
				";
    
                
                $get_atas_nama = $list->ATAS_NAMA;
                $atas_nama = '';
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $list->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$list->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$list->KET_LAINNYA.')' ;
                    }
                }
                
                
                
                $kepemilikan = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Jenis Bukti</b></td>
                            <td>:</td>
                            <td>" . $list->JENIS_BUKTI_VALUE . "</td>
						 </tr>
						 <tr>
						    <td><b>Asal Usul Harta</b></td>
                            <td>:</td>
                            <td>" . $list_asul . "</td>
						 </tr>
						 <tr>
						    <td><b>Atas Nama</b></td>
                            <td>:</td>

                            <td>" . $atas_nama . "</td>
						 </tr>
						 <tr>
						    <td><b>Pemanfaatan</b></td>
                            <td>:</td>
                            <td>" . $list_pm . "</td>
						 </tr>
						 <tr>
						    <td><b>Ket. Lainnya</b></td>
                            <td>:</td>
                            <td>" . $list->KET_LAINNYA . "</td>
						 </tr>
                         <tr>
                            <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list->TAHUN_PEROLEHAN_AWAL . "
                            </td>
                         </tr>
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $uraian,
                        $kepemilikan,
                        'Rp. ' . number_rupiah($list->NILAI_PEROLEHAN) . '',
                        'Rp. ' . number_rupiah($list->NILAI_PELAPORAN) . '',
                        $list->STATUS == 0 || $list->STATUS == 2 && $list->entry_via == '0' ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_harta_bergerak($TABLE, $PK);
        $jml = $this->db->get($TABLE)->num_rows();
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_harta_begerak($ID) {
        $TABLE = 't_lhkpn_harta_bergerak';
        $PK = $this->pk($TABLE);
        $this->db->select('
			' . $TABLE . '.*,
			m_jenis_bukti.*,
			m_jenis_harta.*,
			m_pemanfaatan.*,
			' . $TABLE . '.ID AS REAL_ID,
			' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
		');
        $this->db->where($PK, $ID);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_pemanfaatan', 'm_pemanfaatan.ID_PEMANFAATAN = ' . $TABLE . '.PEMANFAATAN ', 'left');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $result = $this->db->get($TABLE)->row();
        $this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_bergerak.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_bergerak')->result();
        $data = array(
            'result' => $result,
            'asal_usul' => $asal_usul
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function update_harta_bergerak() {

        $TABLE = 't_lhkpn_harta_bergerak';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');


        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL');
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }


        /* $PEMANFAATAN_INPUT = $this->input->post('PEMANFAATAN');
          if(count($PEMANFAATAN_INPUT)>1){
          $PEMANFAATAN = join(',', $PEMANFAATAN_INPUT);
          }else{
          $PEMANFAATAN = $PEMANFAATAN_INPUT[0];
          } */

        if (!$ID) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $ID);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

        $data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);
        
        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'MEREK' => $this->input->post('MEREK'),
            'MODEL' => $this->input->post('MODEL'),
            'TAHUN_PEMBUATAN' => $this->input->post('TAHUN_PEMBUATAN'),
            'NOPOL_REGISTRASI' => $this->input->post('NOPOL_REGISTRASI'),
            'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI'),
            'NOMOR_BUKTI' => $this->input->post('NOMOR_BUKTI'),
            'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            'ATAS_NAMA' => $data_atas_nama,
            'PASANGAN_ANAK' => $data_pasangan_anak,
            'KET_LAINNYA' => $this->input->post('KET_LAINNYA_AN'),
            'ASAL_USUL' => $ASAL_USUL,
            'PEMANFAATAN' => $this->input->post('PEMANFAATAN'),
            'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            
            
            'MATA_UANG' => 1,
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED,
            'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'IS_ACTIVE' => 1
        );

        $ID_HARTA_ASL = $this->do_update($TABLE, $data, $ID);
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_bergerak');

        if ($id_data_harta != 0) {
            $this->delete_condition($ID, $ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_bergerak');
        }
        $this->delete_condition($ID, $ID_HARTA_ASL);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_bergerak');
        if ($ID_HARTA_ASL != 0) {
            if ($ASAL_USUL_INPUT) {
                foreach ($ASAL_USUL_INPUT as $asl) {
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $ID_HARTA_ASL,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        );
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_bergerak', $asl_array);
                    }
                }
            }
            echo "1";
        } else {
            echo "0";
        }
    }

    // HARTA BERGERAK LAIN
    private function __set_join_grid_harta_bergerak_lain($TABLE, $PK) {
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_bergerak_lain.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");

        $this->db->group_by($TABLE . '.' . $PK);
    }

    function grid_harta_bergerak_Lain($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_bergerak_lain';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $sql_like = "";
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {
            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " JUMLAH LIKE '%" . $cari . "%' OR "
                    . " SATUAN LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_bergerak_lain.*,
        	t_lhkpn_harta_bergerak_lain.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_bergerak_lain`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_bergerak_lain`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_harta.NAMA as JENIS_HARTA,
        	t_lhkpn.*
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_bergerak_lain($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
//        echo $this->db->last_query();exit;
        if ($data) {
            foreach ($data as $list) {
                $i++;
                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain')->result();
                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {
                        if ($asul) {
                            $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                        } else {
                            foreach ($asul as $a) {
                                $list_asul .= '' . $a->ASAL_USUL . ' , ';
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }


                $uraian = "
					<table class='table table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->JENIS_HARTA . "</td>
						 </tr>
						 <tr>
						    <td><b>Jumlah</b></td>
                            <td>:</td>
                            <td>" . $list->JUMLAH . "</td>
						 </tr>
						 <tr>
						    <td><b>Satuan</b></td>
                            <td>:</td>
                            <td>" . $list->SATUAN . "</td>
						 </tr>
						 <tr>
						    <td><b>Ket Lainnya</b></td>
                            <td>:</td>
                            <td>" . $list->KETERANGAN . "</td>
						 </tr>
                         <tr>
                            <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list->TAHUN_PEROLEHAN_AWAL . "
                            </td>
                         </tr>
					</table>
				";
                $kepemilikan = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Asal Usul Harta</b></td>
                            <td>:</td>
                            <td>" . $list_asul . "</td>
						 </tr>
                         
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $uraian,
                        $kepemilikan,
                        'Rp. ' . number_rupiah($list->NILAI_PEROLEHAN) . '',
                        'Rp. ' . number_rupiah($list->NILAI_PELAPORAN) . '',
                        $list->STATUS == 0 || $list->STATUS == 2 && $list->entry_via == '0' ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_harta_bergerak_lain($TABLE, $PK);
        $jml = $this->db->get($TABLE)->num_rows();
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_harta_begerak_Lain($ID) {
        $TABLE = 't_lhkpn_harta_bergerak_lain';
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $this->db->select('
			' . $TABLE . '.*,
			m_jenis_harta.*,
			' . $TABLE . '.ID AS REAL_ID,
			' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
		');
        $this->db->where($PK, $ID);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS', 'LEFT');
        $result = $this->db->get($TABLE)->row();
        $this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain')->result();
        $data = array(
            'result' => $result,
            'asal_usul' => $asal_usul
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function update_harta_bergerak_Lain() {
        $TABLE = 't_lhkpn_harta_bergerak_lain';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');



        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL');
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }


        if (!$ID) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $ID);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }


        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'JUMLAH' => $this->input->post('JUMLAH'),
            'SATUAN' => $this->input->post('SATUAN'),
            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'ASAL_USUL' => $ASAL_USUL,
            'MATA_UANG' => 1,
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED,
            'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'IS_ACTIVE' => 1
        );

        $ID_HARTA_ASL = $this->do_update($TABLE, $data, $ID);
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_bergerak_lain');

        if ($id_data_harta != 0) {
            $this->delete_condition($ID, $ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_bergerak_lain');
        }
        $this->delete_condition($ID, $ID_HARTA_ASL);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain');
        if ($ID_HARTA_ASL != 0) {
            if ($ASAL_USUL_INPUT) {
                foreach ($ASAL_USUL_INPUT as $asl) {
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $ID_HARTA_ASL,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        );
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain', $asl_array);
                    }
                }
            }
            echo "1";
        } else {
            echo "0";
        }
    }

    function grid_surat_berharga($ID_LHKPN) {

        $this->load->model('m_data_harta_surat_berharga');

        $TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $sql_like = '';

        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }

        $ID_PN = $this->session->userdata('ID_PN');

        $data = $this->m_data_harta_surat_berharga->get_data($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari);

//        echo $this->db->last_query();


        $jml = $this->m_data_harta_surat_berharga->count_num_rows($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari);

//        echo $this->db->last_query();exit;

        if ($data) {
            foreach ($data as $list) {
                $i++;

                $asul = $this->m_data_harta_surat_berharga->get_asal_usul_by_id($list->ID);
                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {

                        if (!empty($asul)) {

                            if ($asul) {
                                $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                            } else {
                                foreach ($asul as $a) {
                                    $list_asul .= '' . $a->ASAL_USUL . ' , ';
                                }
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul && !empty($asul)) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }
                $img = null;
                if ($list->FILE_BUKTI) {

                    $filelist = explode(',', $list->FILE_BUKTI);
                    
                    $dir = null;
                    
                    foreach ($filelist as $key => $tmp_name) {
                        //if (empty($tmp_name)) {
                            
                        if ($key==0) {
                            $dt = explode("/", $tmp_name);
                            $c = count($dt);
                            for($i=0; $i<$c-1; $i++) {
                                $dir = $dir . $dt[$i] . "/";
                            }
                            $tmp_name = $dt[$i++];
                        }

                        if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                                $tmp_name = $dir . $tmp_name;
                                $img = $img . " <a class='files' target='_blank' href='" . base_url() . '' . $tmp_name . "'><i class='fa fa-file-text fa-2x'></i></a>";
                            }
                            
                        //}
                    }
                    //if (file_exists($list->FILE_BUKTI)) {
                        
                    //$img = "<a id='" . $list->ID . "'  href='javascript:void(0)' onclick=\"showDocument('" . base_url() . '' . $list->FILE_BUKTI . "')\" class='files' title='Document'><i class='fa fa-file-text fa-2x'></i></a>";
                    
                    //}
                }

                $get_atas_nama = $list->ATAS_NAMA;
                $atas_nama = '';
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $list->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$list->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$list->KET_LAINNYA.')' ;
                    }
                }
                
                
                
                $uraian = "
					<table class='table table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA . "" . $img . "</td>
						 </tr>
						  <tr>
						    <td><b>Atas Nama</b></td>
                            <td>:</td>

                            <td>" . $atas_nama . "</td>
						 </tr>
						  <tr>
						    <td><b>Penerbit / Perusahaan</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA_PENERBIT . "</td>
						 </tr>
						  <tr>
						    <td><b>Custodion / Sekuritas</b></td>
                            <td>:</td>
                             <td>" . $list->CUSTODIAN . "</td>
						 </tr>
                         <tr>
                            <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list->TAHUN_PEROLEHAN_AWAL . "
                            </td>
                         </tr>
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $uraian,
                        $list->NOMOR_REKENING,
                        $list_asul,
                        'Rp. ' . number_rupiah($list->NILAI_PEROLEHAN) . '',
                        'Rp. ' . number_rupiah($list->NILAI_PELAPORAN) . '',
//                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($this->m_data_harta_surat_berharga->PK, $this->m_data_harta_surat_berharga->TABLE, $list->IS_COPY) : ''
                        $list->STATUS_LHKPN == 0 || $list->STATUS_LHKPN == 2 && $list->entry_via == '0' ? $this->action($list->ID, $this->m_data_harta_surat_berharga->TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }


        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_surat_berharga($ID) {
        $TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $this->db->select('
			' . $TABLE . '.*,
			m_jenis_harta.*,
			' . $TABLE . '.ID AS REAL_ID,
			' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
		');
        $this->db->where($PK, $ID);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $result = $this->db->get($TABLE)->row();
        $this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_surat_berharga.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_surat_berharga')->result();
        $data = array(
            'result' => $result,
            'asal_usul' => $asal_usul
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function update_surat_berharga() {
        $TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');



        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL');
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }

        if (!$ID) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $ID);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

        $FILE_BUKTI = NULL;
        if ($ID) {
            $this->db->where($PK, $ID);
            $temp = $this->db->get($TABLE)->row();
            $upload = $this->upload_surat_berharga();
            if ($upload['upload']) {
                if ($temp) {
                    if ($temp->FILE_BUKTI) {
                        unlink($temp->FILE_BUKTI);
                        $FILE_BUKTI = $upload['url'];
                    } else {
                        $FILE_BUKTI = $upload['url'];
                    }
                }
            } else {
                $FILE_BUKTI = $temp->FILE_BUKTI;
            }
        } else {
            $upload = $this->upload_surat_berharga();
            if ($upload['upload']) {
                $FILE_BUKTI = $upload['url'];
            }
        }

        
        $data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);
        //         'ATAS_NAMA' => $data_atas_nama,
        //         'PASANGAN_ANAK' => $data_pasangan_anak,
        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING'),

            'ATAS_NAMA' => $data_atas_nama,
            'PASANGAN_ANAK' => $data_pasangan_anak,
            'ASAL_USUL' => $ASAL_USUL,
            'STATUS' => $STATUS,
            'NAMA_PENERBIT' => $this->input->post('NAMA_PENERBIT'),
            'MATA_UANG' => 1,
            'CUSTODIAN' => $this->input->post('CUSTODIAN'),
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED,
            'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'FILE_BUKTI' => $FILE_BUKTI,
            'IS_ACTIVE' => 1,
            'KET_LAINNYA' => $this->input->post('KET_LAINNYA_AN')
        );
        $ID_HARTA_ASL = $this->do_update($TABLE, $data, $ID);
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_surat_berharga');

        if ($id_data_harta != 0) {
            $this->delete_condition($ID, $ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_surat_berharga');
        }
        $this->delete_condition($ID, $ID_HARTA_ASL);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_surat_berharga');
        if ($ID_HARTA_ASL != 0) {
            if ($ASAL_USUL_INPUT) {
                foreach ($ASAL_USUL_INPUT as $asl) {
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $ID_HARTA_ASL,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        );
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_surat_berharga', $asl_array);
                    }
                }
            }
            echo "1";
        } else {
            echo "0";
        }
    }

    function upload_surat_berharga() {
        $result = array();
        $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
        if (!file_exists('uploads/data_suratberharga/' . $folder)) {
            mkdir('uploads/data_suratberharga/' . $folder);
            $content = "Bukti Surat Berharga Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_suratberharga/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
            /* IBO UPDATE */
        }
        
        $rst = false;
        $urllist = 'uploads/data_suratberharga/' . $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);

            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {

                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);
        
        
//         $total = count($_FILES['file1']['name']);
        
//         // Loop through each file
//         for($i=0; $i<$total; $i++) {
//             //Get the temp file path
//             $tmpFilePath = $_FILES['file1']['tmp_name'][$i];
            
//             //Make sure we have a filepath
//             if ($tmpFilePath != ""){
//                 //Setup our new file path
//                 $uploaddir = 'uploads/data_suratberharga/' .  $_FILES['file1']['name'][$i];
                
//                 //Upload the file into the temp dir
//                 if(move_uploaded_file($tmpFilePath, $uploaddir)) {
                    
//                     //Handle other code here
//                     $result = array('upload' => true, 'url' => $uploaddir);
//                 }
//             }
//         }
        
//         $tmpFilePath = $_FILES['file1']['tmp_name'];
        
//         //Make sure we have a filepath
//         if ($tmpFilePath != ""){
//             //Setup our new file path
//             $uploaddir = 'uploads/data_suratberharga/' . $total . '.'. $_FILES['file1']['name'];
            
//             //Upload the file into the temp dir
//             if(move_uploaded_file($tmpFilePath, $uploaddir)) {
                
//                 //Handle other code here
//                 $result = array('upload' => true, 'url' => $uploaddir);
//             }
//         }
        
//             if (isset($_FILES["file1"])) {
//                 foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file1']['name'][$key])));
//                     $file_name = $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         } else {
//                             $result = array('upload' => false, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* IBO UPDATE */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file1']['name'][$key])));
//                     $file_name = $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         } else {
//                             $result = array('upload' => false, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
        //}
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

    // KAS
    private function __set_join_grid_kas($TABLE, $PK) {
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");
    }

    function grid_kas($ID_LHKPN) {


        $this->load->model('m_data_harta_kas');

        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $sql_like = "";
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }

        $ID_PN = $this->session->userdata('ID_PN');
        $data = $this->m_data_harta_kas->get_data($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari);

        $jml = $this->m_data_harta_kas->count_num_rows($ID_LHKPN, $ID_PN, $iDisplayLength, $iDisplayStart, $cari);


        if ($data) {
            foreach ($data as $list) {
                $i++;

                $asul = $this->m_data_harta_kas->get_asal_usul_by_id($list->ID);

                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {
                        if (!empty($asul)) {
                            if ($asul) {
                                $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                            } else {
                                foreach ($asul as $a) {
                                    $list_asul .= '' . $a->ASAL_USUL . ' , ';
                                }
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul && !empty($asul)) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }

                $img = null;
                if ($list->FILE_BUKTI) {
                    
                    $filelist = explode(',', $list->FILE_BUKTI);
                    
                    $dir = null;
                    
                    foreach ($filelist as $key => $tmp_name) {
                        //if (empty($tmp_name)) {
                        
                        if ($key==0) {
                            $dt = explode("/", $tmp_name);
                            $c = count($dt);
                            for($i=0; $i<$c-1; $i++) {
                                $dir = $dir . $dt[$i] . "/";
                            }
                            $tmp_name = $dt[$i++];
                        }
                        
                        if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                            $tmp_name = $dir . $tmp_name;
                            $img = $img . " <a class='files' target='_blank' href='" . base_url() . '' . $tmp_name . "'><i class='fa fa-file-text fa-2x'></i></a>";
                        } 
                    } 
                }
                
                
//                 $image = null;
//                 /* IBO UPDATE */
//                 $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
//                 $folderLama = $this->session->userdata('USERNAME');
//                 //var_dump('uploads/data_kas/' . $folder);exit;
//                 if (!file_exists('uploads/data_kas/' . $folder)) {
//                     mkdir('uploads/data_kas/' . $folder);
//                     $x=$this->PindahFile('uploads/data_kas/' . $folderLama,'uploads/data_kas/' . $folder);
//                 }
//                 //var_dump($list->FILE_BUKTI);exit;
//                 if ($list->FILE_BUKTI) {
//                     if (file_exists($list->FILE_BUKTI)) {
//                         $image = ' <a class="files" target="_blank" href="' . base_url() . '' . $list->FILE_BUKTI . '"><i class="fa fa-file-text fa-2x"></i></a>';
//                     }
//                 }
                    

                $uraian = "
					<table class='table table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA . " " . $img . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                              <td>-</td>
						 </tr>
						 <tr>
						    <td><b>Nama Bank / Lembaga</b></td>
                            <td>:</td>
                           <td>" . $list->NAMA_BANK . "</td>
						 </tr>
					</table>
				";

                
                
                
                $get_atas_nama = $list->ATAS_NAMA_REKENING;
                $atas_nama = '';
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $list->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$list->ATAS_NAMA_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$list->ATAS_NAMA_LAINNYA.')' ;
                    }
                }
                
                
                
                $info_rekening = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Nomor</b></td>
                            <td>:</td>
                            <td>" . $list->NOMOR_REKENING . "</td>
						 </tr>
						 <tr>
						    <td><b>Atas Nama</b></td>
                            <td>:</td>

                            <td>" . $atas_nama . "</td>
						 </tr>
						 <!--<tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $list->KETERANGAN . "</td>
						 </tr>-->
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $uraian,
                        $info_rekening,
                        $list_asul,
                        'Rp. ' . number_rupiah($list->NILAI_EQUIVALEN) . '',
                        $list->STATUS_LHKPN == 0 || $list->STATUS_LHKPN == 2 && $list->entry_via == '0' ? $this->action($list->ID, $this->m_data_harta_kas->TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }

        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_kas($ID) {
        $TABLE = 't_lhkpn_harta_kas';
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $this->db->select('
			' . $TABLE . '.*,
			m_jenis_harta.*,
			' . $TABLE . '.ID AS REAL_ID,
			' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
		');
        $this->db->where($PK, $ID);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $result = $this->db->get($TABLE)->row();
        $this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_kas.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_kas')->result();
        $data = array(
            'result' => $result,
            'asal_usul' => $asal_usul
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function update_kas() {
        $TABLE = 't_lhkpn_harta_kas';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');



        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL');
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }

        if (!$ID) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $ID);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }

        $FILE_BUKTI = NULL;
        if ($ID) {
            $this->db->where($PK, $ID);
            $temp = $this->db->get($TABLE)->row();
            $upload = $this->upload_kas();
            if ($upload['upload']) {
                if ($temp) {
                    if ($temp->FILE_BUKTI) {
                        unlink($temp->FILE_BUKTI);
                        $FILE_BUKTI = $upload['url'];
                    } else {
                        $FILE_BUKTI = $upload['url'];
                    }
                }
            } else {
                $FILE_BUKTI = $temp->FILE_BUKTI;
            }
            //print_r($upload);
        } else {
            $upload = $this->upload_kas();
            if ($upload['upload']) {
                $FILE_BUKTI = $upload['url'];
            }
            //print_r($upload);
        }


        $data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);

        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'ASAL_USUL' => $ASAL_USUL,
            'NAMA_BANK' => $this->input->post('NAMA_BANK'),
            'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING'),
            'PASANGAN_ANAK' => $data_pasangan_anak,
            'ATAS_NAMA_REKENING' => $data_atas_nama,
            'MATA_UANG' => $this->input->post('MATA_UANG'),
            'NILAI_SALDO' => intval(str_replace('.', '', $this->input->post('NILAI_SALDO'))),
            'NILAI_EQUIVALEN' => intval(str_replace('.', '', $this->input->post('NILAI_EQUIVALEN'))),
            'IS_ACTIVE' => 1,
//            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'NILAI_KURS' => $this->input->post('NIlAI_KURS'),
            'KETERANGAN' => $this->input->post('KET_LAINNYA_AN'),
            'ATAS_NAMA_LAINNYA' => $this->input->post('KET_LAINNYA_AN'),
            'STATUS' => $STATUS,
            'FILE_BUKTI' => $FILE_BUKTI,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'TAHUN_BUKA_REKENING' => $this->input->post('TAHUN_BUKA_REKENING'),
            'IS_CHECKED' => $IS_CHECKED,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );

        $ID_HARTA_ASL = $this->do_update($TABLE, $data, $ID);
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_kas');

        if ($id_data_harta != 0) {
            $this->delete_condition($ID, $ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_kas');
        }
        $this->delete_condition($ID, $ID_HARTA_ASL);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_kas');
        if ($ID_HARTA_ASL != 0) {
            if ($ASAL_USUL_INPUT) {
                foreach ($ASAL_USUL_INPUT as $asl) {
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $ID_HARTA_ASL,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        );
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_kas', $asl_array);
                    }
                }
            }
            echo "1";
        } else {
            echo "0";
        }
    }

    function upload_kas() {
        $result = array();
        $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');

        if (!file_exists('uploads/data_kas/' . $folder)) {
            mkdir('uploads/data_kas/' . $folder);
            $content = "Bukti Kas Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
            $fp = fopen(FCPATH . "/uploads/data_kas/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);

            /* --- IBO ADD -- */
        }
            $rst = false;
            $urllist = 'uploads/data_kas/' . $folder . '/';
            foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
                $time = time();
                $ext = end((explode(".", $_FILES['file1']['name'][$key])));
                $file_name = $key . $time . '.' . $ext;
                $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                $urllist = $urllist . $file_name . ',';
                $uploadext = '.' . strtolower($ext);

                if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                    $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
                }
            }
            $result = array('upload' => $rst, 'url' => $urllist);
            
            
            
            
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* ---End IBO ADD -- */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

    // HARTA LAINNYA 
    private function __set_join_grid_harta_lainnya($TABLE, $PK) {
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");

        $this->db->group_by($TABLE . '.' . $PK);
    }

    function grid_harta_Lainnya($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_lainnya';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $sql_like = '';
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');

        if ($cari) {

            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_lainnya.*,
        	t_lhkpn_harta_lainnya.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_lainnya`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_lainnya`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_harta.NAMA AS NAMA_JENIS,
        	t_lhkpn.*
       	');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_lainnya($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
//        echo $this->db->last_query();exit;
        if ($data) {
            foreach ($data as $list) {
                $i++;
                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_lainnya.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_lainnya')->result();
                $list_asul = NULL;
                $c_asl = explode(',', $list->ASAL_USUL);
                if ((int) count($c_asl) == 1) {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI';
                    } else {
                        if ($asul) {
                            $list_asul .= '' . $asul[0]->ASAL_USUL . ' ';
                        } else {
                            foreach ($asul as $a) {
                                $list_asul .= '' . $a->ASAL_USUL . ' , ';
                            }
                        }
                    }
                } else {
                    if ($c_asl[0] == '1') {
                        $list_asul .= 'HASIL SENDIRI , ';
                    }
                    if ($asul) {
                        foreach ($asul as $a) {
                            $list_asul .= '' . $a->ASAL_USUL . ' , ';
                        }
                    }
                }

                $img = null;
                if ($list->FILE_BUKTI) {
                    
                    $filelist = explode(',', $list->FILE_BUKTI);
                    
                    $dir = null;
                    
                    foreach ($filelist as $key => $tmp_name) {
                        //if (empty($tmp_name)) {
                        
                        if ($key==0) {
                            $dt = explode("/", $tmp_name);
                            $c = count($dt);
                            for($i=0; $i<$c-1; $i++) {
                                $dir = $dir . $dt[$i] . "/";
                            }
                            $tmp_name = $dt[$i++];
                        }
                        
                        if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                            $tmp_name = $dir . $tmp_name;
                            $img = $img . " <a class='files' target='_blank' href='" . base_url() . '' . $tmp_name . "'><i class='fa fa-file-text fa-2x'></i></a>";
                        }
                    }
                }
                
//                 $img = null;
//                 if ($list->FILE_BUKTI) {
//                     if (file_exists($list->FILE_BUKTI)) {
//                         $img = " <a class='files' target='_blank' href='" . base_url() . '' . $list->FILE_BUKTI . "'><i class='fa fa-file-text fa-2x'></i></a>";
//                     }
//                 }

                $uraian = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA_JENIS . "" . $img . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $list->KETERANGAN . "</td>
						 </tr>
                         <tr>
                            <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>
                            	" . $list->TAHUN_PEROLEHAN_AWAL . "
                            </td>
                         </tr>
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $uraian,
                        $list_asul,
                        'Rp. ' . number_rupiah($list->NILAI_PEROLEHAN) . '',
                        'Rp. ' . number_rupiah($list->NILAI_PELAPORAN) . '',
                        $list->STATUS == 0 || $list->STATUS == 2 && $list->entry_via == '0' ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_harta_lainnya($TABLE, $PK);
        $jml = $this->db->get($TABLE)->num_rows();
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_harta_Lainnya($ID) {
        $TABLE = 't_lhkpn_harta_lainnya';
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $this->db->select('
			' . $TABLE . '.*,
			m_jenis_harta.*,
			' . $TABLE . '.ID AS REAL_ID,
			' . $TABLE . '.ASAL_USUL AS ARR_ASAL_USUL,
		');
        $this->db->where($PK, $ID);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $result = $this->db->get($TABLE)->row();
        $this->db->where('ID_HARTA', $ID);
        $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_harta_lainnya.ID_ASAL_USUL');
        $asal_usul = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_lainnya')->result();
        $data = array(
            'result' => $result,
            'asal_usul' => $asal_usul
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function update_harta_Lainnya() {
        $TABLE = 't_lhkpn_harta_lainnya';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');



        $ASAL_USUL_INPUT = $this->input->post('ASAL_USUL');
        if (count($ASAL_USUL_INPUT) > 1) {
            $ASAL_USUL = join(',', $ASAL_USUL_INPUT);
        } else {
            $ASAL_USUL = $ASAL_USUL_INPUT[0];
        }


        if (!$ID) { // BUAT BARU DARI AWAL
            $ID_HARTA = NULL;
            $STATUS = '3';
            $IS_PELEPASAN = '0';
            $IS_CHECKED = '0';
        } else { // EDIT DATA
            $this->db->where($PK, $ID);
            $check = $this->db->get($TABLE)->row();
            if ($check->ID_HARTA) { // LAPORAN KE 2,3, dst...
                $ID_HARTA = $check->ID_HARTA;
                $STATUS = '2';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '1';
            } else {
                $ID_HARTA = NULL;
                $STATUS = '3';
                $IS_PELEPASAN = '0';
                $IS_CHECKED = '0';
            }
        }
        
        $FILE_BUKTI = NULL;
        if ($ID) {
            $this->db->where($PK, $ID);
            $temp = $this->db->get($TABLE)->row();
            $upload = $this->upload_harta_lainnya();
            if ($upload['upload']) {
                if ($temp) {
                    if ($temp->FILE_BUKTI) {
                        unlink($temp->FILE_BUKTI);
                        $FILE_BUKTI = $upload['url'];
                    } else {
                        $FILE_BUKTI = $upload['url'];
                    }
                }
            } else {
                $FILE_BUKTI = $temp->FILE_BUKTI;
            }
        } else {
            $upload = $this->upload_harta_lainnya();
            if ($upload['upload']) {
                $FILE_BUKTI = $upload['url'];
            }
        }

        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'ASAL_USUL' => $ASAL_USUL,
            'MATA_UANG' => 1,
            'STATUS' => $STATUS,
            'IS_PELEPASAN' => $IS_PELEPASAN,
            'IS_CHECKED' => $IS_CHECKED,
            'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'FILE_BUKTI' => $FILE_BUKTI,
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
            'IS_ACTIVE' => 1
        );

        $ID_HARTA_ASL = $this->do_update($TABLE, $data, $ID);
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_lainnya');

        if ($id_data_harta != 0) {
            $this->delete_condition($ID, $ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_lainnya');
        }
        $this->delete_condition($ID, $ID_HARTA_ASL);
        $this->db->delete('t_lhkpn_asal_usul_pelepasan_harta_lainnya');
        if ($ID_HARTA_ASL != 0) {
            if ($ASAL_USUL_INPUT) {
                foreach ($ASAL_USUL_INPUT as $asl) {
                    if ($asl > 1) {
                        $TANGGAL_TRANSAKSI = $this->input->post('asal_tgl_transaksi');
                        $NAMA = $this->input->post('asal_pihak2_nama');
                        $ALAMAT = $this->input->post('asal_pihak2_alamat');
                        $beasar_nilai = $this->input->post('asal_besar_nilai');
                        $keterangan = $this->input->post('asal_keterangan');
                        $TANGGAL = date('Y-m-d', strtotime(str_replace('/', '-', $TANGGAL_TRANSAKSI[$asl])));
                        $asl_array = array(
                            'ID_HARTA' => $ID_HARTA_ASL,
                            'ID_ASAL_USUL' => $asl,
                            'TANGGAL_TRANSAKSI' => $TANGGAL,
                            'NAMA' => $NAMA[$asl],
                            'ALAMAT' => $ALAMAT[$asl],
                            'URAIAN_HARTA' => $keterangan[$asl],
                            'NILAI_PELEPASAN' => intval(str_replace('.', '', $beasar_nilai[$asl])),
                            'CREATED_TIME' => date("Y-m-d H:i:s"),
                            'CREATED_BY' => $this->session->userdata('NAMA'),
                            'CREATED_IP' => get_client_ip(),
                            'UPDATED_TIME' => date("Y-m-d H:i:s"),
                            'UPDATED_BY' => $this->session->userdata('NAMA'),
                            'UPDATED_IP' => get_client_ip(),
                        );
                        $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_lainnya', $asl_array);
                    }
                }
            }
            echo "1";
        } else {
            echo "0";
        }
    }
    
    function upload_harta_lainnya() {
        $result = array();
        $folder = $this->encrypt($this->session->userdata('USERNAME'), 'e');
        if (!file_exists('uploads/data_hartalainnya/' . $folder)) {
            mkdir('uploads/data_hartalainnya/' . $folder);
            $content = "Bukti Harta Lainnya Dari " . $folder . " dengan nik " . $this->session->userdata('USERNAME');
             $fp = fopen(FCPATH . "/uploads/data_hartalainnya/" . $folder . "/readme.txt", "wb");
             fwrite($fp, $content);
             fclose($fp);
            /* IBO UPDATE */
        }
        
        $rst = false;
        $urllist = 'uploads/data_hartalainnya/' . $folder . '/';
        foreach ($_FILES['file1']['tmp_name'] as $key => $tmp_name) {
            $time = time();
            $ext = end((explode(".", $_FILES['file1']['name'][$key])));
            $file_name = $key . $time . '.' . $ext;
            $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
            $urllist = $urllist . $file_name . ',';
            $uploadext = '.' . strtolower($ext);

            if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx' || $uploadext == '.tif' || $uploadext == '.tiff') {
                $rst = (move_uploaded_file($_FILES['file1']['tmp_name'][$key], $uploaddir));
            }
        }
        $result = array('upload' => $rst, 'url' => $urllist);

//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }

//             /* IBO UPDATE */
//         } else {
//             if (isset($_FILES["file1"])) {
//                 $time = time();
//                 $ext = end((explode(".", $_FILES['file1']['name'])));
//                 $file_name = $time . '.' . $ext;
//                 $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                 $uploadext = '.' . strtolower($ext);
//                 if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                     if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
//                         $result = array('upload' => true, 'url' => $uploaddir);
//                     } else {
//                         $result = array('upload' => false, 'url' => $uploaddir);
//                     }
//                 }
//             } else if (isset($_FILES["file2"])) {
//                 foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
//                     $time = time();
//                     $ext = end((explode(".", $_FILES['file2']['name'][$key])));
//                     $file_name = $key . '' . $time . '.' . $ext;
//                     $uploaddir = 'uploads/data_hartalainnya/' . $folder . '/' . $file_name;
//                     $uploadext = '.' . strtolower($ext);
//                     if ($uploadext == '.jpg' || $uploadext == '.gif' || $uploadext == '.png' || $uploadext == '.swf' || $uploadext == '.jpeg' || $uploadext == '.pdf' || $uploadext == '.doc' || $uploadext == '.xls' || $uploadext == '.docx') {
//                         if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
//                             $result = array('upload' => true, 'url' => $uploaddir);
//                         }
//                     }
//                 }
//             } else {
//                 $result = array('upload' => false, 'url' => $uploaddir);
//             }
//         }
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

    // HUTANG
    private function __set_join_grid_hutang($TABLE, $PK) {
        $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = ' . $TABLE . '.KODE_JENIS');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '" . $this->session->userdata('ID_PN') . "'");

        $this->db->group_by($TABLE . '.' . $PK);
    }

    function grid_hutang($ID_LHKPN) {
        $TABLE = 't_lhkpn_hutang';
        $PK = $this->pk($TABLE);
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $sql_like = "";
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        if ($cari) {

            $sql_like = " (m_jenis_hutang.NAMA LIKE '%" . $cari . "%' OR "
                    . " t_lhkpn_hutang.ATAS_NAMA LIKE '%" . $cari . "%' OR "
                    . " t_lhkpn_hutang.NAMA_KREDITUR LIKE '%" . $cari . "%' OR "
                    . " t_lhkpn_hutang.AGUNAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_hutang($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();

        if ($data) {
            foreach ($data as $list) {

   
                $get_atas_nama = $list->ATAS_NAMA;
                $atas_nama = '';
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $list->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$list->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$list->KET_LAINNYA.')' ;
                    }
                }
                
                
                
                
                $i++;
                $jenis = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA . "</td>
						 </tr>
						 <tr>
						    <td><b>Atas Nama</b></td>
                            <td>:</td>

                            <td>" . $atas_nama . "</td>
						 </tr>
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $jenis,
                        $list->NAMA_KREDITUR,
                        $list->AGUNAN,
                        'Rp. ' . number_rupiah($list->AWAL_HUTANG) . '',
                        'Rp. ' . number_rupiah($list->SALDO_HUTANG) . '',
                        $list->STATUS == 0 || $list->STATUS == 2 && $list->entry_via == '0' ? $this->action($list->$PK, $TABLE, '0') : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if ($cari) {
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_hutang($TABLE, $PK);
        $jml = $this->db->get($TABLE)->num_rows();
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $jml,
            "iTotalDisplayRecords" => $jml,
            "aaData" => $aaData
        );
        header('Content-Type: application/json');
        echo json_encode($sOutput);
        exit;
    }

    function edit_hutang($ID) {
        $TABLE = 't_lhkpn_hutang';
        $PK = $this->pk($TABLE);
        $this->db->where($PK, $ID);
        $data = $this->db->get($TABLE)->row();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function update_hutang() {
        $TABLE = 't_lhkpn_hutang';
        $PK = $this->pk($TABLE);
        $ID = $this->input->post('ID');
        $ID_LHKPN = $this->input->post('ID_LHKPN');

        $data_atas_nama = json_encode($this->input->post('ATAS_NAMA'));
        $data_atas_nama = str_replace('[', '', $data_atas_nama);
        $data_atas_nama = str_replace(']', '', $data_atas_nama);
        $data_atas_nama = str_replace('"', '', $data_atas_nama);
        
        
        $data_pasangan_anak = json_encode($this->input->post('PASANGAN_ANAK'));
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);
                        
        $data = array(
            'ID_LHKPN' => $ID_LHKPN,
            'ATAS_NAMA' => $data_atas_nama,
            'PASANGAN_ANAK' => $data_pasangan_anak,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'NAMA_KREDITUR' => $this->input->post('NAMA_KREDITUR'),
            'AGUNAN' => $this->input->post('AGUNAN'),
            'AWAL_HUTANG' => str_replace('.', '', $this->input->post('AWAL_HUTANG')),
            'SALDO_HUTANG' => str_replace('.', '', $this->input->post('SALDO_HUTANG')),
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
//            'IS_LOAD' => '0',
            'KET_LAINNYA' => $this->input->post('KET_LAINNYA_AN')
        );
        $result = NULL;
        if ($ID) {
            $this->db->where($PK, $ID);
            $result = $this->db->update($TABLE, $data);
        } else {
            $this->db->insert($TABLE, $data);
            $result = $this->db->insert_id();
        }
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function checkload($ID_LHKPN, $TABLE) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_LOAD', '1');
        $this->db->join($TABLE, $TABLE . '.ID_LHKPN = t_lhkpn.ID_LHKPN');
        $lhkpn = $this->db->get('t_lhkpn')->num_rows();
        if ($lhkpn > 1) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function test() {
        
    }

    function test_post() {
        $array = $_POST;
        header('Content-type: application/json');
        echo json_encode($array);
    }

    function encrypt($string, $action = 'e') {
        
        return encrypt_username($string, $action);
//        $secret_key = 'R@|-|a5iaKPK|-|@rTa';
//        $secret_iv = 'R@|-|a5ia|/|394124|-|@rTa';
//
//        $output = false;
//        $encrypt_method = "AES-256-CBC";
//        $key = hash('sha256', $secret_key);
//        $iv = substr(hash('sha256', $secret_iv), 0, 16);
//
//        if ($action == 'e') {
//            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
//        } else if ($action == 'd') {
//            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
//        }
//
//        return $output;
        exit;
    }

    function PindahFile($src, $dst) {
        //var_dump($src);exit;
        if (is_dir ( $src )) {
            $files = scandir ( $src );
            foreach ( $files as $file ){
                copy ( "$src/$file", "$dst/$file" );
            }
        } 
    }

}
