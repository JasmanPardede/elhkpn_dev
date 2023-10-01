<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Validasi_Excel_Controller
 *
 * @author nurfadillah
 */
class Validasi_Excel_Controller extends MY_Controller {

    protected $prefix_table_name = 't_imp_xl_lhkpn_';
    protected $prefix_pk = 'id_imp_xl_lhkpn_';
    protected $detect_table_name = TRUE;
    protected $is_harta = FALSE;
    protected $posted_fields = array();
    protected $upload_harta_location = "";
    protected $posted_primary_key = FALSE;
    protected $current_id_imp_xl_lhkpn = FALSE;

    public function __construct() {
        parent::__construct();
        $this->check_akses();
        $this->load->model('mglobal');
        $this->load->helper('form');
        $this->config->load('harta');
    }

    /**
     * @return void
     * @param type $id
     */
    protected function before_get_detail($id) {
        
    }

    /**
     * 
     * @param record_set $result
     * @param int $id
     * @return record_set
     */
    protected function after_get_detail($result, $id) {
        //echo $result;exit;
        return $result;
    }

    protected function detect_postfix() {
        $current_class = get_called_class();
        return str_replace("validasi_", "", strtolower($current_class));
    }

    protected function get_postfix() {
        $post_fix = $this->detect_postfix();

        if (!$this->detect_table_name) {
//            $post_fix = $this->get_postfix();
        }

        return $post_fix;
    }

    protected function get_table_name() {
        return $this->prefix_table_name . $this->get_postfix();
    }

    protected function get_pk_without_table_name() {
        return $this->prefix_pk . $this->get_postfix();
    }

    protected function clear_money_mask() {
        foreach (array(
            "A0",
            "A1",
            "A2",
            "A3",
            "A4",
            "PA0",
            "PA1",
            "PA2",
            "PA3",
            "PA4",
            "B0",
            "B1",
            "B2",
            "B3",
            "C0",
            "C1",
            "C2",
            "C3",
            "NILAI_PELEPASAN", 
            "nilai_pelepasan", 
            "SALDO_HUTANG", 
            "saldo_hutang", 
            "AWAL_HUTANG", 
            "awal_hutang", 
            "NILAI_EQUIVALEN", 
            "nilai_equivalen", 
            "NILAI_SALDO", 
            "nilai_saldo", 
            "NILAI_PEROLEHAN", 
            "nilai_perolehan", 
            "NILAI_PELAPORAN", 
            "nilai_pelaporan") as $key) {
            if (array_key_exists($key, $_POST)) {
                $_POST[$key] = str_replace(",", ".", str_replace(".", "", $_POST[$key]));
            }
        }
    }

    protected function get_primary_key() {
        $table_name = $this->get_table_name();
        $pk_without_table_name = $this->get_pk_without_table_name();
        $primary_key = $table_name . "." . $pk_without_table_name;
        unset($table_name, $pk_without_table_name);
        return $primary_key;
    }

    protected function get_detail($id = FALSE, $select_fields = "*") {
        if ($id) {
            extract($this->collect_form_properties(FALSE));

            $this->before_get_detail($id);

            $this->db->select($select_fields);
            $data_detail = $this->mglobal->secure_get_by_id($table_name, $primary_key, $pk_without_table_name, $id);

            if ($data_detail)
                return $this->after_get_detail($data_detail, $id);
        }
        return FALSE;
    }

    protected function edit_before_load_view($data_detail = FALSE) {
        return $data_detail;
    }

    protected function edit_after_load_view() {
        
    }

    public function edit($id = FALSE) {
        if ($id) {
            $data["item"] = $this->get_detail($id);
            if ($data["item"]) {
                $data = $this->edit_before_load_view($data);

                if ($this->is_harta) {
                    $this->load->view('lhkpn/v_include_plugin_js');
                    $this->load->view('lhkpn/v_include_js');
                }

                $data["action"] = "update_perubahan";

                $this->load->view(strtolower(get_called_class()) . '/edit_form', $data);
                $this->edit_after_load_view(strtolower(get_called_class()) . '/edit_form');
            }
        } else {
            echo "Tidak ada ditemukan 
			<br><br>
			<button type='reset' class='btn btn-danger btn-sm ' onclick='CloseModalBox2();'><i class='fa fa-remove'></i>Batal</button>";
        }
    }

    public function edit_tgl_terima($id = FALSE) {
        if ($id) {
            $data["item"] = $this->get_detail($id);
            if ($data["item"]) {
                $data = $this->edit_before_load_view($data);

                if ($this->is_harta) {
                    $this->load->view('lhkpn/v_include_js');
                }

                $this->load->view(strtolower(get_called_class()) . '/edit_tgl_terima', $data);
                $this->edit_after_load_view(strtolower(get_called_class()) . '/edit_tgl_terima');
            }
        } else {
            echo "Tidak ada ditemukan 
            <br><br>
            <button type='reset' class='btn btn-danger btn-sm ' onclick='CloseModalBox2();'><i class='fa fa-remove'></i>Batal</button>";
        }
    }

    protected function update_perubahan_before_check_post() {
        
    }

    protected function get_posted_data() {
        $posted_data = array();
        foreach ($this->posted_fields as $posted_field) {
            $pdata = $this->input->post($posted_field);
            $posted_data[$posted_field] = NULL;
            if ($pdata) {
                $posted_data[$posted_field] = $pdata != '' ? $pdata : NULL;
            }
        }
        return $posted_data;
    }

    protected function collect_form_properties($with_post_data = TRUE) {

        $form_properties = [
            "post_fix" => $this->get_postfix(),
            "table_name" => $this->get_table_name(),
            "pk_without_table_name" => $this->get_pk_without_table_name()
        ];
        if ($with_post_data) {
            $form_properties["posted_data"] = $this->get_posted_data();
        } else {
            $form_properties["primary_key"] = $this->get_primary_key();
        }

        return $form_properties;
    }

    public function update_perubahan() {

        $this->clear_money_mask();
        $this->update_perubahan_before_check_post();

        if (!empty($_POST)) {

            extract($this->collect_form_properties());

            $id = $posted_data[$pk_without_table_name];

            $posted_data = $this->before_update_perubahan($posted_data, $id);

            $this->posted_primary_key = $posted_data[$pk_without_table_name];
            unset($posted_data[$pk_without_table_name]);

            $posted_data['UPDATED_TIME'] = date('d-m-Y H:i:s');
            $posted_data['UPDATED_BY'] = $this->username;

            $result = $this->mglobal->secure_update_data($table_name, $posted_data, $pk_without_table_name, $id);
            $this->after_update_perubahan($posted_data, $id);

            if ($result) {
                echo "1";
                exit;
            }
        }
        echo "0";
        exit;
    }

    protected function before_update_perubahan($posted_data, $detected_primary_key_value) {
        return $posted_data;
    }

    protected function after_update_perubahan($posted_data, $detected_primary_key_value) {
        return;
    }

    public function hapus($id = FALSE) {
        if ($id) {

            $post_fix = $this->get_postfix();

//            $table_name = $this->prefix_table_name . $post_fix;
            $table_name = $this->get_table_name();
//            $pk_without_table_name = $this->prefix_pk . $post_fix;
            $pk_without_table_name = $this->get_pk_without_table_name();

            $this->mglobal->secure_delete_data($table_name, $pk_without_table_name, $id);
            echo "1";
            exit;
        }
        echo "0";
        exit;
    }

    private function get_file_bukti_uploaded($id_imp_xl_lhkpn) {
        
    }

    protected function set_file_list($data_detail = FALSE) {
        $data_detail['file_list'] = array("-- Pilih --");

        if ($data_detail && ($data_detail['item'] || $this->current_id_imp_xl_lhkpn)) {
            if (property_exists($data_detail['item'], 'id_imp_xl_lhkpn')) {
                $file_list = $this->lhkpn_temp_file_bukti($data_detail['item']->id_imp_xl_lhkpn);
            } elseif (property_exists($data_detail['item'], 'ID_imp_xl_LHKPN')) {
                $file_list = $this->lhkpn_temp_file_bukti($data_detail['item']->ID_imp_xl_LHKPN);
            } elseif ($this->current_id_imp_xl_lhkpn) {
                $file_list = $this->lhkpn_temp_file_bukti($this->current_id_imp_xl_lhkpn);
            }
            $data_detail['file_list'] = array_combine($file_list, $file_list);
            unset($file_list);
            array_unshift($data_detail['file_list'], "-- Pilih --", " ");
        }
        return $data_detail;
    }

    protected function get_id_imp_lhkpn($record = FALSE) {
        if ($record) {
            if (property_exists($record, 'ID_imp_xl_LHKPN')) {
                return $record->ID_imp_xl_LHKPN;
            }
            if (property_exists($record, 'id_imp_xl_lhkpn')) {
                return $record->id_imp_xl_lhkpn;
            }
        }
        return FALSE;
    }

    protected function save_to_folder_nik($data_detail = FALSE, $detected_id = FALSE, $column_name = "FILE_BUKTI") {
        if (!$data_detail || !$detected_id) {
            return FALSE;
        }

        if ($data_detail[$column_name] == '') {
            return FALSE;
        }

        $detail_data_harta = $this->mglobal->secure_get_by_id($this->get_table_name(), $this->get_primary_key(), $this->get_pk_without_table_name(), $detected_id);

        if (!$detail_data_harta) {
            return FALSE;
        }

        $data_penerimaan = $this->get_lhkpn_penerimaan_by_id_temp_lhkpn($this->get_id_imp_lhkpn($detail_data_harta));
        unset($detail_data_harta);

        if (!$data_penerimaan) {
            return FALSE;
        }

        $rand_id = $data_penerimaan->RAND_ID;
        $data_pn = $this->mglobal->get_by_id("T_PN", "ID_PN", $data_penerimaan->ID_PN);
        unset($data_penerimaan);

        if (!$data_pn) {
            return FALSE;
        }

        $encrypted_username = encrypt_username($data_pn->NIK, 'e');

        // Make destination directory
        if (!is_dir("./uploads/" . $this->upload_harta_location . "/" . $encrypted_username . "/")) {
            mkdir("./uploads/" . $this->upload_harta_location . "/" . $encrypted_username . "/", 0777, TRUE);
        }

        copyr(self::DIR_TEMP_UPLOAD . $rand_id . "/" . $data_detail[$column_name], "./uploads/" . $this->upload_harta_location . "/" . $encrypted_username . "/" . $data_detail[$column_name]);

        unset($encrypted_username, $data_pn, $rand_id);
        return TRUE;
    }

    /**
     * @return array Data untuk ditayangkan di view
     */
    protected function before_add_load_view() {
        return [];
    }

    protected function after_add() {
        
    }

    protected function tambah_data_before_check_post() {
        
    }

    public function tambah_data($id_imp_xl_lhkpn = FALSE) {
        
        $this->clear_money_mask();
        $this->tambah_data_before_check_post();

        if (!empty($_POST) && $id_imp_xl_lhkpn) {

            extract($this->collect_form_properties());

            $posted_data = $this->before_tambah_data($posted_data);

            $posted_data['CREATED_TIME'] = date('d-m-Y H:i:s');
            $posted_data['CREATED_BY'] = $this->username;
            $posted_data['CREATED_IP'] = $_SERVER["REMOTE_ADDR"];
            $posted_data['id_imp_xl_lhkpn'] = $id_imp_xl_lhkpn;

            $result = $this->mglobal->insert($table_name, $posted_data);
            $this->after_tambah_data($posted_data, $result['id'], $result['status']);

            if ($result['status']) {
                echo $result['id'];
                exit;
            }
        }
        echo "0";
        exit;
    }

    protected function before_tambah_data($posted_data) {
        return $posted_data;
    }

    protected function after_tambah_data($posted_data, $insert_id, $status) {
        return;
    }

    public function add($id_imp_xl_lhkpn = FALSE) {

        if (!$id_imp_xl_lhkpn) {
            show_404();
            exit;
        }

        if ($this->is_harta) {
            $this->load->view('lhkpn/v_include_js');
        }

        $this->current_id_imp_xl_lhkpn = $id_imp_xl_lhkpn;

        $data = array_merge($this->before_add_load_view(), ["action" => "tambah_data/" . $id_imp_xl_lhkpn, "onAdd" => TRUE]);

        $this->load->view(strtolower(get_called_class()) . '/edit_form', $data);
        $this->edit_after_load_view(strtolower(get_called_class()) . '/edit_form');

        $this->after_add();
    }

}
