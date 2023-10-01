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

class Inbox extends CI_Controller {

    // num of records per page
    public $limit = 10;
    public $username;
    public $tabel_db = 'T_PESAN_MASUK';

    public function __construct() {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->makses->initialize();
        $this->load->model('mglobal');
    }

    public function index($offset = 0) {
        $join = [
            ['table' => 'T_USER user', 'on' => 'user.ID_USER = pesan.ID_PENGIRIM'],
                // ['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD']
        ];
        $select = 'pesan.ID, user.NAMA, pesan.TANGGAL_MASUK,pesan.SUBJEK, pesan.PESAN, pesan.ID_PENGIRIM, pesan.FILE, pesan.IS_READ';
        $this->items = $this->mglobal->get_data_all($this->tabel_db . ' pesan', $join, ['pesan.IS_ACTIVE' => '1', 'pesan.ID_PENERIMA' => $this->session->userdata('ID_USER')], $select, null, ['TANGGAL_MASUK', 'DESC']);
        // load view
        $data = array(
            'items' => $this->items,
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Mailbox' => 'index.php/mailbox/inbox',
                'Inbox' => 'index.php/mailbox/inbox',
            )),
                // 'pagination'	=> call_user_func('ng::genPagination'),
        );
        $this->load->view(strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__), $data);
    }

    /** Form Lihat pesan
     * 
     * @return html form detail pesan
     */
    function detail($id = '') {
        $this->mglobal->update('T_PESAN_MASUK', ['IS_READ' => '1'], ['SUBSTRING(md5(ID), 6, 8) = ' => $id]);
        $data = array(
            'form' => 'detail',
            'item' => $this->mglobal->get_data_all('T_PESAN_MASUK', null, ['SUBSTRING(md5(ID), 6, 8) = ' => $id])[0],
        );
        $this->load->view(strtolower(__CLASS__) . '_form', $data);
    }

    /** Form Balas pesan
     * 
     * @return html form reply pesan
     */
    function reply($id = '') {
        $data = array(
            'form' => 'reply',
            'item' => $this->mglobal->get_data_all('T_PESAN_MASUK', null, ['SUBSTRING(md5(ID), 6, 8) = ' => $id])[0],
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
            'item' => $this->mglobal->get_data_all('T_PESAN_MASUK', null, ['SUBSTRING(md5(ID), 6, 8) = ' => $id])[0],
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
            $subjek = $this->input->post('SUBJEKB');
            $data_pesan = $this->input->post('PESAN');
            $pesan_valid = strip_tags(nl2br($data_pesan), '<br>');

            if ($this->input->post('SUBJEKB') != '' && $this->input->post('SUBJEKB') != 're:[' . $this->input->post('SUBJEKA') . ']') {
                $subjek = $this->input->post('SUBJEKB');
            }

            $data = array(
                'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $this->input->post('ID_PENERIMA'),
                'SUBJEK' => $subjek,
                'PESAN' => $pesan_valid,
                'FILE' => $url,
                'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1'
            );
            $result = $this->mglobal->insert('T_PESAN_KELUAR', $data);

            if ($result) {
                $data2 = array(
                    'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                    'ID_PENERIMA' => $this->input->post('ID_PENERIMA'),
                    'SUBJEK' => $subjek,
                    'PESAN' => $pesan_valid,
                    'FILE' => $url,
                    'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                    'IS_ACTIVE' => '1'
                );
                $this->mglobal->insert('T_PESAN_MASUK', $data2);
            }
            $user = $this->mglobal->get_data_all('T_USER', null, ['ID_USER = ' => $this->input->post('ID_PENERIMA')], 'NAMA,EMAIL')[0];
            $subject = $subjek;
            ng::mail_send($user->EMAIL, $subject, $this->input->post('PESAN'), NULL, 'uploads/mail_out/' . $id . '/' . $url);
        } 
		else if ($this->input->post('act', TRUE) == 'dodelete') {
            $data = array(
                'IS_ACTIVE' => '-1'
            );
            $this->mglobal->update('T_PESAN_MASUK', $data, ['SUBSTRING(md5(ID), 6, 8) = ' => $data_id]);
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
        if (is_null($id)) {
            $q = $_GET['q'];
            $where['user.NAMA LIKE'] = '%' . $q . '%';
            $where['user.IS_ACTIVE'] = '1';
            $where_e = 'user.ID_USER <> ' . $this->session->userdata('ID_USER');
            $select = 'user.ID_USER id_user, user.NAMA nama, satker.INST_NAMA inst_satkerkd';
            $join = [['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD', 'join' => 'left']];
            $contact = $this->mglobal->get_data_all('T_USER user', $join, $where, $select, $where_e, ['user.NAMA', 'ASC']);

            $data = [];
            for ($i = 0; $i < count($contact); $i++) {
                $inst = ($contact[$i]->inst_satkerkd !== null) ? " - (" . $contact[$i]->inst_satkerkd . ")" : "";
                $data[$i] = ['id' => $contact[$i]->id_user, 'name' => '<b>' . $contact[$i]->nama . "</b>" . $inst];
            }

            echo json_encode(['item' => $data]);
        } 
		else {
            $join = [['table' => 'M_INST_SATKER satker', 'on' => 'satker.INST_SATKERKD = user.INST_SATKERKD', 'join' => 'left']];
            $select = 'user.ID_USER id_user, user.NAMA nama, satker.INST_NAMA inst_satkerkd';
            $contact = $this->mglobal->get_data_all('T_USER user', $join, ['user.ID_USER' => $id], $select, null, ['user.NAMA', 'ASC']);
            $inst = ($contact[0]->inst_satkerkd !== null) ? " - (" . $contact[0]->inst_satkerkd . ")" : "";
            $tmp = [
                $data[0] = ['id' => $contact[0]->id_user, 'name' => '<b>' . $contact[0]->nama . "</b>" . $inst],
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
        $select = 'user.NAMA nama, satker.INST_NAMA inst_satkerkd';
        $data = $this->mglobal->get_data_all('T_USER user', $join, ['user.ID_USER' => $id], $select, null, ['user.NAMA', 'ASC']);

        echo json_encode($data);
    }

}

/* End of file inbox.php */
/* Location: ./application/modules/mailbox/controllers/inbox.php */