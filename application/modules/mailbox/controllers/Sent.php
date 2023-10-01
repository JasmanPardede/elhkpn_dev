<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller User
 * 
 * @author Arif Kurniawan - PT.Mitreka Solusi Indonesia
 * @package Controllers
 */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sent extends CI_Controller {

    // num of records per page
    public $limit = 10;
    public $username;
    public $tabel_db = 'T_PESAN_KELUAR';

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('mglobal');
    }

    public function index($offset = 0) {
        $join = [
            ['table' => 'T_USER user', 'on' => 'user.ID_USER = pesan.ID_PENERIMA'],
                // ['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD']
        ];
        $select = 'pesan.ID, user.NAMA, pesan.TANGGAL_KIRIM, pesan.SUBJEK, pesan.PESAN, pesan.ID_PENGIRIM, pesan.FILE';
        $this->items = $this->mglobal->get_data_all($this->tabel_db . ' pesan', $join, ['pesan.IS_ACTIVE' => '1', 'pesan.ID_PENGIRIM' => $this->session->userdata('ID_USER')], $select, null, ['TANGGAL_KIRIM', 'DESC']);
        // load view
        $data = array(
            'items' => $this->items,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Mailbox' => 'index.php/mailbox/sent',
                'Sent' => 'index.php/mailbox/sent',
            )),
                // 'pagination'	=> call_user_func('ng::genPagination'),
        );
        $this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    /** Form Tambah pesan
     * 
     * @return html form tambah pesan
     */
    function addmail() {
        $data = array(
            'form' => 'add',
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }
    /** Form Lihat pesan
     * 
     * @return html form detail pesan
     */
    function detail($id = '') {
        $data = array(
            'form' => 'detail',
            'item' => $this->mglobal->get_data_all('T_PESAN_KELUAR', null, ['SUBSTRING(md5(ID), 6, 8) = ' => $id])[0],
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }
    /** Form Lihat pesan
     * 
     * @return html form detail pesan
     */
    function delete($id = '') {
        $data = array(
            'form' => 'delete',
            'item' => $this->mglobal->get_data_all('T_PESAN_KELUAR', null, ['SUBSTRING(md5(ID), 6, 8) = ' => $id])[0],
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    function savemail($data_id = '') {
        $type_file = array('.xls', '.xlsx', '.doc', '.docx', '.pdf', '.jpg', '.jpeg', '.png');
        $maxsize = 500000;
        $id = 'unknown';
        $url = '';

        $user = $this->session->userdata('USR');

        if ($this->session->userdata('ID_USER') != '') {
            $id = $this->session->userdata('ID_USER');
        }

        $filename = 'uploads/mail_out/' . $id . '/readme.txt';

        if (!file_exists($filename)) {

            $dir = './uploads/mail_out/' . $id . '/';

            $file_to_write = 'readme.txt';
            $content_to_write = "Bukti Kas Dari " . $user . ' dengan id ' . $id;

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            $file = fopen($dir . '/' . $file_to_write, "w");

            fwrite($file, $content_to_write);

            // closes the file
            fclose($file);
        }

        if ($this->input->post('act', TRUE) == 'doinsert') {
            $filependukung = @$_FILES['filename'];

            $extension = strtolower(@substr(@$filependukung['name'], -4));
            if (in_array($extension, $type_file)) {
                if ($filependukung['size'] <= $maxsize) {
                    $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/mail_out/" . $id . "/", 0, 10000);
                    if ($filependukung['size'] == '') {
                        $url = '';
                    } else {
                        $url = time() . "-" . trim($filependukung['name']);
                    }
                }
            }
            $penerima = explode(',', $this->input->post('ID_PENERIMA'));
            $data_pesan = $this->input->post('PESAN');
            $pesan_valid = strip_tags(nl2br($data_pesan), '<br>');

            foreach ($penerima as $key) {
                $data = array(
                    'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                    'ID_PENERIMA' => $key,
                    'SUBJEK' => $this->input->post('SUBJEK'),
                    'PESAN' => $pesan_valid,
                    'FILE' => $url,
                    'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                    'IS_ACTIVE' => '1'
                );
                $result = $this->mglobal->insert('T_PESAN_KELUAR', $data);

                if ($result) {
                    $data2 = array(
                        'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                        'ID_PENERIMA' => $key,
                        'SUBJEK' => $this->input->post('SUBJEK'),
                        'PESAN' => $pesan_valid,
                        'FILE' => $url,
                        'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                        'IS_ACTIVE' => '1'
                    );
                    $this->mglobal->insert('T_PESAN_MASUK', $data2);
                }
                $user = $this->mglobal->get_data_all('T_USER', null, ['ID_USER = ' => $key], 'NAMA,EMAIL')[0];
                $subject = $this->input->post('SUBJEK');
                $admin = $this->mglobal->get_data_all('T_USER', null, ['USERNAME = ' => 'admin_kpk'], 'ID_USER, NAMA,EMAIL')[0];
                if ($admin->ID_USER != $this->session->userdata('ID_USER')) {
                    ng::mail_send($user->EMAIL, $subject, $this->input->post('PESAN'), NULL, 'uploads/mail_out/' . $id . '/' . $url, $admin->EMAIL);

                    $dataadmin = array(
                        'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                        'ID_PENERIMA' => $admin->ID_USER,
                        'SUBJEK' => $this->input->post('SUBJEK'),
                        'PESAN' => $pesan_valid,
                        'FILE' => $url,
                        'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                        'IS_ACTIVE' => '1'
                    );
                    //$resultadmin = $this->mglobal->insert('T_PESAN_KELUAR', $dataadmin);

                    if ($resultadmin) {
                        $dataadmin2 = array(
                            'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                            'ID_PENERIMA' => $admin->ID_USER,
                            'SUBJEK' => $this->input->post('SUBJEK'),
                            'PESAN' => $pesan_valid,
                            'FILE' => $url,
                            'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                            'IS_ACTIVE' => '1'
                        );
                        //$this->mglobal->insert('T_PESAN_MASUK', $dataadmin2);
                    }
                } 
				else {
                    ng::mail_send($user->EMAIL, $subject, $this->input->post('PESAN'), NULL, 'uploads/mail_out/' . $id . '/' . $url, NULL);
                }
            }
        } else if ($this->input->post('act', TRUE) == 'dodelete') {
            $data = array(
                'IS_ACTIVE' => '-1'
            );
            $this->mglobal->update('T_PESAN_KELUAR', $data, ['SUBSTRING(md5(ID), 6, 8) = ' => $data_id]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo intval($this->db->trans_status());
    }

    /** Function getContact
     * 
     * @return data user
     */
    public function getContact($id = null) {
		$roles=$this->session->userdata('ID_ROLE');
        if (is_null($id)) {
            $q = $_GET['q'];
            $where['user.NAMA LIKE'] = '%' . $q . '%';
            $where['user.IS_ACTIVE'] = '1';
			
            /*if ($this->session->userdata('IS_PN') == '1') {
                if($roles==1 || $roles ==2){
					$role = array('1', '2','3', '4', '5');
				}
				else if($roles==3){
					$role = array('2','3', '4', '5');
				}
				else if($roles==4){
					$role = array('2','4', '5');
				}
				else if($roles==5){
					$role = array('2', '5');
				}
                $this->db->where_in('user.ID_ROLE', $role);
                $this->db->where('user.INST_SATKERKD', $this->session->userdata('INST_SATKERKD'));
            } */
			if($roles==1 || $roles ==2){
				$role = array('1', '2','3', '4', '5');
				$this->db->where_in('user.ID_ROLE', $role);
			}
			else if($roles==3){
				$role = array('2','3', '4', '5');
				$this->db->where_in('user.ID_ROLE', $role);
				$this->db->where('user.INST_SATKERKD', $this->session->userdata('INST_SATKERKD'));
				$where_e = 'user.ID_USER <> ' . $this->session->userdata('ID_USER')." OR  user.USERNAME='admin_kpk'";
				//$this->db->or_where('user.USERNAME', 'admin_kpk');
			}
			else if($roles==4){
				$role = array('2','4', '5');
				$where['user.INST_SATKERKD'] = $this->session->userdata('INST_SATKERKD');
				$this->db->where_in('user.ID_ROLE', $role);
				$where_e = 'user.ID_USER <> ' . $this->session->userdata('ID_USER')." OR  user.USERNAME='admin_kpk'";
			}
			else if($roles==5){
				$role = array('2', '5');
				$where['user.INST_SATKERKD'] = $this->session->userdata('INST_SATKERKD');
				$this->db->where_in('user.ID_ROLE', $role);
				$where_e = 'user.ID_USER <> ' . $this->session->userdata('ID_USER')." OR  user.USERNAME='admin_kpk'";
			}
			
            
            $select = 'user.ID_USER id_user, user.NAMA nama, satker.INST_NAMA inst_satkerkd , user.USERNAME NIK';
            $join = [['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD', 'join' => 'left']];
            $contact = $this->mglobal->get_data_all('T_USER user', $join, $where, $select, $where_e, ['user.NAMA', 'ASC'], $start = null, $tampil = 50);

            $data = [];
            for ($i = 0; $i < count($contact); $i++) {
                $inst = ($contact[$i]->inst_satkerkd !== null) ? " - " . $contact[$i]->inst_satkerkd . "" : "";
                $nik = ($contact[$i]->NIK !== null) ? " - " . $contact[$i]->NIK . "" : "";
				$data[$i] = ['id' => $contact[$i]->id_user, 'name' => '<b>' . $contact[$i]->nama . "</b>" . $inst. "" . $nik];
            }

            echo json_encode(['item' => $data]);
        } 
		else {
            $join = [['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD', 'join' => 'left']];
            $select = 'user.ID_USER id_user, user.NAMA nama, satker.INST_NAMA inst_satkerkd';
            $contact = $this->mglobal->get_data_all($roles . ' T_USER user', $join, ['user.ID_USER' => $id], $select, null, ['user.USERNAME', 'ASC']);
            $inst = ($contact[0]->inst_satkerkd !== null) ? " - (" . $contact[0]->inst_satkerkd . ")" : "";
            $nik = ($contact[$i]->NIK !== null) ? " - " . $contact[$i]->NIK . "" : "";
			$tmp = [
                $data[0] = ['id' => $contact[0]->id_user, 'name' => '<b>' . $contact[0]->nama . "</b>" . $inst. "" . $nik],
            ];

            echo json_encode($tmp);
        }
    }

    /** Function getContact_name
     * 
     * @return data user berdasarkan id
     */
    public function getContact_name($id = null) {
        $join = [['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD', 'join' => 'left']];
        $select = 'user.NAMA nama, satker.INST_NAMA inst_satkerkd, user.USERNAME NIK';
        $data = $this->mglobal->get_data_all('T_USER user', $join, ['user.ID_USER' => $id], $select, null, ['user.NAMA', 'ASC']);

        echo json_encode($data);
    }

    public function sentJabatan() {
        $email = explode(',', $this->input->post('email'));
        $msg = $this->input->post('msg');
        $id = explode(',', $this->input->post('id'));

        foreach ($email as $key => $row) {
            $this->sentMail($this->session->userdata('ID_USER'), $id[$key], $row, 'Review Jabatan', $msg);
        }

        echo '1';
    }

    function sentMail($pengirim, $idUser, $penerima, $subject, $pesan, $send_to_mail = TRUE) {
        $this->load->model('Msuratkeluar');
        return $this->msuratkeluar->send_message($pengirim, $idUser, $penerima, $subject, $pesan, $send_to_mail);
    }

    function ViewExportExcel() {

        $this->load->library('excel');
        $dir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
        $objReader = new PHPExcel_Reader_Excel5();
        $objReader->setReadDataOnly(true);
        $filename = $dir . '/file/excel/data_pn.xls';
        $objPHPExcel = $objReader->load($filename);
//
        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        array();
        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            if ($row->getRowIndex() < 7)
                continue; //skip first row
            $rowIndex = $row->getRowIndex();
            $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '');

            foreach ($cellIterator as $cell) {
                if ('A' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('B' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('C' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
//                    
                } else if ('D' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('E' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('F' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('G' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('H' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
                } else if ('I' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                } else if ('J' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                }
            }
        }

        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Mailbox' => 'index.php/mailbox/sent',
                'Sent' => 'index.php/mailbox/sent',
            )),
            'list' => $array_data
        );

        $this->load->view('excel_index_view', $data);
    }

}

/* End of file sent.php */
/* Location: ./application/modules/mailbox/controllers/sent.php */