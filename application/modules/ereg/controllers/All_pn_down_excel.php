<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'modules/ereg/controllers/All_pn.php';

class All_pn_down_excel extends All_pn {

    const RESPONSE_FAILED = '0';
    const RESPONSE_SUCCESS_NOW = '1';
    const RESPONSE_SUCCESS_POSTPONE = '2';
    const ENABLE_LOG_MESSAGE = FALSE;

    private $__call_in_background_mode = FALSE;
    private $__show_spend_time = FALSE;
    public $total_data_pnwl = NULL;
    // num of records per page
    private $limit_write_records = 5;

    /**
     * max execute all
     * min execute just 1
     * @var type 
     */
    private $__execution_time = 'max';

    public function __construct() {
        parent::__construct();
        $this->log_message('info', 'masuk backGroundDownloadFileExcel after construct');
    }

    protected function __init_all_pn() {
        return;
    }

    protected function __check_akses() {

        $method = $this->router->fetch_method();

        if ($method != 'backGroundDownloadFileExcel') {

//            $this->makses->initialize();
//            $this->makses->check_is_read();
        }

        if ($method == 'backGroundDownloadFileExcel') {
            $this->log_message('info', 'masuk backGroundDownloadFileExcel __check_akses');
        }
    }

    protected function __is_logged_in() {
        $method = $this->router->fetch_method();

        if ($method != 'backGroundDownloadFileExcel') {
            call_user_func('ng::islogin');
        }
        if ($method == 'backGroundDownloadFileExcel') {
            $this->log_message('info', 'masuk backGroundDownloadFileExcel __is_logged_in');
        }
    }

    private function __set_memlimit_maxexectime() {
        ini_set('memory_limit', '2048M');
        ini_set('MAX_EXECUTION_TIME', -1);
    }

    private function __get_cfnm_recfnm() {
        return array($this->input->get('cfnm'), $this->input->get('recfnm'));
    }

    private function __get_nama_instansi_by_instansi_id($ins) {
        $this->load->model('Minstansi');
        $instansi = $this->Minstansi->get_by_id($ins)->row();

        $n_instansi = $instansi->INST_NAMA;
        unset($instansi);
        return $n_instansi;
    }

    private function __calculate_excel_offset($page = 1, $limit = 2000) {
        return (($page - 1) * $limit + 1) - 1;
    }

    private function __set_total_data_pnwl_to_null() {
        $this->total_data_pnwl = NULL;
    }

    private function __get_total_data_pnwl($ins = FALSE, $uk_nama = FALSE, $uk_id = NULL, $wl_tahun) {

        if ($this->total_data_pnwl === NULL) {
            $this->total_data_pnwl = $this->mpn->__count_get_all($ins, $uk_nama, $uk_id, $wl_tahun);
        }
        return $this->total_data_pnwl;
    }

    function __write_list_data_to_excel($ins, $uk_nama, $n_instansi, $uk_id) {
        
        $wl_tahun = $this->input->get('CARI[TAHUN_WL]');
        
        $total_record = $this->__get_total_data_pnwl($ins, $uk_nama, $uk_id, $wl_tahun);

        if ($total_record > 0) {
            $page = 1;
            $current_execution = 1;

            $exec_times = ceil($total_record / $this->limit_write_records);

            if ($this->__execution_time == 'min') {
                $exec_times = 1;
            }
            $str_tr = "";

            $rowStart = 1;
            $ssIndex = 5;

            $xlTableRowCount = $total_record + 37;

            while ($current_execution <= $exec_times) {
                $offset = $this->__calculate_excel_offset($current_execution, $this->limit_write_records);
                $result_list_data = $this->mpn->get_all_limited($ins, $uk_nama, $this->limit_write_records, $offset, $uk_id, $wl_tahun);

                foreach ($result_list_data as $dataRow) {

                    $str_tr .= $this->load->view('all_pn/xl/xl_table_row_list_data_pnwl', [
                        "no" => $rowStart,
                        "dataRow" => $dataRow,
                        "rowSsIndex" => $ssIndex
                            ], TRUE);

                    $rowStart++;
                    $ssIndex++;
                }
                unset($result_list_data);
                $current_execution++;
            }

            $table_string = $this->load->view('all_pn/xl/xl_table_list_data_pnwl', [
                "n_instansi" => $n_instansi,
                "tr_records" => $str_tr,
                "tblRowCount" => $xlTableRowCount
                    ], TRUE);

            return $table_string;
        } return FALSE;
    }

    function __write_list_data_instansi_to_excel($ins) {

        $total_record = $this->mpn->__count_get_all_instansi($ins);
        if ($total_record > 0) {
            $page = 1;
            $current_execution = 1;

            $exec_times = ceil($total_record / $this->limit_write_records);

            if ($this->__execution_time == 'min') {
                $exec_times = 1;
            }
            $str_tr = "";

            $rowStart = 1;
            $ssIndex = 4;

            $xlTableRowCount = $total_record + 5;

            while ($current_execution <= $exec_times) {
                $offset = $this->__calculate_excel_offset($current_execution, $this->limit_write_records);
                $result_list_data = $this->mpn->get_all_instansi_limited($ins, $this->limit_write_records, $offset);
                foreach ($result_list_data as $dataRow) {

                    $str_tr .= $this->load->view('all_pn/xl/xl_table_row_list_data_instansi_pnwl', [
                        "no" => $rowStart,
                        "dataRow" => $dataRow,
                        "rowSsIndex" => $ssIndex
                            ], TRUE);

                    $rowStart++;
                    $ssIndex++;
                }
                unset($result_list_data);
                $current_execution++;
            }

            $table_string = $this->load->view('all_pn/xl/xl_table_list_data_instansi_pnwl', [
                "tr_records" => $str_tr,
                "tblRowCount" => $xlTableRowCount
                    ], TRUE);

            return $table_string;
        }
        return FALSE;
    }

    function __write_list_data_unit_kerja_to_excel($ins, $uk_id) {

        $total_record = $this->mpn->__count_get_all_unit_kerja($ins, $uk_id);
        if ($total_record > 0) {
            $page = 1;
            $current_execution = 1;

            $exec_times = ceil($total_record / $this->limit_write_records);

            if ($this->__execution_time == 'min') {
                $exec_times = 1;
            }
            $str_tr = "";

            $rowStart = 1;
            $ssIndex = 4;

            $xlTableRowCount = $total_record + 3;

            while ($current_execution <= $exec_times) {
                $offset = $this->__calculate_excel_offset($current_execution, $this->limit_write_records);
                $result_list_data = $this->mpn->get_all_unit_kerja_limited($ins, $uk_id, $this->limit_write_records, $offset);
                foreach ($result_list_data as $dataRow) {

                    $str_tr .= $this->load->view('all_pn/xl/xl_table_row_list_data_unit_kerja_pnwl', [
                        "no" => $rowStart,
                        "dataRow" => $dataRow,
                        "rowSsIndex" => $ssIndex
                            ], TRUE);

                    $rowStart++;
                    $ssIndex++;
                }
                unset($result_list_data);
                $current_execution++;
            }

            $table_string = $this->load->view('all_pn/xl/xl_table_list_data_unit_kerja_pnwl', [
                "tr_records" => $str_tr,
                "tblRowCount" => $xlTableRowCount
                    ], TRUE);

            return $table_string;
        }
        return FALSE;
    }

    function __write_list_data_sub_unit_kerja_to_excel($ins, $uk_id) {

        $total_record = $this->mpn->__count_get_all_sub_unit_kerja($ins, $uk_id);
        if ($total_record > 0) {
            $page = 1;
            $current_execution = 1;

            $exec_times = ceil($total_record / $this->limit_write_records);

            if ($this->__execution_time == 'min') {
                $exec_times = 1;
            }
            $str_tr = "";

            $rowStart = 1;
            $ssIndex = 4;

            $xlTableRowCount = $total_record + 3;

            while ($current_execution <= $exec_times) {
                $offset = $this->__calculate_excel_offset($current_execution, $this->limit_write_records);
                $result_list_data = $this->mpn->get_all_sub_unit_kerja_limited($ins, $uk_id, $this->limit_write_records, $offset);
                foreach ($result_list_data as $dataRow) {

                    $str_tr .= $this->load->view('all_pn/xl/xl_table_row_list_data_sub_unit_kerja_pnwl', [
                        "no" => $rowStart,
                        "dataRow" => $dataRow,
                        "rowSsIndex" => $ssIndex
                            ], TRUE);

                    $rowStart++;
                    $ssIndex++;
                }
                unset($result_list_data);
                $current_execution++;
            }

            $table_string = $this->load->view('all_pn/xl/xl_table_list_data_sub_unit_kerja_pnwl', [
                "tr_records" => $str_tr,
                "tblRowCount" => $xlTableRowCount
                    ], TRUE);

            return $table_string;
        }
        return FALSE;
    }

    function __write_list_data_jabatan_to_excel($ins, $uk_id) {

        $total_record = $this->mpn->__count_get_all_jabatan($ins, $uk_id);
        if ($total_record > 0) {
            $page = 1;
            $current_execution = 1;

            $exec_times = ceil($total_record / $this->limit_write_records);

            if ($this->__execution_time == 'min') {
                $exec_times = 1;
            }
            $str_tr = "";

            $rowStart = 1;
            $ssIndex = 4;

            $xlTableRowCount = $total_record + 3;


            while ($current_execution <= $exec_times) {
                $offset = $this->__calculate_excel_offset($current_execution, $this->limit_write_records);
                $result_list_data = $this->mpn->get_all_jabatan_limited($ins, $uk_id, $this->limit_write_records, $offset);
                foreach ($result_list_data as $dataRow) {

                    $str_tr .= $this->load->view('all_pn/xl/xl_table_row_list_data_jabatan', [
                        "no" => $rowStart,
                        "dataRow" => $dataRow,
                        "rowSsIndex" => $ssIndex
                            ], TRUE);

                    $rowStart++;
                    $ssIndex++;
                }
                unset($result_list_data);
                $current_execution++;
            }

            $table_string = $this->load->view('all_pn/xl/xl_table_list_data_jabatan', [
                "tr_records" => $str_tr,
                "tblRowCount" => $xlTableRowCount
                    ], TRUE);

            return $table_string;
        }
        return FALSE;
    }

    private function __evaluate_uk_id_on_url_query($uk_id) {
        $passed_uk_id = $this->input->get('CARI[UNIT_KERJA]');

        if ($passed_uk_id && $passed_uk_id != '') {

            $uk_id = $passed_uk_id;
        }

        return $uk_id;
    }

    private function __write_excel($ins, $uk_nama, $n_instansi, $uk_id) {
//        gwe_dump($uk_id, TRUE);


//        $uk_id = $this->__evaluate_uk_id_on_url_query($uk_id);


        $arr_workSheets = [];
        $workSheet = [];
        $arr_sheetsName = ["Pnwl", "Instansi", "UnitKerja", "SubUnitKerja", "Jabatan"];
        $this->log_message('info', 'masuk backGroundDownloadFileExcel start write pnwl');
        $workSheet["Pnwl"] = $this->__write_list_data_to_excel($ins, $uk_nama, $n_instansi, $uk_id);
        $this->log_message('info', 'masuk backGroundDownloadFileExcel start write master data instansi');
        $workSheet["Instansi"] = $this->__write_list_data_instansi_to_excel($ins);
        $this->log_message('info', 'masuk backGroundDownloadFileExcel start write master data unit kerja');
        $workSheet["UnitKerja"] = $this->__write_list_data_unit_kerja_to_excel($ins, $uk_id);
        $this->log_message('info', 'masuk backGroundDownloadFileExcel start write master data sub unit kerja');
        $workSheet["SubUnitKerja"] = $this->__write_list_data_sub_unit_kerja_to_excel($ins, $uk_id);
        $this->log_message('info', 'masuk backGroundDownloadFileExcel start write master data jabatan');
        $workSheet["Jabatan"] = $this->__write_list_data_jabatan_to_excel($ins, $uk_id);

        foreach ($arr_sheetsName as $sheetName) {
            if (array_key_exists($sheetName, $workSheet) && $workSheet[$sheetName] !== FALSE) {
                $arr_workSheets[] = $workSheet[$sheetName];
            }
        }
        unset($arr_sheetsName, $workSheet);

        return $arr_workSheets;
    }

    function __save_file_excel($workSheets, $filename = "export") {

        $workbookTemplate = $this->load->view('all_pn/xl/xl_export_table_list_data_pnwl', [
            "worksheets" => $workSheets
                ], TRUE);

        file_put_contents(APPPATH . "/../download/pnwl_excel/" . $filename . '.xls', $workbookTemplate);

        if (!$this->__call_in_background_mode) {
            echo $filename . '.xls';
            exit;
        }
        return;
    }

    private function __is_created_filename_ok($created_file_name, $re_created_file_name) {
        /**
         * gagal generate file excel
         */
        if ($created_file_name === FALSE && $re_created_file_name === FALSE) {
            echo self::RESPONSE_FAILED;
            exit;
        }
    }

    function backGroundDownloadFileExcel() {

        $this->__call_in_background_mode = TRUE;

        $this->log_message('info', 'masuk backGroundDownloadFileExcel start function');

        ini_set('max_execution_time', 0);


        $idUser = $this->input->get('rerecfnm');
        $idRole = $this->input->get('rerecfnmr');

        $starttime = $this->input->get('starttime');

        $newdata = array(
            'ID_ROLE' => $idRole,
            'ID_USER' => $idUser,
        );

        $this->session->set_userdata($newdata);

        list($ins, $uk_nama, $n_instansi, $uk_id, $filename, $created_file_name, $re_created_file_name) = $this->__init();

        $this->log_message('info', 'backGroundDownloadFileExcel start Execution Process');
        $this->__execute_process($ins, $uk_nama, $n_instansi, $uk_id, $filename);
        $this->log_message('info', 'backGroundDownloadFileExcel finish Execution Process');
        $this->log_message('info', 'backGroundDownloadFileExcel Prepare send mail');

        /**
         * dari titik ini tidak perlu di upload ke server kecuali text pesan
         */
        $pesan = "File excel PN/WL Instansi Saudara telah siap untuk diupdate, silahkan klik link berikut ini untuk membuka file tersebut <a href=\"" . base_url() . "download/pnwl_excel/" . $filename . ".xls\">" . base_url() . "download/pnwl_excel/" . $filename . ".xls</a>";
        /**
         * selesai disini
         */
        $this->load->model('Msuratkeluar');
        $this->log_message('info', 'backGroundDownloadFileExcel send mail');

        $endtime = microtime(true);

        $spendtime = number_format(($endtime - $starttime), 2);

        if ($this->__show_spend_time) {
            $pesan .= " membutuhkan waktu " . $spendtime . " detik.";
        }

        $this->Msuratkeluar->send_message($idUser, $idUser, "", "Download Excel", $pesan, FALSE);
        $this->log_message('info', 'backGroundDownloadFileExcel finish send mail');
        ini_set('max_execution_time', 30);
    }

    private function __init() {
        $this->__set_memlimit_maxexectime();

        $ins = $this->get_instansi_daftar_pn_wl_via_excel();

        list($created_file_name, $re_created_file_name) = $this->__get_cfnm_recfnm();

        $this->__is_created_filename_ok($created_file_name, $re_created_file_name);

        $filename = $created_file_name . "_" . $re_created_file_name;
//
        $n_instansi = $this->__get_nama_instansi_by_instansi_id($ins);

        list($uk_id, $uk_nama) = $this->__get_current_uk_nama(TRUE);

        return array($ins, $uk_nama, $n_instansi, $uk_id, $filename, $created_file_name, $re_created_file_name);
    }

    private function __execute_process($ins, $uk_nama, $n_instansi, $uk_id, $filename) {
        $arr_workSheets = $this->__write_excel($ins, $uk_nama, $n_instansi, $uk_id);
        $workSheets = implode(" ", $arr_workSheets);
        $this->__save_file_excel($workSheets, $filename);
    }

    function downloadFileExcel() {

        list($ins, $uk_nama, $n_instansi, $uk_id, $filename, $created_file_name, $re_created_file_name) = $this->__init();

        $response = $filename;
        $uk_id = $this->__evaluate_uk_id_on_url_query($uk_id);
//        $total_data_pnwl = $this->__get_total_data_pnwl($ins, $uk_nama, $uk_id);

        $this->log_message('info', 'all_pn_down_excel prepare');

//        if ($total_data_pnwl <= 2000) {
            $this->__execute_process($ins, $uk_nama, $n_instansi, $uk_id, $filename);
//        } else {
//
//            /*
//             * 
//             * komentar dibawah ini HARAM untuk dihapus
//             * 
//              //   http://10.102.2.22/elhkpndev/index.php/ereg/all_pn_down_excel/downloadFileExcel?
//              //   CARI[INSTANSI]=30&
//              //   cfnm=l9AYs&
//              //   recfnm=tAXvC&
//              //   _=1488339028960
//             * 
//             */
//
//            $randnum_a = $this->__uniqueRandomNumbersWithinRange(200, 300, 3);
//            $randnum_b = $this->__uniqueRandomNumbersWithinRange(200, 300, 3);
//
//            $url = $this->config->item('curl_url') . 'index.php/ereg/all_pn_down_excel/backGroundDownloadFileExcel/?CARI[INSTANSI]=' . $ins
//                    . '&cfnm=' . $created_file_name
//                    . '&recfnm=' . $re_created_file_name
//                    . '&rerecfnm=' . $this->session->userdata['ID_USER']
//                    . '&rerecfnmr=' . $this->session->useradata['ID_ROLE']
//                    . '&starttime=' . microtime(true);
//
//            $this->curl_call_background($url, 1, 1);
//
//            $response = self::RESPONSE_SUCCESS_POSTPONE;
//        }

        $this->__set_total_data_pnwl_to_null();
        echo $response;
        exit;
    }

    function __save_file_excel_bck($workSheets, $filename = "export") {


        $workbookTemplate = $this->load->view('all_pn/xl/xl_export_table_list_data_pnwl', [
            "worksheets" => $workSheets
                ], TRUE);

        header('Content-type:');
        header('Expires:');

        header('Content-type: application/octet-stream');
        header("Pragma: no-cache");
        header("Expires: 0");
        header('Content-disposition: attachment; filename=' . $filename . '.xls');
        echo $workbookTemplate;
        exit;
    }
    
    public function DownUpExcels() {
        $arr_response = $this->get_instansi_daftar_pn_wl_via_excel(1);
        extract($arr_response);
        $qurl_uk = "";
        $param_uk = $uk;
        if ($uk) {
            $qurl_uk = "&CARI[UNIT_KERJA]=" . $uk;
        } else {
            $uk = "";
            $param_uk = FALSE;
        }
        $query_url_download = $arr_response["instansi_found"] ? "" : "?CARI[INSTANSI]=" . $ins . $qurl_uk;
//        $query_url_download = $arr_response["instansi_found"] ? "" : "?CARI[INSTANSI]=" . $ins . $qurl_uk;
        if ($uk && $arr_response["instansi_found"]) {
            $query_url_download .= "?weHIDSJ=IHIUDSAJ&" . $qurl_uk;
        }
        $thn_wl = $this->input->get('CARI[TAHUN_WL]');
        $query_url_download .= '&CARI[TAHUN_WL]='.$thn_wl;
//gwe_dump(array($uk, $instansi_found, $arr_response["instansi_found"], $ins), TRUE);
        $array_data = array();
        $this->load->model('Mt_pn_upload_xls_temp', '', TRUE);

        $this->load->model('Mt_pn_ver_pnwl');

        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
            )),
            'list_ver_pnwl' => $this->Mt_pn_ver_pnwl->get_count_ver_pnwl_jabatan($ins, $param_uk),
            'list_ver_pnwl_tambah' => $this->Mt_pn_ver_pnwl->get_count_ver_pnwl_tambahan($ins, $param_uk),
//            'list_ver_pnwl_non_aktif' => $this->Mt_pn_ver_pnwl->get_count_ver_pnwl_nonact($ins),
//            'list_cek_temp' => $this->Mt_pn_ver_pnwl->get_count_temp_uk_suk($ins),
            "query_url_download" => $query_url_download,
            'INST_SATKERKD' => $ins,
            'UK_ID' => $uk
        );

        $data = array_merge($data, $arr_response);
		//echo $data['list_ver_pnwl_tambah'];exit;
        $this->load->view('v_exc_downup', $data);
    }

}
