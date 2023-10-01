<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_harta extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
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
                        if ($data->STATUS == '3'){
                            $delete.= "<a id='" . $ID . "'  data-nilai=[" . $nilai . "] href='javascript:void(0)' class='btn btn-primary btn-sm penetapan-action' title='Penetapan'><i class='fa fa-link'></i></a>";
                        }
                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                        $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                        $delete = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";
                    }
                }
            } else { // JIKA DATA DITETAPKAN
                $edit = "<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
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
    }

    function get_option_matauang($table, $name, $value) {
        $this->db->where('IS_ACTIVE', '1');
        $this->db->order_by($value);
        $data = $this->db->get($table)->result();
        echo "<option></option>";
        foreach ($data as $row) {
            echo "<option value='" . $row->$value . "'>" . strtoupper($row->$name) . "</option>";
        }
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
    }

    function ATAS_NAMA($index) {
        $data = array();
        $data[1] = 'PN YANG BERSANGKUTAN';
        $data[2] = 'PASANGAN/ANAK';
        $data[3] = 'LAINNYA';
        return $data[$index];
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
    }

    function IS_COPY($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn')->row();
        if ($data) {
            if ($data->IS_COPY == 1) {
                echo "<a id='" . $ID_LHKPN . "'  href='javascript:void(0)' class='btn btn-warning btn-sm updated-action'><i class='fa fa-refresh'></i> Load Data</a>";
            }
        }
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
    }

    // HARTA TIDAK BERGERAK

    private function __set_join_grid_harta_tidak_bergerak($TABLE ,  $PK){
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = ' . $TABLE . '.ID_NEGARA ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
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
//            $this->db->like($TABLE . '.JALAN', $cari);
//            $this->db->or_like($TABLE . '.KEL', $cari);
//            $this->db->or_like($TABLE . '.KEC', $cari);
//            $this->db->or_like($TABLE . '.KAB_KOT', $cari);
//            $this->db->or_like($TABLE . '.LUAS_BANGUNAN', $cari);
//            $this->db->or_like($TABLE . '.LUAS_TANAH', $cari);
//            $this->db->or_like('m_jenis_bukti.JENIS_BUKTI', $cari);
//            $this->db->or_like($TABLE . '.NOMOR_BUKTI', $cari);

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
        	m_jenis_bukti.*,
        	m_mata_uang.*,
        	t_lhkpn.*, t_lhkpn.STATUS STATUS_LHKPN,
        	t_lhkpn_harta_tidak_bergerak.*,
        	t_lhkpn_harta_tidak_bergerak.STATUS AS STATUS_HARTA,
        	m_jenis_bukti.JENIS_BUKTI AS JENIS_BUKTI_HARTA
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_tidak_bergerak($TABLE ,  $PK);
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
                            <td>" . number_rupiah($list->LUAS_TANAH) . " m <sup>2</sup></td>
                         </tr>
                         <tr>
                            <td><b>Bangunan</b></td>
                            <td>:</td>
                            <td>" . number_rupiah($list->LUAS_BANGUNAN) . " m <sup>2</sup></td>
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
                            <td>" . $this->ATAS_NAMA($list->ATAS_NAMA) . "</td>
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
                        $list->STATUS_LHKPN == '0' || $list->STATUS_LHKPN == '2' ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }


        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        
        if($cari){
            $this->db->where($sql_like);
            
        }
        $this->__set_join_grid_harta_tidak_bergerak($TABLE ,  $PK);
        $jml = $this->db->get($TABLE)->num_rows();
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
    }
    
    function delete_condition($ID,$ID_HARTA_ASL){
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
    }
    
    function __get_id_harta($TABLE_HARTA){
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
            'LUAS_TANAH' => intval(str_replace('.', '', $this->input->post('LUAS_TANAH'))),
            'LUAS_BANGUNAN' => intval(str_replace('.', '', $this->input->post('LUAS_BANGUNAN'))),
            'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI'),
            'NOMOR_BUKTI' => $this->input->post('NOMOR_BUKTI'),
            'ATAS_NAMA' => $this->input->post('ATAS_NAMA'),
            'ASAL_USUL' => $ASAL_USUL,
            'PEMANFAATAN' => $PEMANFAATAN,
            'KET_LAINNYA' => $this->input->post('KET_LAINNYA_AN'),
            //'TAHUN_PEROLEHAN_AWAL' => $this->input->post('TAHUN_PEROLEHAN_AWAL'),
            //'TAHUN_PEROLEHAN_AKHIR' => $this->input->post('TAHUN_PEROLEHAN_AKHIR'),
            'MATA_UANG' => 1,
            'NILAI_PEROLEHAN' => str_replace('.', '', $this->input->post('NILAI_PEROLEHAN')),
            'NILAI_PELAPORAN' => str_replace('.', '', $this->input->post('NILAI_PELAPORAN')),
            'JENIS_NILAI_PELAPORAN' => $this->input->post('JENIS_NILAI_PELAPORAN'),
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

        if($id_data_harta != 0){
            $this->delete_condition($ID,$ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_tidak_bergerak');
        }
        
        $this->delete_condition($ID,$ID_HARTA_ASL);
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
    private function __set_join_grid_harta_bergerak($TABLE, $PK){
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        
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
//            $this->db->like('m_jenis_harta.NAMA', $cari);
//            $this->db->or_like($TABLE . '.MEREK', $cari);
//            $this->db->or_like($TABLE . '.MODEL', $cari);
//            $this->db->or_like($TABLE . '.TAHUN_PEMBUATAN', $cari);
//            $this->db->or_like($TABLE . '.NOPOL_REGISTRASI', $cari);
//            $this->db->or_like('m_jenis_bukti.JENIS_BUKTI', $cari);
//            $this->db->or_like($TABLE . '.KET_LAINNYA', $cari);

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
        	m_jenis_bukti.JENIS_BUKTI as JENIS_BUKTI_VALUE,
        	t_lhkpn.*
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_bergerak($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
//        echo $this->db->last_query();
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
                            <td>" . $this->ATAS_NAMA($list->ATAS_NAMA) . "</td>
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
                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if($cari){
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
            'ATAS_NAMA' => $this->input->post('ATAS_NAMA'),
            'KET_LAINNYA' => $this->input->post('KET_LAINNYA_AN'),
            'ASAL_USUL' => $ASAL_USUL,
            'PEMANFAATAN' => $this->input->post('PEMANFAATAN'),
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

        if($id_data_harta != 0){
            $this->delete_condition($ID,$ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_bergerak');
        }
        $this->delete_condition($ID,$ID_HARTA_ASL);
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
    private function __set_join_grid_harta_bergerak_lain($TABLE, $PK){
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_harta_bergerak_lain.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        
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
//            $this->db->like('m_jenis_harta.NAMA', $cari);
//            $this->db->or_like($TABLE . '.JUMLAH', $cari);
//            $this->db->or_like($TABLE . '.SATUAN', $cari);
//            $this->db->or_like($TABLE . '.KETERANGAN', $cari);
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
        	m_jenis_harta.NAMA as JENIS_HARTA,
        	t_lhkpn.*
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_bergerak_lain($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
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
                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        
        if($cari){
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

        if($id_data_harta != 0){
            $this->delete_condition($ID,$ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_bergerak_lain');
        }
        $this->delete_condition($ID,$ID_HARTA_ASL);
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

    // SURAT BERHARGA
    private function __set_join_grid_surat_berharga($TABLE, $PK){
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        
        $this->db->group_by($TABLE . '.' . $PK);
    }
    function grid_surat_berharga($ID_LHKPN) {
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
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');

        if ($cari) {
//            $this->db->like('m_jenis_harta.NAMA', $cari);
//            $this->db->or_like($TABLE . '.ATAS_NAMA', $cari);
//            $this->db->or_like($TABLE . '.NAMA_PENERBIT', $cari);
//            $this->db->or_like($TABLE . '.CUSTODIAN', $cari);

            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " ATAS_NAMA LIKE '%" . $cari . "%' OR "
                    . " NAMA_PENERBIT LIKE '%" . $cari . "%' OR "
                    . " CUSTODIAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_surat_berharga.*,
        	t_lhkpn_harta_surat_berharga.STATUS AS STATUS_HARTA,
        	t_lhkpn.*
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_surat_berharga($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
        if ($data) {
            foreach ($data as $list) {
                $i++;
                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_surat_berharga.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_surat_berharga')->result();
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
                    if (file_exists($list->FILE_BUKTI)) {
                        $img = " <a class='files' target='_blank' href='" . base_url() . '' . $list->FILE_BUKTI . "'><i class='fa fa-file-text fa-2x'></i></a>";
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
                            <td>" . $list->ATAS_NAMA . "</td>
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
                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if($cari){
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_surat_berharga($TABLE, $PK);
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

        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING'),
            'ATAS_NAMA' => $this->input->post('ATAS_NAMA'),
            'ASAL_USUL' => $ASAL_USUL,
            'STATUS' => $STATUS,
            'NAMA_PENERBIT' => $this->input->post('NAMA_PENERBIT'),
            'MATA_UANG' => 1,
            'CUSTODIAN' => $this->input->post('CUSTODIAN'),
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

        if($id_data_harta != 0){
            $this->delete_condition($ID,$ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_surat_berharga');
        }
        $this->delete_condition($ID,$ID_HARTA_ASL);
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
        $folder = $this->session->userdata('USERNAME');
        if (!file_exists('uploads/data_suratberharga/' . $folder)) {
            mkdir('uploads/data_suratberharga/' . $folder);
            $content = "Bukti Surat Berharga Dari " . $folder . " dengan nik " . $folder;
            $fp = fopen(FCPATH . "/uploads/data_suratberharga/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
        } else {
            if (isset($_FILES["file1"])) {
                $time = time();
                $ext = end((explode(".", $_FILES['file1']['name'])));
                $file_name = $time . '.' . $ext;
                $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
                if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                    $result = array('upload' => true, 'url' => $uploaddir);
                } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                }
            } else if (isset($_FILES["file2"])) {
                foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                    $file_name = $key . '' . $time . '.' . $ext;
                    $uploaddir = 'uploads/data_suratberharga/' . $folder . '/' . $file_name;
                    if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                    }
                }
            } else {
                $result = array('upload' => false, 'url' => $uploaddir);
            }
        }
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

    // KAS
    private function __set_join_grid_kas($TABLE, $PK){
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        
        $this->db->group_by($TABLE . '.' . $PK);
    }
    function grid_kas($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_kas';
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
//            $this->db->like('m_jenis_harta.NAMA', $cari);
//            $this->db->or_like($TABLE . '.NAMA_BANK', $cari);
//            $this->db->or_like($TABLE . '.NOMOR_REKENING', $cari);
//            $this->db->or_like($TABLE . '.ATAS_NAMA_REKENING', $cari);
//            $this->db->or_like($TABLE . '.KETERANGAN', $cari);
            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " NAMA_BANK LIKE '%" . $cari . "%' OR "
                    . " NOMOR_REKENING LIKE '%" . $cari . "%' OR "
                    . " ATAS_NAMA_REKENING LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	m_jenis_harta.*,
        	m_mata_uang.*,
        	t_lhkpn_harta_kas.*,
        	t_lhkpn_harta_kas.STATUS AS STATUS_HARTA,
        	t_lhkpn.*
        ');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_kas($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
//        display($this->db->last_query());exit;
        if ($data) {
            foreach ($data as $list) {
                $i++;
                $this->db->where('ID_HARTA', $list->ID);
                $this->db->join('m_asal_usul', 'm_asal_usul.ID_ASAL_USUL = t_lhkpn_asal_usul_pelepasan_kas.ID_ASAL_USUL');
                $asul = $this->db->get('t_lhkpn_asal_usul_pelepasan_kas')->result();
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

                $image = null;
                if ($list->FILE_BUKTI) {
                    if (file_exists($list->FILE_BUKTI)) {
                        $image = ' <a class="files" target="_blank" href="' . base_url() . '' . $list->FILE_BUKTI . '"><i class="fa fa-file-text fa-2x"></i></a>';
                    }
                }


                $uraian = "
					<table class='table table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA . " " . $image . "</td>
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
                            <td>" . $list->ATAS_NAMA_REKENING . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $list->KETERANGAN . "</td>
						 </tr>
					</table>
				";

                if ($list->ID_LHKPN == $ID_LHKPN) {
                    $aaData[] = array(
                        $i,
                        $this->STATUS_HARTA($list->STATUS_HARTA, $list->IS_PELEPASAN),
                        $uraian,
                        $info_rekening,
                        $list_asul,
                        //''.$list->SIMBOL.''.number_rupiah($list->NILAI_SALDO).'',
                        'Rp. ' . number_rupiah($list->NILAI_EQUIVALEN) . '',
                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if($cari){
            $this->db->where($sql_like);
        }
        $this->__set_join_grid_kas($TABLE, $PK);
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



        $data = array(
            'ID_HARTA' => $ID_HARTA,
            'ID_LHKPN' => $ID_LHKPN,
            'KODE_JENIS' => $this->input->post('KODE_JENIS'),
            'ASAL_USUL' => $ASAL_USUL,
            'NAMA_BANK' => $this->input->post('NAMA_BANK'),
            'NOMOR_REKENING' => $this->input->post('NOMOR_REKENING'),
            'ATAS_NAMA_REKENING' => $this->input->post('ATAS_NAMA_REKENING'),
            'MATA_UANG' => $this->input->post('MATA_UANG'),
            'NILAI_SALDO' => intval(str_replace('.', '', $this->input->post('NILAI_SALDO'))),
            'NILAI_EQUIVALEN' => intval(str_replace('.', '', $this->input->post('NILAI_EQUIVALEN'))),
            'IS_ACTIVE' => 1,
//            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'KETERANGAN' => $this->input->post('KET_LAINNYA_AN'),
            'STATUS' => $STATUS,
            'FILE_BUKTI' => $FILE_BUKTI,
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
        if ($ID) {
            $this->db->where('ID_HARTA', $ID);
        } else {
            $this->db->where('ID_HARTA', $ID_HARTA_ASL);
        }
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_kas');

        if($id_data_harta != 0){
            $this->delete_condition($ID,$ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_kas');
        }
        $this->delete_condition($ID,$ID_HARTA_ASL);
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
        $folder = $this->session->userdata('USERNAME');
        if (!file_exists('uploads/data_kas/' . $folder)) {
            mkdir('uploads/data_kas/' . $folder);
            $content = "Bukti Kas Dari " . $folder . " dengan nik " . $folder;
            $fp = fopen(FCPATH . "/uploads/data_kas/" . $folder . "/readme.txt", "wb");
            fwrite($fp, $content);
            fclose($fp);
        } else {
            if (isset($_FILES["file1"])) {
                $time = time();
                $ext = end((explode(".", $_FILES['file1']['name'])));
                $file_name = $time . '.' . $ext;
                $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                if (move_uploaded_file($_FILES['file1']['tmp_name'], $uploaddir)) {
                    $result = array('upload' => true, 'url' => $uploaddir);
                } else {
                    $result = array('upload' => false, 'url' => $uploaddir);
                }
            } else if (isset($_FILES["file2"])) {
                foreach ($_FILES['file2']['tmp_name'] as $key => $tmp_name) {
                    $time = time();
                    $ext = end((explode(".", $_FILES['file2']['name'][$key])));
                    $file_name = $key . '' . $time . '.' . $ext;
                    $uploaddir = 'uploads/data_kas/' . $folder . '/' . $file_name;
                    if (move_uploaded_file($_FILES['file2']['tmp_name'][$key], $uploaddir)) {
                        $result = array('upload' => true, 'url' => $uploaddir);
                    }
                }
            } else {
                $result = array('upload' => false, 'url' => $uploaddir);
            }
        }
        /* header('Content-Type: application/json');
          echo json_encode($result); */
        return $result;
    }

    // HARTA LAINNYA 
    private function __set_join_grid_harta_lainnya($TABLE, $PK){
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        
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
//            $this->db->like('m_jenis_harta.NAMA', $cari);
//            $this->db->or_like($TABLE . '.KETERANGAN', $cari);

            $sql_like = " (m_jenis_harta.NAMA LIKE '%" . $cari . "%' OR "
                    . " KETERANGAN LIKE '%" . $cari . "%') ";

            $this->db->where($sql_like);
        }
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_lainnya.*,
        	t_lhkpn_harta_lainnya.STATUS AS STATUS_HARTA,
        	m_jenis_harta.NAMA AS NAMA_JENIS,
        	t_lhkpn.*
       	');
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->__set_join_grid_harta_lainnya($TABLE, $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get($TABLE)->result();
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

                $uraian = "
					<table class='table table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $list->NAMA_JENIS . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $list->KETERANGAN . "</td>
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
                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($list->$PK, $TABLE, $list->IS_COPY) : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if($cari){
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
        $id_data_harta = $this->__get_id_harta('t_lhkpn_pelepasan_harta_lainnya');

        if($id_data_harta != 0){
            $this->delete_condition($ID,$ID_HARTA_ASL);
            $this->db->delete('t_lhkpn_pelepasan_harta_lainnya');
        }
        $this->delete_condition($ID,$ID_HARTA_ASL);
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

    // HUTANG
    private function __set_join_grid_hutang($TABLE, $PK){
        $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = ' . $TABLE . '.KODE_JENIS');
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->join('t_lhkpn', "t_lhkpn.ID_LHKPN = " . $TABLE . ".ID_LHKPN and ID_PN = '".$this->session->userdata('ID_PN')."'");
        
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
//            $this->db->like('m_jenis_hutang.NAMA', $cari);
//            $this->db->or_like('t_lhkpn_hutang.ATAS_NAMA', $cari);
//            $this->db->or_like('t_lhkpn_hutang.NAMA_KREDITUR', $cari);
//            $this->db->or_like('t_lhkpn_hutang.AGUNAN', $cari);

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
                            <td>" . $list->ATAS_NAMA . "</td>
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
                        $list->STATUS == 0 || $list->STATUS == 2 ? $this->action($list->$PK, $TABLE, '0') : ''
                    );
                }
            }
        }
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        if($cari){
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
        $data = array(
            'ID_LHKPN' => $ID_LHKPN,
            'ATAS_NAMA' => $this->input->post('ATAS_NAMA'),
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
            'IS_LOAD' => '0',
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

}
