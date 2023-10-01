<?php
/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Helper lhkpn_helper
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Helper
 */

/** Get setting value for current session user login
 *
 * @param find setting
 * @param source session | table
 * @return setting value
 */
function createRandomPassword($length) {
    $chars = "23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
    $i = 0;
    $password = "";
    while ($i <= $length) {
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        $i++;
    }
    return $password;
}

if (!function_exists('show_date_with_format')) {

    function show_date_with_format($stringdate = "", $format = "d-m-Y", $current_format = "Y-m-d", $default_value = "none") {
        $return_date = "";
        if ($stringdate !== NULL && $stringdate != "" && strtolower($stringdate) != "null" && strtotime($stringdate) != FALSE) {
            $return_date = date($format, strtotime($stringdate));
        }
        return $return_date;
    }

}

if (!function_exists('copyr')) {

    /**
     * Copy a file, or recursively copy a folder and its contents
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     * @param       string   $source    Source path
     * @param       string   $dest      Destination path
     * @return      bool     Returns TRUE on success, FALSE on failure
     */
    function copyr($source, $dest, $show_log = FALSE) {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, 0777, TRUE);
        }

        if ($show_log) {
            var_dump('is_dir', is_dir($dest), $dest);
        }

        // Loop through the folder
        $dir = dir($source);
        if ($dir) {
            while (false !== $entry = $dir->read()) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                // Deep copy directories
                copyr("$source/$entry", "$dest/$entry");
            }

            // Clean up
            $dir->close();
        }
        return true;
    }

}

if (!function_exists('string_to_date')) {

    function string_to_date($str_date, $format = 'd-m-Y') {

        if ($str_date != FALSE && $str_date != NULL && $str_date != '')
            return date($format, strtotime($str_date));
        else
            return '';
    }

}

function random_word($length = 5) {
    $chars = "34689abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $i = 0;
    $captcha = "";
    while ($i < $length) {
        $captcha .= $chars[mt_rand(0, strlen($chars) - 1)];
        $i++;
    }
    return $captcha;
}

function getSetting($find, $source = 'session') {
    $CI = & get_instance();
    if ($source == 'session') {
        $USER_SETTINGS = $CI->session->userdata('USER_SETTINGS');
    } else if ($source == 'table') {
        $sql = "SELECT * FROM T_SETTINGS WHERE OWNER = '" . $CI->session->userdata('PEG_NIP_BARU') . "'";
        $USER_SETTINGS = $CI->db->query($sql)->result();
    }
    foreach ($USER_SETTINGS as $item) {
        if ($item->SETTING == $find) {
            return $item->VALUE;
        }
    }
}

if (!function_exists('gwe_dump')) {

    function gwe_dump($variable_to_dump = NULL, $exit_after_dump = FALSE) {

        if (is_array($variable_to_dump)) {
            foreach ($variable_to_dump as $var_d) {
                var_dump($var_d);
                echo "<br /><br /><br /><br />";
            }
        } else {
            var_dump($variable_to_dump);
        }

        if ($exit_after_dump) {
            exit;
        }

        return;
    }

}

/** Get setting value for current cursor user
 *
 * @return setting value
 */
function getUserSetting($find, $USER_SETTINGS) {
    foreach ($USER_SETTINGS as $item) {
        if ($item->SETTING == $find) {
            return $item->VALUE;
        }
    }
}

/** Get setting value for current cursor user
 *
 * @return setting value
 */
function getOwnerSetting($SETTING, $OWNER) {
    $CI = & get_instance();
    $sql = "SELECT *
        FROM T_SETTINGS
        WHERE SETTING LIKE '" . $SETTING . "' AND OWNER LIKE '" . $OWNER . "'";
    $USER_SETTINGS = $CI->db->query($sql);
    if ($USER_SETTINGS->num_rows() > 0) {
        foreach ($USER_SETTINGS->result() as $item) {
            if ($item->SETTING == $SETTING && $item->OWNER == $OWNER) {
                return $item->VALUE;
            }
        }
    }
    return 0;
}

/** Get Umur
 *
 * @todo tests
 * @return umur
 */
function getUmur($date, $date2 = '', $showdate = true) {
    $date1 = strtotime(date("d-m-Y H:i:s", $date));
    if ($date2 != '') {
        $date2 = strtotime(date("d-m-Y H:i:s", $date2));
    } else {
        $date2 = strtotime('now');
    }
    $subTime = $date2 - $date1;
    $y = ($subTime / (60 * 60 * 24 * 365));
    $d = ($subTime / (60 * 60 * 24)) % 365;
    $h = ($subTime / (60 * 60)) % 24;
    $m = ($subTime / 60) % 60;

    // echo "Difference between ".date('Y-m-d H:i:s',$date1)." and ".date('Y-m-d H:i:s',$date2)." is:\n";
    // echo '<br>';
    if ($showdate == true) {
        echo date("d-m-Y H:i:s", $date);
    }

    echo '<span style="font-size:10px;color:blue;"> (';
    echo floor($y) ? floor($y) . " years\n" : "";
    echo floor($d) ? floor($d) . " days\n" : "";
    echo floor($h) ? floor($h) . " hours\n" : "";
    echo floor($m) ? floor($m) . " minutes\n" : "";
    echo ')</span>';
}

function getHitungUmur($tgl_lahir,$tgl_kirim_final = NULL){
    $date1 = new DateTime(date('Y-m-d', strtotime($tgl_lahir)));
    if (!empty($tgl_kirim_final)) {
        $date2 = new DateTime(date('Y-m-d', strtotime($tgl_kirim_final)));
    } else {
        $date2 = new DateTime(date('Y-m-d'));
    }
    if ($date1 > $date2) {
        return "Belum Lahir";
    } else {
        $interval = $date1->diff($date2);
        //echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
        $data = array(
            'year'=>$interval->y,
            'month'=>$interval->m,
            'day'=>$interval->d
        );
        if ($interval->y == 0) {
            if($interval->m == 0) {
                return "Belum 1 Bulan";
            } else {
                return $interval->m . " Bulan";
            }
        } else {
            if($interval->m == 0) {
                return $interval->y . " Tahun";
            } else {
                return $interval->y . " Tahun " . $interval->m . " Bulan";
            }
        }
    }
}

/**
 * Merubah ID_PROV, ID_KABKOT, ID_KEC dan ID_KEL menjadi nama dari masing - masing ID
 */
function getArea($id_prov = '', $id_kabkot = '', $id_kec = '', $id_kel = '') {
    $CI = & get_instance();
    $error[0]['NAME'] = 'Area tidak ada !';

    $sql = "SELECT NAME FROM M_AREA WHERE IDPROV = '" . $id_prov . "' AND IDKOT = '" . $id_kabkot . "' AND IDKEC = '" . $id_kec . "' AND IDKEL = '" . $id_kel . "' ORDER BY NAME ASC";
    $result = $CI->db->query($sql);

    if ($result->num_rows() > 0) {
        return $result->result();
    }

    return $error;
}

/**
 * Get Negara
 */
function getNegara($id_negara) {
    $CI = & get_instance();
    $error[0]['NAME'] = 'Area tidak ada !';

    $sql = "SELECT NAMA_NEGARA FROM M_NEGARA WHERE ID = '" . $id_negara . "'";
    $result = $CI->db->query($sql);

    if ($result->num_rows() > 0) {
        return $result->result();
    }

    return $error;
}

function GetFormHelp($n_form) {
    $CI = & get_instance();
    $error[0]['FORM_TITLE'] = 'Area tidak ada !';
    $sql = "SELECT * FROM m_bantuan WHERE NAME_FORM = '" . $n_form . "'";
    $result = $CI->db->query($sql);
    if ($result->num_rows() > 0) {
        return $result->row();
    }
    return $error;
}

function FormHelpPopOver($n_form) {
    $popover = NULL;
    if (@GetFormHelp($n_form)->ID)
        $popover = '<a data-toggle="popover" title="' . @GetFormHelp($n_form)->FORM_TITLE . '" data-content="' . @GetFormHelp($n_form)->FORM_DESC . '" data-placement="right" class="over"><i class="fa fa-info-circle"></i></a>';
    return $popover;
}

function FormHelpPlaceholderToolTip($n_form, $tooltips = FALSE) {
    $placeholder = NULL;
    $tooltips = $tooltips ? 'data-toggle="tooltip" title="' . @GetFormHelp($n_form)->FORM_TITLE . '"' : '';
    if (@GetFormHelp($n_form)->ID)
        $placeholder = 'placeholder="' . GetFormHelp($n_form)->FORM_HELP . '" ' . $tooltips . '';
    return $placeholder;
}

function FormHelpAccordionEfiling($p_name, $call = NULL) {
    $CI = & get_instance();
    $call = $call ? $call : NULL;
    $sql = "SELECT * FROM m_petunjuk_efiling WHERE PETUNJUK_NAME = '" . $p_name . "'";
    $result = $CI->db->query($sql);
    $data = $result->row();
    $html = '<div class="box-header with-border title-alat title-box">
                <div class="accordion" id="accordion2">
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse' . $call . '" style="color:#fff;background:#ff9100;border-radius:5px;padding:3px 10px;text-decoration:none;">
                               <h5 class="" style="font-weight: bold;">"' . $data->PETUNJUK_TITLE . '"</h5>
                            </a>
                         </div>
                        <div id="collapse' . $call . '" class="accordion-body collapse">
                          <div class="accordion-inner">
                             ' . $data->PETUNJUK_DESC . '
                          </div>
                        </div>
                    </div>
                </div>
            </div>';

    return $html;
}

if (!function_exists('upload_file_lhkpn')) {

    function upload_file_lhkpn($dir, $file_post, $n_instansi = FALSE, $maxsize = 50000000) {
        $dir = "./uploads/$dir/";
        $filename = "uploads/$dir/readme.txt";

        $xexcel_filename = FALSE;
        $fileexcel = FALSE;

        if (!file_exists($filename)) {

            $file_to_write = 'readme.txt';
            $content_to_write = "file ini di upload oleh instansi $n_instansi";

            if (is_dir($dir) === false) {
                mkdir($dir);
            }

            if ($n_instansi) {
                $file = fopen($dir . '/' . $file_to_write, "w");

                fwrite($file, $content_to_write);

                // closes the file
                fclose($file);
            }

            $fileexcel = (isset($_FILES[$file_post])) ? $_FILES[$file_post] : '';
        }
        $namefilexls = 'tpl_data_pnwl.xls';

        $c = [
            "error" => 1,
            "nama_file" => ""
        ];
        if ($fileexcel && $fileexcel['error'] == 0) {
            $extension = strtolower(@substr(@$fileexcel['name'], -4));
            if (in_array($extension, $type_file) && $fileexcel['size'] != '' && $fileexcel['size'] <= $maxsize) {
                $c = save_file($fileexcel['tmp_name'], $namefilexls, $fileexcel['size'], "./uploads/$dir", 0, 10000);
            }
        }

        if (is_array($c) && array_key_exists("error", $c) && $c["error"] != 1) {
            $xexcel_filename = $dir . '/' . $c["nama_file"];
        }
        return $xexcel_filename;
    }

}

function get_pn_posisi_berakhir_by_status($STATUS = NULL) {

}

/// start class ng
class ng {

    /**
     * generate pagination
     *
     * @return config pagination
     */
    static public function genPagination() {
        $CI = & get_instance();
        $CI->load->library('pagination');
        $config['base_url'] = @$CI->base_url;
        $config['total_rows'] = @$CI->total_rows;
        $config['per_page'] = $CI->limit;
        $config['uri_segment'] = $CI->uri_segment;
        $config['full_tag_open'] = '<ul class="pagination tsc_pagination tsc_paginationA tsc_paginationA01">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&#8810; Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next &#8811;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $_SERVER['PHP_SELF'] . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_links'] = 5;
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links();
    }

    static public function genPagination_lhkpn() {
        $CI = & get_instance();
        $CI->load->library('pagination');
        $config['base_url'] = $CI->base_url;
        $config['total_rows'] = @$CI->total_rows;
        $config['per_page'] = $CI->limit;
        $config['uri_segment'] = $CI->uri_segment;
        $config['full_tag_open'] = '<ul class="pagination tsc_pagination tsc_paginationA tsc_paginationA01">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&#8810; Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next &#8811;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $_SERVER['PHP_SELF'] . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_links'] = 5;
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links_lhkpn();
    }

    /**
     * generate breadcrumb
     *
     * @return html breadcrumb
     */
    static public function genBreadcrumb($li) {
        $breadcrumb = '';
        if (is_array($li)) {
            $c = count($li);
            $i = 0;
            foreach ($li as $label => $url) {
                ++$i;
                if ($label == 'Dashboard') {
                    $label = ' <i class="fa fa-home"></i> ';
                }
                $breadcrumb .= '<li><a href="' . $url . '" style="' . ($i == $c ? 'font-weight:bold;' : '') . '">' . $label . '</a></li>';
            }
        }

        return '<ol class="breadcrumb">
                    ' . $breadcrumb . '
                </ol>';
        return '<div class="row">
            <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                    ' . $breadcrumb . '
                </ol>
            </div>
        </div>';
    }

    /**
     * generate upload
     *
     * @return filename
     */
    static public function genUpload($FILE, $infix, $id) {
        $CI = & get_instance();
        $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx");
        $temp = explode(".", $_FILES[$FILE]["name"]);
        $extension = end($temp);

        $prefix = $CI->session->userdata('INST_SATKERKD') . '_' . $CI->session->userdata('UNITKERJA_IDK');

        $newName = $prefix . '_' . $infix . '_' . $id . '_' . $_FILES[$FILE]["name"];

        $type = array(
            "image/gif",
            "image/jpeg",
            "image/jpg",
            "image/pjpeg",
            "image/x-png",
            "image/png",
            "application/msword",
            "application/doc",
            "application/txt",
            "application/pdf",
            "text/pdf",
        );

        $maxsize = 200000000;

        if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
            if ($_FILES[$FILE]["error"] > 0) {
                // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
            } else {
                if (file_exists("upload/" . $newName)) {
                    // echo $_FILES[$FILE]["name"] . " already exists. ";
                } else {
                    // chmod('upload/', 0777);
                    move_uploaded_file($_FILES[$FILE]["tmp_name"], "./upload/" . $newName);
                }
                return $newName;
            }
        } else {
            // echo "Invalid file";
        }
        return '';
    }

    static public function islogin() {
        // return true;
        $CI = & get_instance();
        if ($CI->session->userdata('logged_in') != true || $CI->session->userdata('productkey') != productkey) {
            echo '<script language="javascript">location="' . base_url() . 'index.php/auth/";</script>';
            exit();
        }
    }

    static public function get_allowed_firefox_version() {
        return '47';
//        return '234234';
    }

    static public function get_allowed_chrome_version() {
        return '57';
//        return '234234234234';
    }
    
    static public function get_allowed_safari_version() {
        return '604';
//        return '234234234234';
    }

    static public function get_allowed_browser_version($browser) {
        return strtolower($browser) == 'firefox' ? self::get_allowed_firefox_version() : self::get_allowed_chrome_version();
    }

    static public function allowed_firefox_version($version) {
        if (!$version) {
            return FALSE;
        }

        return self::get_prefix_version($version) >= self::get_allowed_firefox_version();
    }

    static public function get_prefix_version($version) {
        $arr_version = explode(".", $version);
        return $arr_version[0];
    }

    static public function allowed_chrome_version($version) {

        if (!$version) {
            return FALSE;
        }


        return self::get_prefix_version($version) >= self::get_allowed_chrome_version();
    }

    static public function allowed_safari_version($version) {

        if (!$version) {
            return FALSE;
        }


        return self::get_prefix_version($version) >= self::get_allowed_safari_version();
    }

    static public function allowed_browser_version() {
        $self = & get_instance();
        $self->load->library('user_agent');
        $is_browser = $self->agent->is_browser();
        $version = $is_browser ? $self->agent->version() : FALSE;
        $browser = $is_browser ? $self->agent->browser() : FALSE;


        unset($self);

        if (!$version || !(strtolower($browser) == 'firefox' || strtolower($browser) == 'chrome' || strtolower($browser) == 'safari')) {
            return FALSE;
        }

        if (strtolower($browser) == 'firefox' || strtolower($browser) == 'chrome') {
            return strtolower($browser) == 'firefox' ? self::allowed_firefox_version($version) : self::allowed_chrome_version($version);
        }

        if (strtolower($browser) == 'safari'){
            return self::allowed_safari_version($version);
        }
    }

//    static public function

    static public function get_user_agent_information() {

        $self = & get_instance();

        $self->load->library('user_agent');
        $is_browser = $self->agent->is_browser();
        $browser = $is_browser ? $self->agent->browser() : "Unknown";
        $version = $is_browser ? $self->agent->version() : "Unknown";

        $version_ok = strtolower($browser) == 'firefox' ? self::allowed_firefox_version($version) : self::allowed_chrome_version($version);

        if (strtolower($browser) == 'safari'){
            $version_ok = self::allowed_safari_version($version);
        }

        unset($self);

        $nama_browser = 'Chrome';
        $version_browser = self::get_allowed_chrome_version();


        //$msg = "Aplikasi ini hanya bisa dijalankan dengan menggunakan Web Browser Chrome dengan versi diatas atau sama dengan " . self::get_allowed_chrome_version()."; atau menggunakan Web Browser Firefox dengan versi diatas atau sama dengan ".self::get_allowed_firefox_version();
        $msg = "Saat ini anda menggunakan Web Browser <strong>" . $browser . "</strong> versi " . $version . "<br /><br />Untuk login ke dalam Aplikasi e-LHKPN disarankan menggunakan Web Browser <strong>Chrome minimal versi " . self::get_allowed_chrome_version() . "</strong> atau Web Browser <strong>Firefox versi minimal " . self::get_allowed_firefox_version() . "</strong>. ";

        if ((strtolower($browser) == 'firefox' || strtolower($browser) == 'chrome' || strtolower($browser) == 'safari') && in_array(strtolower($browser), ['firefox', 'chrome', 'safari'])) {
            $nama_browser = $browser;
            $version_browser = self::get_allowed_browser_version($browser);

            $msg = $version_ok && (strtolower($browser) == 'firefox' || strtolower($browser) == 'chrome' || strtolower($browser) == 'safari') ? "" : "Aplikasi ini hanya bisa dijalankan dengan menggunakan Web Browser " . $nama_browser . " dengan versi diatas " . $version_browser;
        }



        return (object) [
                    "is_browser" => $is_browser,
                    "browser" => $browser,
                    "version" => $version,
                    "version_ok" => $version_ok,
                    "msg" => $msg,
        ];
//        agent->versioin()
    }

    static public function csstheme() {
        $CI = & get_instance();

        $sql = "SELECT *
        FROM `T_SETTINGS` WHERE OWNER = 'oa' AND SETTING like 'theme%'";
        $query = $CI->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $theme[$row->SETTING] = json_decode($row->VALUE);
            }
        }

        if ($CI->session->userdata('USR') == 'admin') {
            ;
            $selectedTheme = $theme['theme.2'];
        } else {
            $selectedTheme = $theme['theme.3'];
        }
        ?>
        <style type="text/css">
            body, #sidebar-left, .main-menu .dropdown-menu{
                background: <?= $selectedTheme->colorSide; ?> url(<?= base_url(); ?>img/devoops_pattern_b10.png) 0 0 repeat !important;
            }
            #logo{
                background: <?= $selectedTheme->colorLogo; ?> url(<?= base_url(); ?>img/devoops_pattern_b10.png) 0 0 repeat !important;
            }
            #breadcrumb{
                background: <?= $selectedTheme->colorBreadcrumb; ?> url(<?= base_url(); ?>img/devoops_pattern_b10.png) 0 0 repeat !important;
            }
            #dashboard_links .nav{
                background: <?= $selectedTheme->colorNav; ?> url(<?= base_url(); ?>img/devoops_pattern_b10.png) 0 0 repeat !important;
            }
        </style>
        <?php
    }

    static public function dateindo($datetime) {
        return date('d-m-Y', strtotime($datetime));
    }

    static public function datetimeindo($date) {
        return date('d-m-Y H:i:s', strtotime($date));
    }

    static public function datetimesys($datetime) {
        return date('Y-m-d H:i:s', strtotime($datetime));
    }

    static public function datesys($date) {
        return date('Y-m-d', strtotime($date));
    }

    static public function exportDataTo($data, $mode, $view, $filename) {
        $CI = & get_instance();
        $html = $CI->load->view($view, $data, TRUE);
        if ($mode == 'pdf') {
            $CI->load->library('pdf');
            $pdf = $CI->pdf->load();
//            $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            $pdf->SetFooter(FAlSE);
            $pdf->WriteHTML($html); // write the HTML into the PDF
            //$pdf->Output($pdfFilePath, 'F'); // save to file because we can
            $pdf->SetJS('print();');
            $pdf->Output();
        } else if ($mode == 'excel') {
            header("Content-type: application/vnd.ms-excel;charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"" . $filename . ".xls");
            header("Cache-control: private");
            echo $html;
        } else if ($mode == 'word') {
            header("Content-type: application/vnd.ms-word;charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"" . $filename . ".doc");
            header("Cache-control: private");
            echo $html;
        }
    }

    static public function logActivity($activity) {
        $CI = & get_instance();
        $data = array(
            'USERNAME' => $CI->session->userdata('USR'),
            'ACTIVITY' => $activity,
            'CREATED_TIME' => time(),
            'CREATED_BY' => $CI->session->userdata('USR'),
            'CREATED_IP' => get_client_ip(),
            // 'CREATED_IP' => $CI->input->ip_address(),
        );
        $CI->db->insert('T_USER_ACTIVITY', $data);
    }

    static public function logSmsActivity($name,$role,$phone, $msg, $activity) {
        $CI = & get_instance();

        $data = array(
            'USERNAME' => $name,
            'PHONE_NUMBER' => $phone,
            'ACTIVITY' => $activity,
            'MESSAGE' => $msg,
            'CREATED_TIME' => time(),
            'ROLE' =>$role,
            'DATE_SEND' => date('Y-m-d'),
            'TIME_SEND' => date('H:i:s'),
        );
        $CI->db->insert('T_SMS_ACTIVITY', $data);
    }

    static public function filesize_formatted($file) {
        if (substr($file, -1) == '/') {
            return '';
        } else if (!file_exists($file)) {
            return '';
        }

        $bytes = filesize($file);

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    static public function printDariSd($a, $b) {
        if ($b > 0) {
            echo ' Dari : ' . $a . ' s/d ' . $b;
        } else {
            echo ' Tahun : ' . $a;
        }
    }

    static public function text_filter($message, $type = "") {
        if (intval($type) == 2) {
            $message = htmlspecialchars(trim($message), ENT_QUOTES);
        } else {
            $message = strip_tags(urldecode($message));
            $message = htmlspecialchars(trim($message), ENT_QUOTES);
        }

        return $message;
    }

    public static function save_path_as_array($attach){
        if (!is_null($attach) && is_array($attach)) {
            foreach($attach as $item){
                $attachments[] = FCPATH.$item;
            }
            $attach = json_encode($attachments);
        }else if(!is_null($attach) && !is_array($attach)){
            $attachments = [FCPATH.$attach];
            $attach = json_encode($attachments);
        }

        return $attach;
    }

    public static function save_data_as_array($data){
        if (!is_null($data) && is_array($data)) {
            foreach($data as $item){
                $dt_arr[] = $item;
            }
            $data = json_encode($dt_arr);
        }else if(!is_null($data) && !is_array($data)){
            $dt_arr = [$data];
            $data = json_encode($dt_arr);
        }

        return $data;
    }

    /// send mail with queue ///
    public static function mail_send_queue($to, $subject, $message, $from = null, $masking = null, $attach = null, $cc = null, $bcc = null, $qrcode = null, $barcode = null, $is_unlink_attachment = false, $is_unlink_barcode = false, $is_unlink_qrcode = false, $is_login = false){  
        
        $CI = & get_instance();
        $CI->load->model('mglobal', '', TRUE);
                
        if ($from != null) {
            $from = $from;
        } else {
            $from = "statistik@kpk.go.id";
        }
        
        $subject = ng::text_filter($subject);
        
        if (is_null($masking)){
            $masking = "Aplikasi e-LHKPN";
        }
        
        if($subject != 'PIC Penugasan e-Audit'){
            $bcc = 'report.elhkpn@kpk.go.id';
        }

        /// ---- dapat menghandle multiple to, cc, bcc and attachment ---- ///
        $to = ng::save_data_as_array($to);
        $cc = ng::save_data_as_array($cc);
        $bcc = ng::save_data_as_array($bcc);        
        $attach = ng::save_path_as_array($attach);
        $qrcode = ng::save_path_as_array($qrcode);
        $barcode = ng::save_path_as_array($barcode);

        $data = [
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'from' => json_encode((object)['address' => $from, 'name' => $masking]),
            'attach' => $attach,
            'cc' => $cc,
            'bcc' => $bcc, 
            'qrcode' => $qrcode,
            'barcode' => $barcode,
            'is_unlink_attachment' => $is_unlink_attachment,
            'is_unlink_qrcode' => $is_unlink_qrcode,
            'is_unlink_barcode' => $is_unlink_barcode,
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($is_login){
            $action = $CI->mglobal->insert('t_login_queues', $data);
        }else{
            $action = $CI->mglobal->insert('t_email_queues', $data);
        }

        if($action['status'] == TRUE){ /// email akan diantrikan di table t_mail_jobs (yang akan diexecute oleh supervisor)
            $artisan_path = $CI->config->item('path_artisan_queue_mail');
            exec('php '.$artisan_path.' antrian:run --id='.$action['id'].'', $output, $result);

            unset($CI);

            if($result == 0){ //gagal masuk antrian
                return false;
            }

            return true;
        }

        unset($CI);
        return false;
    }

    static public function mail_send($to, $subject, $message, $from = null, $attach = null, $cc = null, $force_single_attach = FALSE, $masking = null, $multiple = null,$bcc2 = null) {
        $CI = & get_instance();

        if (ENV_USE_LDAP == TRUE) {
            kpk::mail_send($to, $subject, $message, $from = null, $attach = null, $cc = null);
        } else {
            // mail($to, $subject, $message, $headers);
            if ($from != null) {
                $from = $from;
            } else {
                $from = "statistik@kpk.go.id";
            }

            $subject = ng::text_filter($subject);

            /* $header  = $CI->config->item('mail_server');
              $config['protocol'] = "smtp";
              $config['smtp_host'] = $CI->config->item('smtp_host');
              $config['smtp_port'] = $CI->config->item('smtp_port');
              $config['smtp_user'] = $CI->config->item('smtp_user');
              $config['smtp_pass'] = $CI->config->item('smtp_pass');
              $config['mailtype'] = 'html';
              $config['charset'] = 'utf-8';
              $config['newline'] = "\r\n";
              $config['wordwrap'] = TRUE; */

            $CI->load->library('email');
            $CI->load->config('email');
            //$CI->email->initialize($config);
            if (!is_null($masking)){
                $CI->email->from($from, $masking);
            }else{
                $CI->email->from($from, "Aplikasi e-LHKPN");
            }
            $CI->email->to($to);
            if($subject != 'PIC Penugasan e-Audit'){
                $CI->email->bcc('report.elhkpn@kpk.go.id');
            }
            $CI->email->subject($subject);
            $CI->email->message($message);

            if (!is_null($multiple)) {
                foreach($multiple as $me){
                   $CI->email->attach($me);
                }
            }


            if (!is_null($attach)) {
                $CI->email->attach($attach);
            }
            if (!is_null($cc)) {
                $CI->email->cc($cc);
            }
            if (!is_null($bcc2)) {
                $CI->email->bcc($bcc2);
            }

            if (!$CI->email->send()) {
                //show_error($CI->email->print_debugger());
                unset($CI);
                return FALSE;
            }
        }
        unset($CI);
        return TRUE;
    }


    static function getOS($user_agent) { 

        $os_platform  = "Unknown OS Platform";

        $os_array =  array(
                        '/windows nt 10/i'      =>  'Windows 10',
                        '/windows nt 6.3/i'     =>  'Windows 8.1',
                        '/windows nt 6.2/i'     =>  'Windows 8',
                        '/windows nt 6.1/i'     =>  'Windows 7',
                        '/windows nt 6.0/i'     =>  'Windows Vista',
                        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                        '/windows nt 5.1/i'     =>  'Windows XP',
                        '/windows xp/i'         =>  'Windows XP',
                        '/windows nt 5.0/i'     =>  'Windows 2000',
                        '/windows me/i'         =>  'Windows ME',
                        '/win98/i'              =>  'Windows 98',
                        '/win95/i'              =>  'Windows 95',
                        '/win16/i'              =>  'Windows 3.11',
                        '/macintosh|mac os x/i' =>  'Mac OS X',
                        '/mac_powerpc/i'        =>  'Mac OS 9',
                        '/linux/i'              =>  'Linux',
                        '/ubuntu/i'             =>  'Ubuntu',
                        '/iphone/i'             =>  'iPhone',
                        '/ipod/i'               =>  'iPod',
                        '/ipad/i'               =>  'iPad',
                        '/android/i'            =>  'Android',
                        '/blackberry/i'         =>  'BlackBerry',
                        '/webos/i'              =>  'Mobile'
                    );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;

        return $os_platform;
    }

    static function getBrowser($user_agent) {

        $browser = "Unknown Browser";

        $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                        );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;

        return $browser;
    }

    static function detect_location($ip) {
        
        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET", // Atur type request, get atau post
            CURLOPT_POST => false, // Atur menjadi GET
            CURLOPT_FOLLOWLOCATION => true, // Follow redirect aktif
            CURLOPT_CONNECTTIMEOUT => 120, // Atur koneksi timeout
            CURLOPT_TIMEOUT => 120, // Atur response timeout
            CURLOPT_RETURNTRANSFER => 1
        );

        $url = 'http://www.geoplugin.net/json.gp?ip='.$ip;

        $ch = curl_init($url);          // Inisialisasi Curl
        curl_setopt_array($ch, $options);    // Set Opsi
        $response = curl_exec($ch);           // Eksekusi Curl
        curl_close($ch);                     // Stop atau tutup script

        return $response;
   }

    function input_escape() {
        if ($this->input->get()) {
            foreach ((array) $this->input->get() as $k => $v) {
                if (is_array($k)) {
                    foreach ($k as $key => $value) {
                        $_GET[$k][$key] = $this->db->escape_like_str($value);
                    }
                } else {
                    $_GET[$k] = $this->db->escape_like_str($v);
                }
            }
        }
        if ($this->input->post()) {
            foreach ((array) $this->input->post() as $k => $v) {
                if (is_array($k)) {
                    foreach ($k as $key => $value) {
                        $_POST[$k][$key] = $this->db->escape_like_str($value);
                    }
                } else {
                    $_POST[$k] = $this->db->escape_like_str($v);
                }
            }
        }
        return true;
    }

}

/// end class ng

function number_rupiah($number) {
    return number_format($number, 0, ",", ".");
}

function provinsiKotaList($items, &$PROVINSI, &$KOTA) {
    $CI = & get_instance();
    $IDPROV = array();
    $IDKOT = array();

    foreach ($items as $item_reff) {
        if ($item_reff->PROVINSI && $item_reff->PROVINSI != '-- propinsi --') {
            $IDPROV[] = $item_reff->PROVINSI;
        }
        if ($item_reff->KOTA && $item_reff->KOTA != '-- kota --') {
            $IDKOT[] = $item_reff->PROVINSI . $item_reff->KOTA;
        }
    }

    $IDPROV = array_unique($IDPROV);
    $IDKOT = array_unique($IDKOT);

    if (count($IDPROV)) {
        $sql = "SELECT * FROM M_AREA WHERE IDPROV IN (" . implode(', ', $IDPROV) . ") AND IDKOT = ''";
        $areas = $CI->db->query($sql)->result();
        foreach ($areas as $area) {
            $PROVINSI[$area->IDPROV] = (array) $area;
        }
    }

    if (count($IDKOT)) {
        $sql = "SELECT * FROM M_AREA WHERE CONCAT(IDPROV,IDKOT) IN (" . implode(', ', $IDKOT) . ") AND IDKEC = ''";
        $areas = $CI->db->query($sql)->result();
        foreach ($areas as $area) {
            $KOTA[$area->IDPROV . $area->IDKOT] = (array) $area;
        }
    }
}

function display($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    ($die ? die : '');
}

/* Upload SAVE FILE */

function save_file($file, $file_name, $file_size, $folder, $flag, $size, $array_allowed_extension = array(), $rename = TRUE, $overwrite = FALSE) {

    if ($file != '') {
        $ret['error'] = 0;
        $pict = getimagesize($file);
        //if (!(($pict[2]==1) || ($pict[2]==2))) :
        $extension = strtolower(substr($file_name, -4));

        if (is_array($array_allowed_extension)) {
            $array_allowed_extension = array_merge(array('.jpg', '.png', '.jpeg', '.pdf', '.tiff', '.doc', 'docx', '.xls', 'xlsx'), $array_allowed_extension);

            // if(!in_array($extension, array('.xls', '.pdf', 'docx','pptx','xlsx','.doc'))) :
            if (!in_array($extension, $array_allowed_extension)) :
                $ret['error'] = 1;
                // $ret['msg'] = "Please, File ".$tail." must be xls,pdf,docx or GIF format...";
                $ret['msg'] = "Please, File " . $extension . " must be xls,pdf,docx or GIF format...";
                return $ret;
                exit();
            endif;
        }


        if ($file == "none") :
            $ret['error'] = 1;
            $ret['msg'] = "Please, Fill file field...";
            return $ret;
            exit();
        endif;
        if ($flag) :
            if ($file_size >= $size * 1024) :
                $ret['error'] = 1;
                $ret['msg'] = "File size too large. Maximum file size $size KB...";
                return $ret;
                exit();
            endif;
        endif;
        $name_file = trim($file_name);
        if ($rename) {
            $name_file = time() . "-" . trim($file_name);
        }

        if (!$rename && $overwrite && file_exists($folder . "/" . $name_file)) {
            unlink($folder . "/" . $name_file);
        }

        if (!@copy($file, $folder . "/" . $name_file)) :
            $ret['error'] = 1;
            $ret['msg'] = "Copy file failed. Please check the file $file_name... $file -> $folder/$name_file";
            return $ret;
            exit();
        endif;

        $ret['nama_file'] = $name_file;
        return $ret;
        exit();
    }
}

function save_file_name($file, $file_name, $file_size, $folder, $size) {
    if ($file != '') {
        $ret['error'] = 0;
        $pict = getimagesize($file);
        //if (!(($pict[2]==1) || ($pict[2]==2))) :
        $extension = strtolower(substr($file_name, -4));
        // if(!in_array($extension, array('.xls', '.pdf', 'docx','pptx','xlsx','.doc'))) :
        if (!in_array($extension, array('.jpg', '.png', '.jpeg', '.pdf', '.tiff', '.doc', 'docx', '.xls', 'xlsx'))) :
            $ret['error'] = 1;
            // $ret['msg'] = "Please, File ".$tail." must be xls,pdf,docx or GIF format...";
            $ret['msg'] = "Please, File " . $extension . " must be xls,pdf,docx or GIF format...";
            return $ret;
            exit();
        endif;

        if ($file == "none") :
            $ret['error'] = 1;
            $ret['msg'] = "Please, Fill file field...";
            return $ret;
            exit();
        endif;

        $name_file = trim($file_name);

        if (!@copy($file, $folder . "/" . $name_file)) :
            $ret['error'] = 1;
            $ret['msg'] = "Copy file failed. Please check the file $file_name... $file -> $folder/$name_file";
            return $ret;
            exit();
        endif;

        $ret['nama_file'] = $name_file;
        return $ret;
        exit();
    }
}

/** Print Role
 *
 * @return html multiple role
 */
function printRole($ID_ROLE, $ROLE) {
    $role = [];
    $arrRole = explode(',', $ID_ROLE);
    foreach ($arrRole as $key) {
        if (isset($ROLE[trim($key)])) {
            $role[] = '<span class="badge bg-' . $ROLE[trim($key)]['COLOR'] . '">' . $ROLE[trim($key)]['ROLE'] . '</span>';
        }
    }
    return implode(', ', $role);
}

function doresetpasswordAll($post) {
    $type = $post['act'];
    if ($type == 'dorepas1') {
        $CI = & get_instance();
        $CI->db->trans_begin();
        $CI->load->model('muser', '', TRUE);
        $data['USERNAME'] = $post['USERNAME'];
        $getUser = $CI->muser->getUser($data['USERNAME']);
        $data['REQUEST_RESET'] = '1';
        $data['REQUEST_RESET_KEY'] = md5($data['USERNAME'] . date('Y-m-d H:i:s') . 'Mitreka');
        $data['REQUEST_RESET_TIME'] = date('Y:m:d H:i:s');
        $date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $data['REQUEST_RESET_EXPIRED'] = $date . " " . date('H:i:s');
        $urlDate = $date . "_" . date('H:i:s');
        $CI->muser->updtResetPass($data);

        $CI->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->config->item('smtp_host');
        $config['useragent'] = "CodeIgniter";
        $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['smtp_port'] = $this->config->item('smtp_port');
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = $this->config->item('smtp_user');
        $config['smtp_pass'] = $this->config->item('smtp_pass');
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from('apptester@mitrekasolusi.co.id', 'Mitreka (Admin LHKPN)');
        $CI->email->to($getUser[0]->EMAIL);

        $CI->email->subject('Reset Password User');
        $text = 'Link : ' . base_url() . 'index.php/auth/resetpassword/' . $data['REQUEST_RESET_KEY'];
        $CI->email->message($text);

        $send = $CI->email->send();

        if ($CI->db->trans_status() === FALSE) {
            $CI->db->trans_rollback();
        } else {
            $CI->db->trans_commit();
        }

        $result = intval($CI->db->trans_status());
    } else {
        $CI = & get_instance();
        $CI->db->trans_begin();
        $CI->load->model('muser', '', TRUE);
        // $data['USERNAME']              = $post['username'];
        // $getUser                       = $CI->muser->getUser2($data['USERNAME']);

        $data = array();
        $data['REQUEST_RESET'] = '1';
        $data['REQUEST_RESET_KEY'] = md5($post['USERNAME'] . date('Y-m-d H:i:s') . 'Mitreka');
        $data['REQUEST_RESET_TIME'] = date('H:i:s');
        $data['REQUEST_RESET_EXPIRED'] = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $data['USERNAME'] = $post['USERNAME'];

        $CI->muser->updtResetPass($data);

        $CI->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->config->item('smtp_host');
        $config['useragent'] = "CodeIgniter";
        $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['smtp_port'] = $this->config->item('smtp_port');
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = $this->config->item('smtp_user');
        $config['smtp_pass'] = $this->config->item('smtp_pass');
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from('apptester@mitrekasolusi.co.id', 'Mitreka (Admin LHKPN)');
        $CI->email->to($post['EMAIL']);

        $CI->email->subject('Reset Password User');
        $text = 'Link : http://localhost:8181/lhkpn_public/index.php/auth/lupapassword';
        $CI->email->message($text);

        $send = $CI->email->send();

        if ($CI->db->trans_status() === FALSE) {
            $CI->db->trans_rollback();
        } else {
            $CI->db->trans_commit();
        }

        $result = intval($CI->db->trans_status());
    }
    return $result;
}

function bulan($bulan, $abbr = FALSE, $flipped = FALSE) {
    $aBulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

    if ($abbr) {
        $aBulan = array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
    }

    if ($flipped) {
        $aBulan = array_flip($aBulan);
    }
    return array_key_exists($bulan, $aBulan) ? $aBulan[$bulan] : NULL;
}

function tgl_format($tgl, $default_value = "none") {
    if (trim($tgl) !== "" && $tgl !== NULL && strtotime($tgl) != FALSE) {
        $tanggal = date('j', strtotime($tgl));
        $bulan = bulan(date('n', strtotime($tgl)) - 1);
        $tahun = date('Y', strtotime($tgl));
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
    if ($default_value == "none")
        return $tgl;

    return $default_value;
}

function getColor($num) {
    $hash = md5('color' . $num); // modify 'color' to get a different palette
    return array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))); //b
}

function Namabulan($par = null) {
    if ($par == '01') {
        $nama = 'Januari';
    } else if ($par == '02') {
        $nama = 'Februari';
    } else if ($par == '03') {
        $nama = 'Maret';
    } else if ($par == '04') {
        $nama = 'April';
    } else if ($par == '05') {
        $nama = 'Mei';
    } else if ($par == '06') {
        $nama = 'Juni';
    } else if ($par == '07') {
        $nama = 'Juli';
    } else if ($par == '08') {
        $nama = 'Agustus';
    } else if ($par == '09') {
        $nama = 'September';
    } else if ($par == '10') {
        $nama = 'Oktober';
    } else if ($par == '11') {
        $nama = 'November';
    } else {
        $nama = 'Desember';
    }
    return $nama;
}

if (!function_exists('get_format_tanggal_lapor_lhkpn')) {

    function get_format_tanggal_lapor_lhkpn($jenis_laporan, $tanggal) {


        $time = strtotime($tanggal);

        $bulan_lapor = Namabulan(date('m', $time));
        $tgl_lapor = date('d', $time);
        $tahun_lapor = date('Y', $time);
        $tahun = $tgl_lapor . ' ' . $bulan_lapor . ' ' . $tahun_lapor;
        //echo $this->db->last_query();exit;
        if ($jenis_laporan == "4") {
            $tahun = substr($tanggal, 0, 4);
        }
        return $tahun;
    }

}

function pembanding($jabatanpn, $jabatan) {
    $pembanding = ['ID_JABATAN', 'ESELON', 'LEMBAGA', 'UNIT_KERJA'];

    foreach ($jabatanpn as $row) {
        foreach ($jabatan as $baris) {
            foreach ($pembanding as $pem) {
                if ($row->$pem != $baris->$pem) {
                    return 0;
                }
            }
        }
    }

    return true;
}

function replaceRegex($string) {
    $string = preg_replace_callback('/\[([\w\s]*)\]/', 'numberFormat', $string);
    return $string;
}

/**
 * Format 4 byte data
 * @param type $matches
 * @return type
 */
function numberFormat($matches) {
    return number_format($matches[1], 0, ',', '.');
}

/**
 * Format 8 byte
 * @param type $var
 * @param type $null
 * @param type $fractional
 * @return string
 */
function _format_number($var, $null = TRUE, $fractional = FALSE) {
    if ($null === TRUE && $var == 0)
        return "N/A";

    if ($null === FALSE && ($var == 0 || $var == "")) {
        return "";
    }

    if ($fractional) {
        $var = sprintf('%.2f', $var);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1.$2', $var);
        if ($replaced != $var) {
            $var = $replaced;
        } else {
            break;
        }
    }
    return $var;
}

function ifaTetap($array) {
    if ($array->STATUS == '1' && $array->IS_CHECKED == '0') {
        return true;
    } else {
        return false;
    }
}

function indonesian_date($timestamp = '', $date_format = 'j F Y - H:i', $suffix = '') {
    if (trim($timestamp) == '') {
        $timestamp = time();
    } elseif (!ctype_digit(strval($timestamp))) {
        $timestamp = strtotime($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
        '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
        '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
        '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
        '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
        '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
        '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
        '/November/', '/December/',
    );
    $replace = array('Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September',
        'Oktober', 'November', 'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date} {$suffix}";
    return $date;
}

function role_user($id_user) {
    $CI = & get_instance();
    $CI->load->database();
    $CI->db->where('ID_USER', $id_user);
    $CI->db->join('t_user_role', 't_user_role.ID_ROLE = t_user.ID_ROLE');
    $data = $CI->db->get('t_user')->row();
    return strtoupper($data->ROLE);
}

function auth_user() {
    $CI = & get_instance();
    $CI->load->database();
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function CallURLPage($url, $curl_data = NULL) {


    //$options = array(
    //  CURLOPT_CUSTOMREQUEST => "GET", // Atur type request, get atau post
    //CURLOPT_POST => false, // Atur menjadi GET
    //CURLOPT_FOLLOWLOCATION => true, // Follow redirect aktif
    //CURLOPT_CONNECTTIMEOUT => 120, // Atur koneksi timeout
    // CURLOPT_TIMEOUT => 120, // Atur response timeout
    //);

    $options = array(
        CURLOPT_CUSTOMREQUEST => "GET", // Atur type request, get atau post
        CURLOPT_POST => true, // Atur menjadi GET
        CURLOPT_FOLLOWLOCATION => true, // Follow redirect aktif
        CURLOPT_CONNECTTIMEOUT => 120, // Atur koneksi timeout
        CURLOPT_TIMEOUT => 120, // Atur response timeout
        CURLOPT_POSTFIELDS => $curl_data,
        CURLOPT_RETURNTRANSFER => TRUE,
    );

    $ch = curl_init($url);          // Inisialisasi Curl
    curl_setopt_array($ch, $options);    // Set Opsi
    $content = curl_exec($ch);           // Eksekusi Curl
    curl_close($ch);                     // Stop atau tutup script


    $header['content'] = $content;
    return $header;
}

if (!function_exists('string_to_date')) {

    function string_to_date($str_date, $format = 'd-m-Y') {

        if ($str_date != FALSE && $str_date != NULL && $str_date != '')
            return date($format, strtotime($str_date));
        else
            return '';
    }

}

if (!function_exists('to_mysql_date')) {

    /**
     *
     * @param string $str_date
     * @param array $format in array array("d", "m", "Y")
     * @return boolean
     */
    function to_mysql_date($str_date = FALSE, $format = array("d", "m", "Y"), $delimiter = "/") {
        if ($str_date) {
            $arr_date = explode($delimiter, $str_date);

            $arr_date = array_combine($format, $arr_date);
            $date = date('Y-m-d', mktime(0, 0, 0, $arr_date["m"], $arr_date["d"], $arr_date["Y"]));

            return $date;
        }
        return FALSE;
    }

}

if (!function_exists("clean_data_insert")) {

    function clean_data_insert($data_insert) {
        if ($data_insert && is_array($data_insert)) {
            foreach ($data_insert as $key => $value) {
                if ($value === FALSE) {
                    unset($data_insert[$key]);
                }
//                $data_insert[$key] = $value !== FALSE ? $value : NULL;
            }
            return $data_insert;
        }
        return $data_insert;
    }

}

if (!function_exists("map_data_atas_nama")) {

    function map_data_atas_nama($int_data, $type = NULL) {
        if (is_numeric($int_data)) {
            switch ($int_data) {
                case 1:
                case "1":
                    return "PN yang bersangkutan";
                    break;
                case 2:
                case "2":
                    return "Pasangan/Anak";
                    break;
                default :
                    return "Lainnya";
                    break;
            }
        }
        else{
            return $int_data;
        }
    }

}

if (!function_exists("map_data_asal_usul")) {

    function map_data_asal_usul($int_data, $type = NULL) {
        $return = "";
        switch ($int_data) {
            case 1:
            case "1":
                $return = "Hasil Sendiri";
                break;
            case 2:
            case "2":
                $return = "Warisan";
                break;
            case 3:
            case "3":
                $return = "Hibah dengan Akta";
                break;
            case 4:
            case "4":
                $return = "Hibah tanpa Akta";
                break;
            case 5:
            case "5":
                $return = "Hadiah";
                break;
            default :
                $return = "Lainnya";
                break;
        }
        return $return;
    }

}

if (!function_exists("map_data_pemanfaatan_harta_tidak_bergerak")) {

    function map_data_pemanfaatan_harta_tidak_bergerak($int_data) {
        $return = "";
        switch ($int_data) {
            case 1:
            case "1":
                $return = "Tempat Tinggal";
                break;
            case 2:
            case "2":
                $return = "Disewakan";
                break;
            case 3:
            case "3":
                $return = "Pertanian/Perkebunan/Perikanan/Pertambangan";
                break;
            case 4:
            case "4":
                $return = "Lainnya";
                break;
            default :
                break;
        }
        return $return;
    }

}

if (!function_exists("map_data_pemanfaatan_harta_bergerak")) {

    function map_data_pemanfaatan_harta_bergerak($int_data, $entry_via = '1') {
        $return = "";


        switch ($int_data) {
            case 1:
            case "1":
                $return = "Digunakan sendiri";
                break;
            case 2:
            case "2":
                $return = "Tidak digunakan sendiri & menghasilkan";
                break;
            case 3:
            case "3":
                $return = "Tidak digunakan sendiri dan tidak menghasilkan";
                break;
            case 4:
            case "4":
                $return = "Lainnya";
                break;
            case 5:
            case "5":
                $return = "Digunakan sendiri";
                break;
            case 6:
            case "6":
                $return = "Tidak digunakan sendiri & menghasilkan";
                break;
            case 7:
            case "7":
                $return = "Tidak digunakan sendiri dan tidak menghasilkan";
                break;
            case 8:
            case "8":
                $return = "Lainnya";
                break;
            default :
                break;
        }
        return $return;
    }

}

if (!function_exists("map_data_pemanfaatan")) {

    function map_data_pemanfaatan($int_data, $type = NULL) {
        $return = "";
        switch ($type) {
            case 1:
                $return = map_data_pemanfaatan_harta_tidak_bergerak($int_data);
                break;
            case 2:
                $return = map_data_pemanfaatan_harta_bergerak($int_data);
                break;
            default :
                break;
        }
        return $return;
    }

}

if (!function_exists('show_me')) {

    function show_me($objName = FALSE, $propertyName = FALSE, $default_value = "-") {

        $me = $default_value;
        if ($objName && !is_null($objName)) {
            if (is_object($objName) && $propertyName && property_exists($objName, $propertyName)) {
                $me = $objName->{$propertyName};
            } else {
                $me = $objName;
            }
            if (strlen(strval($me)) > 0) {
                return $me;
            }
        }

        return $default_value;
    }

}

if(!function_exists('calculate_offset')){
    function calculate_offset($page = 1, $limit = 5){
        return (($page - 1) * $limit + 1) - 1;
    }
}

if (!function_exists('beautify_str')) {

    function beautify_str($str = "", $show_blank_space_when_null = FALSE, $default_value = "") {
        if (strlen($str) > 0 && strtolower($str) != "null") {
            return ucwords(str_replace("_", " ", stripslashes($str)));
        }

        if ($show_blank_space_when_null) {
            return " ";
        }
        return $default_value;
    }

}

if (!function_exists('remove_multiple_line_symbol')) {

    function remove_multiple_line_symbol($str_text = "") {
        $str_text = trim(preg_replace("/\s\s+/", "", $str_text));
        $str_text = preg_replace("/(\\r?\\n){2,}/", "", $str_text);
        $str_text = preg_replace("/[\\r\\n]+/", "<br />", $str_text);
        $str_text = preg_replace("/rn+/", "", $str_text);
        $str_text = str_replace("\\n\\n", "", $str_text);
        $str_text = str_replace("\\n", "", $str_text);
        $str_text = nl2br($str_text);
        return $str_text;
    }

}

if (!function_exists('beautify_text')) {

    function beautify_text($str_text = "", $default_value = "-") {
        if (strlen($str_text) > 0) {
            return beautify_str(stripslashes(remove_multiple_line_symbol($str_text)), FALSE, $default_value);
        }
        return "";
    }

}

if (!function_exists('make_secure_text')) {

    function make_secure_text($id) {
//        return md5(date('d') . $id . date('Y'));
        return md5($id . date('Y'));
    }

}

if (!function_exists('make_secure_object')) {

    function make_secure_object($attribute_name, $object_record, $is_object = TRUE) {
        if ($object_record) {
            if ($is_object && is_object($object_record) && property_exists($object_record, $attribute_name)) {
                $object_record->{$attribute_name . '_secure'} = make_secure_text($object_record->{$attribute_name});
            } elseif (!$is_object && is_array($object_record) && array_key_exists($attribute_name, $object_record)) {
                $object_record[$attribute_name . '_secure'] = make_secure_text($object_record->{$attribute_name});
            }
        }
        return $object_record;
    }

}

if (!function_exists("map_jenis_kelamin")) {

    function map_jenis_kelamin_to_bin($jenis_kelamin = FALSE, $type = 'bin') {
        $return_value = NULL;
        if ($jenis_kelamin) {
            switch ($type) {

                case 'num': {
                        /**
                         * 1 pria
                         * 2 wanita
                         */
                        $return_value = $jenis_kelamin == '2' || $jenis_kelamin == '1' ? '1' : '0';
                        break;
                    }
                case 'abbr': {
                        $return_value = preg_replace('/\s+/', '', strtolower($jenis_kelamin)) == 'pria' ? '1' : '0';
                        break;
                    }
                case 'txt': {
                        $return_value = preg_replace('/\s+/', '', strtolower($jenis_kelamin)) == 'laki-laki' ? '1' : '0';
                        break;
                    }
                case 'bin':
                default : {
                        $return_value = $jenis_kelamin == '1' || $jenis_kelamin == 1 ? '1' : '0';
                        break;
                    }
            }
        }
        return $return_value;
    }

}

if (!function_exists('map_in_array')) {

    function map_in_array($array_data, $data = NULL, $return_as_id = TRUE) {
        $response = "";
        if ($return_as_id) {
            $arr_temp = array_flip($array_data);
            $response = array_key_exists($array_data, $arr_temp) ? $arr_temp[$data] : "";
            unset($arr_temp);
        } else {
            $key_check = is_string($data) ? $data : intval($data);
            $response = array_key_exists($key_check, $array_data) ? $array_data[$key_check] : "";
        }
        return $response;
    }

}

if (!function_exists('map_status_perkawinan')) {

    function map_status_perkawinan($status_perkawinan = NULL, $return_as_id = TRUE) {
        $response = "";
        if (!is_null($status_perkawinan)) {
            $arr_status_perkawinan = array(
                "1" => "Menikah",
                "2" => "Cerai",
                "3" => "Janda / Duda",
                "4" => "Lajang",
            );

            $response = map_in_array($arr_status_perkawinan, $status_perkawinan, $return_as_id);
            unset($arr_status_perkawinan);
        }
        return $response;
    }

}

if (!function_exists('show_status_perkawinan')) {

    function show_status_perkawinan($status_perkawinan = NULL, $default_text = "-", $is_text = TRUE) {
        $response = "";
        if (!is_null($status_perkawinan)) {
            $response = map_status_perkawinan($status_perkawinan, !$is_text);
        }
        return $response == "" ? $default_text : $response;
    }

}

if (!function_exists('get_arr_jenis_laporan')) {

    function get_arr_jenis_laporan($idx = FALSE) {
        $arr_jen_laporan = array(
            "1" => "Calon PN",
            "2" => "Awal Menjabat",
            "3" => "Akhir Menjabat",
            "4" => "Sedang Menjabat",
        );
        if ($idx) {
            if (array_key_exists($idx, $arr_jen_laporan)) {
                return $arr_jen_laporan[$idx];
            }
            return "";
        } else {
            return $arr_jen_laporan;
        }
    }

}

if (!function_exists('get_arr_jenis_laporan_imp_xl')) {

    function get_arr_jenis_laporan_imp_xl() {
        return array(
            "1" => "3",
            "2" => "4",
            "3" => "1",
            "4" => "2",
        );
    }

}

if (!function_exists('map_jenis_laporan_imp_xl')) {

    function map_jenis_laporan_imp_xl($jenis_laporan = NULL) {
        $response = "";
        if (!is_null($jenis_laporan)) {
            $response = map_in_array(get_arr_jenis_laporan_imp_xl(), $jenis_laporan, FALSE);
        }
        return $response;
    }

}

if (!function_exists('map_jenis_laporan_xl')) {

    function map_jenis_laporan_xl($jenis_laporan = NULL) {
        $response = "";
        if (!is_null($jenis_laporan)) {
            $response = map_in_array(get_arr_jenis_laporan(), $jenis_laporan, FALSE);
        }
        return $response;
    }

}

if (!function_exists('get_arr_hubungan_keluarga')) {

    function get_arr_hubungan_keluarga($which_version = 'old') {
        $arr_hub = [
            "old" => [
                "1" => "Istri",
                "2" => "Suami",
                "3" => "Anak Tanggungan",
                "4" => "Anak Bukan Tanggungan",
                "6" => "Lainnya"
            ],
            "1.8" => [
                "2" => "Suami",
                "3" => "Istri",
                "4" => "Anak Tanggungan",
                "5" => "Anak Bukan Tanggungan",
                "6" => "Lainnya"
            ],
            "1.11" => [
                "2" => "Suami",
                "3" => "Istri",
                "4" => "Anak Tanggungan",
                "5" => "Anak Bukan Tanggungan",
                "6" => "Lainnya"
            ],
            "2.1" => [
                "2" => "Suami",
                "3" => "Istri",
                "4" => "Anak Tanggungan",
                "5" => "Anak Bukan Tanggungan",
                "6" => "Lainnya"
            ]
        ];

        $arr_hub["1.6"] = $arr_hub["1.8"];
        if (array_key_exists($which_version, $arr_hub)) {
            return $arr_hub[$which_version];
        }

        return $arr_hub["old"];
    }

}

if (!function_exists('get_arr_hubungan_keluarga_imp_xl')) {

    function get_arr_hubungan_keluarga_imp_xl($which_version = 'old') {
        if ($which_version == 'old') {
            return array(
                "1" => "89",
                "2" => "2",
                "3" => "1",
                "4" => "3",
                "5" => "4",
                "6" => "99", // Lainnya (versi xl)
            );
        }

        if ($which_version == '1.8') {
            return array(
                "1" => "3",
                "2" => "2",
                "3" => "4",
                "4" => "5",
                "5" => "6",
                "6" => "99", // Lainnya (versi xl)
            );
        }
    }

}

if (!function_exists('map_hubungan_keluarga_imp_xl')) {

    function map_hubungan_keluarga_imp_xl($hubungan = NULL, $return_as_id = TRUE) {
        $response = "";
        if (!is_null($hubungan)) {
            $key_hub_keluarga = map_in_array(get_arr_hubungan_keluarga_imp_xl(), $hubungan, FALSE);
            $response = map_in_array(get_arr_hubungan_keluarga(), $key_hub_keluarga, $return_as_id);
        }
        return $response;
    }

}

if (!function_exists('show_hubungan_keluarga')) {

    function show_hubungan_keluarga($hubungan = NULL, $default_text = "-", $which_version = 'old') {
        $response = "";
        $hub_kel = get_arr_hubungan_keluarga($which_version);
        if (!is_null($hubungan) && is_numeric($hubungan) && array_key_exists($hubungan, get_arr_hubungan_keluarga($which_version))) {
            $response = $hub_kel[$hubungan];
        }
        unset($hub_kel);
        return $response == "" ? $default_text : $response;
    }

}

if (!function_exists('show_hubungan_keluarga_imp_xl')) {

    function show_hubungan_keluarga_imp_xl($hubungan = NULL, $default_text = "-", $is_text = TRUE) {
        $response = "";
        if (!is_null($hubungan)) {
            $response = map_hubungan_keluarga_imp_xl($hubungan, !$is_text);
        }
        return $response == "" ? $default_text : $response;
    }

}


if (!function_exists('show_harta_atas_nama')) {

    function show_harta_atas_nama($i_atas_nama = NULL) {
        $response = "";
        if(is_numeric($i_atas_nama)){
            $CI = &get_instance();

            $CI->config->load('harta', TRUE);

            $atas_nama = $CI->config->item('atas_nama', 'harta');

            unset($CI);
            if (array_key_exists($i_atas_nama, $atas_nama)) {
                $response = $atas_nama[$i_atas_nama];
            }
            unset($atas_nama);
        }
        else{
            $response = $i_atas_nama;
        }
        return $response;
    }

}

if (!function_exists('locker_key')) {
    function locker_key($time) {
        $CI =& get_instance();
        $CI->db->from('locker');
        $query = $CI->db->get();

        $secret_key = array('Gd.','MP','@13');
        if($time % 2 == 0){
            $key_locker = array('2','4','6');
            $iv_locker = array('1','3','5');
            $secret_iv = "@2";
        }else{
            $key_locker = array('1','3','5');
            $iv_locker = array('2','4','6');
            $secret_iv = "C1";
        }

        $key_loop = 0;
        $iv_loop = 0;
        $result_key = null;
        $result_iv = null;
        foreach($query->result() as $r){
            if(in_array($r->id,$key_locker)){
                $encrypt_method = "AES-256-CBC";
                $key = hash('sha256', $secret_key[$key_loop]);
                $iv = substr(hash('sha256', $secret_iv), 0, 16);

                $result_key .= openssl_decrypt(base64_decode($r->key), $encrypt_method, $key, 0, $iv);
                $key_loop++;
            }
            if(in_array($r->id,$iv_locker)){
                $encrypt_method = "AES-256-CBC";
                $key = hash('sha256', $secret_key[$iv_loop]);
                $iv = substr(hash('sha256', $secret_iv), 0, 16);

                $result_iv .= openssl_decrypt(base64_decode($r->iv), $encrypt_method, $key, 0, $iv);
                $iv_loop++;
            }
        }
        $result = $result_key.$result_iv;



        return $result;
        exit;
    }
}

if (!function_exists('locker_key_tracking')) {
    function locker_key_tracking($time) {
        $CI =& get_instance();
        $CI->db->start_cache();
        $CI->db->from('locker');
        $query = $CI->db->get();
        $CI->db->flush_cache();

        $secret_key = array('Gd.','MP','@13');
        if($time % 2 == 0){
            $key_locker = array('2','4','6');
            $iv_locker = array('1','3','5');
            $secret_iv = "@2";
        }else{
            $key_locker = array('1','3','5');
            $iv_locker = array('2','4','6');
            $secret_iv = "C1";
        }

        $key_loop = 0;
        $iv_loop = 0;
        $result_key = null;
        $result_iv = null;
        foreach($query->result() as $r){
            if(in_array($r->id,$key_locker)){
                $encrypt_method = "AES-256-CBC";
                $key = hash('sha256', $secret_key[$key_loop]);
                $iv = substr(hash('sha256', $secret_iv), 0, 16);

                $result_key .= openssl_decrypt(base64_decode($r->key), $encrypt_method, $key, 0, $iv);
                $key_loop++;
            }
            if(in_array($r->id,$iv_locker)){
                $encrypt_method = "AES-256-CBC";
                $key = hash('sha256', $secret_key[$iv_loop]);
                $iv = substr(hash('sha256', $secret_iv), 0, 16);

                $result_iv .= openssl_decrypt(base64_decode($r->iv), $encrypt_method, $key, 0, $iv);
                $iv_loop++;
            }
        }
        $result = $result_key.$result_iv;



        return $result;
        exit;
    }
}

if (!function_exists('encrypt_username')) {
    function encrypt_username($string, $action = 'e') {
        if (!isset($string) || empty($sring)) {
            return '';
            exit;
        }
        $time = date('H');
        $locker = locker_key($time);

        $secret_key = substr($locker,0,19);
        $secret_iv = substr($locker,19);



        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
        exit;
    }
}

if (!function_exists('encrypt_username_tracking')) {
    function encrypt_username_tracking($string, $action = 'e') {
        if (!isset($string) || empty($sring)) {
            return '';
            exit;
        }
        $time = date('H');
        $locker = locker_key_tracking($time);

        $secret_key = substr($locker,0,19);
        $secret_iv = substr($locker,19);



        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
        exit;
    }
}


if (!function_exists('extract_zip_subdir')) {

    function extract_zip_subdir($zipfile, $subpath, $destination, $temp_cache, $traverse_first_subdir = true) {
        $zip = new ZipArchive;
        echo "extracting $zipfile... ";
        if (substr($temp_cache, -1) !== DIRECTORY_SEPARATOR) {
            $temp_cache .= DIRECTORY_SEPARATOR;
        }
        $res = $zip->open($zipfile);
        if ($res === TRUE) {
            if ($traverse_first_subdir == true) {
                $zip_dir = $temp_cache . $zip->getNameIndex(0);
            } else {
                $temp_cache = $temp_cache . basename($zipfile, ".zip");
                $zip_dir = $temp_cache;
            }
            echo "  to $temp_cache... \n";
            $zip->extractTo($temp_cache);
            $zip->close();
            echo "ok\n";
            echo "moving subdir... ";
            echo "\n $zip_dir / $subpath -- to -- >  $destination\n";
            rename($zip_dir . DIRECTORY_SEPARATOR . $subpath, $destination);
            echo "ok\n";
            echo "cleaning extraction dir... ";
            rrmdir($zip_dir);
            echo "ok\n";
        } else {
            echo "failed\n";
            die();
        }
    }

}

if (!function_exists('Unzip')) {

    function Unzip($dir, $file, $destiny = "") {
        $dir .= DIRECTORY_SEPARATOR;
        $path_file = $dir . $file;
        $zip = zip_open($path_file);
        $_tmp = array();
        $count = 0;
        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
                $_tmp[$count]["filename"] = zip_entry_name($zip_entry);
                $_tmp[$count]["stored_filename"] = zip_entry_name($zip_entry);
                $_tmp[$count]["size"] = zip_entry_filesize($zip_entry);
                $_tmp[$count]["compressed_size"] = zip_entry_compressedsize($zip_entry);
                $_tmp[$count]["mtime"] = "";
                $_tmp[$count]["comment"] = "";
                $_tmp[$count]["folder"] = dirname(zip_entry_name($zip_entry));
                $_tmp[$count]["index"] = $count;
                $_tmp[$count]["status"] = "ok";
                $_tmp[$count]["method"] = zip_entry_compressionmethod($zip_entry);

                if (zip_entry_open($zip, $zip_entry, "r")) {
                    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    if ($destiny) {
                        $path_file = str_replace("/", DIRECTORY_SEPARATOR, $destiny . zip_entry_name($zip_entry));
                    } else {
                        $path_file = str_replace("/", DIRECTORY_SEPARATOR, $dir . zip_entry_name($zip_entry));
                    }
                    $new_dir = dirname($path_file);

                    // Create Recursive Directory (if not exist)
                    if (!file_exists($new_dir)) {
                        mkdir($new_dir, 0700);
                    }

                    $fp = fopen($dir . zip_entry_name($zip_entry), "w");
                    fwrite($fp, $buf);
                    fclose($fp);

                    zip_entry_close($zip_entry);
                }
                echo "\n</pre>";
                $count++;
            }

            zip_close($zip);
        }
    }

}

if (!function_exists('UnzipImage')) {

    /**
     *
     * @param type $dir
     * @param type $file
     * @param type $destiny
     * @param type $subfolder
     *
     * @return array list of files
     */
    function UnzipSubFolder($dir, $file, $destiny = FALSE, $subfolder = "") {
        $dir .= DIRECTORY_SEPARATOR;
        $destiny = $destiny ? $destiny . DIRECTORY_SEPARATOR : FALSE;
        $path_file = $dir . $file;
        $zip = zip_open($path_file);

        $file_list = array();

//        $_tmp = array();
        $count = 0;
        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
//                $_tmp[$count]["filename"] = zip_entry_name($zip_entry);
//                $_tmp[$count]["stored_filename"] = zip_entry_name($zip_entry);
//                $_tmp[$count]["size"] = zip_entry_filesize($zip_entry);
//                $_tmp[$count]["compressed_size"] = zip_entry_compressedsize($zip_entry);
//                $_tmp[$count]["mtime"] = "";
//                $_tmp[$count]["comment"] = "";
//                $_tmp[$count]["folder"] = dirname(zip_entry_name($zip_entry));
//                $_tmp[$count]["index"] = $count;
//                $_tmp[$count]["status"] = "ok";
//                $_tmp[$count]["method"] = zip_entry_compressionmethod($zip_entry);

                $fname = basename(zip_entry_name($zip_entry));
                $dname = dirname(zip_entry_name($zip_entry));

                if ($subfolder == $dname) {
                    if (zip_entry_open($zip, $zip_entry, "r")) {
                        $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                        $path_file = str_replace("/", DIRECTORY_SEPARATOR, $dir . $fname);
                        if ($destiny) {
                            $path_file = str_replace("/", DIRECTORY_SEPARATOR, $destiny . $fname);
                        }

                        $new_dir = dirname($path_file);

                        $file_list[] = $fname;


                        // Create Recursive Directory (if not exist)
                        if (!file_exists($new_dir)) {
                            mkdir($new_dir, 0700);
                        }

                        $fp = fopen(strtolower(trim($path_file)), "w");
                        fwrite($fp, $buf);
                        fclose($fp);

                        zip_entry_close($zip_entry);
                    }
                }
                $count++;
            }

            zip_close($zip);
        }
        return $file_list;
    }

}

if (!function_exists("show_jenis_kelamin")) {

    function show_jenis_kelamin($jenis_kelamin = FALSE) {
        if (map_jenis_kelamin_to_bin($jenis_kelamin, (is_numeric($jenis_kelamin) ? 'num' : 'txt')) == 1) {
            return "Laki-Laki";
        }
        if (map_jenis_kelamin_to_bin($item->JENIS_KELAMIN, (is_numeric($item->JENIS_KELAMIN) ? 'num' : 'txt')) == 0) {
            return "Perempuan";
        }
        return "";
    }

}

if (!function_exists('show_jenis_laporan_khusus')) {

    function show_jenis_laporan_khusus($id_jenis = FALSE, $tgl_kirim_final = FALSE, $default = "") {
        $response = $default;
        if ($id_jenis) {
            switch ($id_jenis) {
                case '1':
                case 1:
                    $response = get_arr_jenis_laporan('1');
                    break;
                case '2':
                case 2:
                    $response = get_arr_jenis_laporan('2');
                    break;
                case '3':
                case 3:
                    $response = get_arr_jenis_laporan('3');
                    break;
                case '4':
                case 4:
                    $response = substr($tgl_kirim_final, 0, 4);
                    break;
                default:
                    $response = $default;
                    break;
            }
        }
        return $response;
    }

}

if (!function_exists('is_db_debug_active')) {

    function is_db_debug_active() {
        $CI = &get_instance();
        $is_enabled = $CI->config->item('enable_db_debug');
        unset($CI);
        return $is_enabled;
    }

}

if (!function_exists('check_atas_nama')) {
    function check_atas_nama($atas_nama) {
        $check_data = strtoupper($atas_nama);
        if($check_data == "PN YANG BERSANGKUTAN"){
            $result = "1";
        }elseif($check_data == "PASANGAN / ANAK"){
            $result = "2";
        }elseif(strstr($atas_nama, "1")){
            $result = $atas_nama;
        }elseif(strstr($atas_nama, "2")){
            $result = $atas_nama;
        }elseif(strstr($atas_nama, "3")){
            $result = $atas_nama;
        }else{
            $result = "5 Lainnya (".$atas_nama.")";
        }
        return $result;
    }
}

if (!function_exists('textarea_to_html')) {
    function textarea_to_html($text) {
        $olahkata = stripslashes($text);
        return nl2br($olahkata);
    }
}

if (!function_exists('html_to_textarea')) {
    function html_to_textarea($text) {
        $text = str_replace ('<br />', "", $text);
        $text = str_replace ('<br/>', "", $text);
        $text = str_replace ('<br>', "", $text);
        return $text;
    }
}
if (!function_exists('str_slug')) {
    function str_slug($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}


if (!function_exists('protectionDocument')) {
    function protectionDocument($file,$extension_get){
        $message = 'Dokument tidak diijinkan !!';
        $extensions = array();
        $mimes = array();
        $types = array();

        $nama_terlarang = array('.php','.py','.sh','.pl');

            if ($_FILES[$file]['name']) {

                $type_file = current(explode('/',$_FILES[$file]['type']));
                $mime_file = strtolower(end(explode('/',$_FILES[$file]['type'])));
                $extension_file = strtolower(end(explode('.',$_FILES[$file]['name'])));

                foreach($nama_terlarang as $nt){
                    if (strpos($_FILES[$file]['name'], $nt) !== false) {
                        $result = array('detect'=>'name_file','keterangan'=>'nama file mengandung unsur .php .py .sh .pl','name'=>$_FILES[$file]['name'],'type'=>$_FILES[$file]['type']);
                        return $result;
                    }
                }

                foreach($extension_get as $e){
                    $result = kamusMimes($e);
                    if($result['status']=="sukses"){
                        array_push($extensions,$result['data']['extension']);
                        array_push($mimes,$result['data']['mime']);
                        array_push($types,$result['data']['type']);
                    }
                }

                if (!in_array($mime_file, $mimes)) {
                    $result = array('detect'=>'mime_file','keterangan'=>'mime tidak diijinkan','name'=>$_FILES[$file]['name'],'type'=>$_FILES[$file]['type']);
                    return $result;
                }

                if (!in_array($type_file, $types)) {
                    $result = array('detect'=>'type_file','keterangan'=>'type tidak diijinkan','name'=>$_FILES[$file]['name'],'type'=>$_FILES[$file]['type']);
                    return $result;
                }

                if (!in_array($extension_file, $extensions)) {
                    $result = array('detect'=>'extension_file','keterangan'=>'extension tidak diijinkan','name'=>$_FILES[$file]['name'],'type'=>$_FILES[$file]['type']);
                    return $result;
                }
            }
            return false;
    }
}

if (!function_exists('kamusMimes')) {
    function kamusMimes($type){
            //buku extension
            $doc = array('extension'=>'doc','type'=>'application','mime'=>'msword');
            $docx = array('extension'=>'docx','type'=>'application','mime'=>'vnd.openxmlformats-officedocument.wordprocessingml.document');
            $pdf = array('extension'=>'pdf','type'=>'application','mime'=>'pdf');
            $jpg = array('extension'=>'jpg','type'=>'image','mime'=>'jpeg');
            $jpeg = array('extension'=>'jpeg','type'=>'image','mime'=>'jpeg');
            $png = array('extension'=>'png','type'=>'image','mime'=>'png');
            $tiff = array('extension'=>'tiff','type'=>'image','mime'=>'tiff');
            $tif = array('extension'=>'tif','type'=>'image','mime'=>'tiff');
            $xls = array('extension'=>'xls','type'=>'application','mime'=>'vnd.ms-excel');
            $xlsx = array('extension'=>'xlsx','type'=>'application','mime'=>'vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $xlsm = array('extension'=>'xlsm','type'=>'application','mime'=>'vnd.ms-excel.sheet.macroenabled.12');
            $ppt = array('extension'=>'ppt','type'=>'application','mime'=>'application/vnd.ms-powerpoint');
            $pptx = array('extension'=>'pptx','type'=>'application','mime'=>'vnd.openxmlformats-officedocument.presentationml.presentation');
            $gif = array('extension'=>'gif','type'=>'image','mime'=>'gif');
            $swf = array('extension'=>'swf','type'=>'application','mime'=>'x-shockwave-flash');

            $book = array(
                ['title' => 'doc','result'=>$doc],
                ['title' => 'docx','result'=>$docx],
                ['title' => 'pdf','result'=>$pdf],
                ['title' => 'jpg','result'=>$jpg],
                ['title' => 'jpeg','result'=>$jpeg],
                ['title' => 'png','result'=>$png],
                ['title' => 'tiff','result'=>$tiff],
                ['title' => 'tif','result'=>$tif],
                ['title' => 'xls','result'=>$xls],
                ['title' => 'xlsx','result'=>$xlsx],
                ['title' => 'xlsm','result'=>$xlsm],
                ['title' => 'ppt','result'=>$ppt],
                ['title' => 'pptx','result'=>$pptx],
                ['title' => 'gif','result'=>$gif],
                ['title' => 'swf','result'=>$swf],
            );
            foreach($book as $b){
                if($b['title']==$type){
                    return array('status'=>'sukses','data'=>$b['result']);
                }
            }
            return array('status'=>'gagal','data'=>null);
            try{
                return $type;
            }catch(Exception $e){

            }
    }
}

if (!function_exists('protectionMultipleDocument')) {
    function protectionMultipleDocument($file,$extension_get){
            $loop = 0;
            $message = 'Dokument tidak diijinkan !!';
            $extensions = array();
            $mimes = array();
            $types = array();

            $nama_terlarang = array('.php','.py','.sh','.pl');

            foreach($extension_get as $e){
                $result = kamusMimes($e);
                if($result['status']=="sukses"){
                    array_push($extensions,$result['data']['extension']);
                    array_push($mimes,$result['data']['mime']);
                    array_push($types,$result['data']['type']);
                }
            }

            foreach ($_FILES[$file]['type'] as $key => $tmp_name) {
                if($tmp_name){

                    $type_file = current(explode('/',$_FILES[$file]['type'][$loop]));
                    $mime_file = strtolower(end(explode('/',$_FILES[$file]['type'][$loop])));
                    $extension_file = strtolower(end(explode('.',$_FILES[$file]['name'][$loop])));

                    foreach($nama_terlarang as $nt){
                       if (strpos($_FILES[$file]['name'][$loop], $nt) !== false) {
                            $result = array('detect'=>'name_file','keterangan'=>'nama file mengandung unsur .php .py .sh .pl','name'=>$_FILES[$file]['name'][$loop],'type'=>$_FILES[$file]['type'][$loop]);
                            return $result;
                        }
                    }

                    if (!in_array($mime_file, $mimes)) {
                        $result = array('detect'=>'mime_file','keterangan'=>'mime tidak diijinkan','name'=>$_FILES[$file]['name'][$loop],'type'=>$_FILES[$file]['type'][$loop]);
                        return $result;
                    }

                    if (!in_array($type_file, $types)) {
                        $result = array('detect'=>'type_file','keterangan'=>'type tidak diijinkan','name'=>$_FILES[$file]['name'][$loop],'type'=>$_FILES[$file]['type'][$loop]);
                        return $result;
                    }

                    if (!in_array($extension_file, $extensions)) {
                        $result = array('detect'=>'extension_file','keterangan'=>'extension tidak diijinkan','name'=>$_FILES[$file]['name'][$loop],'type'=>$_FILES[$file]['type'][$loop]);
                        return $result;
                    }

                    $loop++;
                }
            }
            return false;
    }
}

if (!function_exists('errorRedirect')) {

    function errorRedirect($tipe = "json",$redirect = null,$message = "File tidak diijinkan"){
        $ci = &get_instance();
        $ci->load->library('session');

        if($tipe=="json"){
            return true;
        }elseif($tipe=="url"){
            $ci->session->set_flashdata('error_message', 'error_message');
            $ci->session->set_flashdata('message',$message);
            redirect($redirect);
            return;
        }
    }
}

if (!function_exists('scanningFileDanger')) {
    function scanningFileDanger($path){
        $handle = file($path);
        foreach($handle as $h){
            if (strpos($h, '<?php') !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('protectUrl')) {
    function protectUrl($data = null){
        if($data){
            $data = encrypt_username($data,'d');
            if(!$data){
                return true;
            }
            $check = strtotime(date('Y-m-d H:i:s'));
            $subTime = $check - $data;
            $y = ($subTime/(60*60*24*365));
            $d = ($subTime/(60*60*24))%365;
            $h = ($subTime/(60*60))%24;
            $m = ($subTime/60)%60;
            if($d==0 and $h==0 and $m <= 10){
                return false;
            }else{
                return true;
            }
        }else{
            $create = strtotime(date('Y-m-d H:i:s'));
            return encrypt_username($create);
        }
    }
}


if (!function_exists('protectFilling')) {
    function protectFilling($id_pn,$table,$id_table){
        $CI =& get_instance();
        $CI->db->from('locker');
        $query = $CI->db->get();

        $table = strtolower($table);

        $result_id = kamusTable($table);

        ////////////AMBIL ID LHKPN TABLE YANG AKAN DI CEK/////////////
        $CI->db->where($result_id, $id_table);
        $CI->db->select('*');
        $CI->db->from($table);
        $query_table = $CI->db->get();
        foreach ($query_table->result() as $row)
        {
            $table_id_lhkpn = $row->ID_LHKPN;
        }
        ////////////AMBIL ID LHKPN TABLE YANG AKAN DI CEK/////////////


        ////////////AMBIL SEMUA ID LHKPN SI PN/////////////
        $CI->db->where('IS_ACTIVE', 1);
        $CI->db->where('ID_PN', $id_pn);
        $CI->db->select('ID_LHKPN');
        $CI->db->from('t_lhkpn');
        $query_lhkpn = $CI->db->get();
        ////////////AMBIL SEMUA ID LHKPN SI PN/////////////

        $cek_loop_sama = 0;

        ////////////CEK APAKAH TABLE BENAR PUNYA SI PN///////////////
        foreach($query_lhkpn->result() as $qr){
            if($table_id_lhkpn == $qr->ID_LHKPN){
                $cek_loop_sama++;
            }
        }
        ////////////CEK APAKAH TABLE BENAR PUNYA SI PN///////////////

        if($cek_loop_sama > 0 ){
            return false;
        }else{
            $name = 'table = '.$table.', id = '.$id_table;

            $result = array('detect'=>'access_user','keterangan'=>'user tidak memiliki ijin terhadap data','name'=>$name,'type'=>null);
            return $result;
        }

    }
}

if (!function_exists('protectLhkpn')) {
    function protectLhkpn($id_pn,$id_lhkpn){
        $CI =& get_instance();
        $CI->db->from('locker');
        $query = $CI->db->get();

        $CI->db->where('ID_LHKPN', $id_lhkpn);
        $CI->db->select('*');
        $CI->db->from('t_lhkpn');
        $query= $CI->db->get();
        foreach ($query->result() as $row)
        {
            $state_id_pn = $row->ID_PN;
        }

        if($state_id_pn==$id_pn){
            return false;
        }else{
            $name = 'id_lhkpn = '.$id_lhkpn;

            $result = array('detect'=>'access_lhkpn','keterangan'=>'user tidak memiliki ijin terhadap LHKPN','name'=>$name,'type'=>null);
            return $result;
        }

    }
}

if (!function_exists('protectPn')) {
    function protectPn($id_pn,$table,$id_table){
        $CI =& get_instance();
        $CI->db->from('locker');
        $query = $CI->db->get();

        $table = strtolower($table);

        $result_id = kamusTable($table);

        ////////////AMBIL ID LHKPN TABLE YANG AKAN DI CEK/////////////
        $CI->db->where($result_id, $id_table);
        $CI->db->select('*');
        $CI->db->from($table);
        $query_table = $CI->db->get();
        foreach ($query_table->result() as $row)
        {
            $table_id_pn = $row->ID_PN;
        }
        ////////////AMBIL ID LHKPN TABLE YANG AKAN DI CEK/////////////

        if($id_pn == $table_id_pn){
            return false;
        }else{
            $name = 'table = '.$table.', id = '.$id_table;

            $result = array('detect'=>'access_pn','keterangan'=>'user tidak memiliki ijin terhadap PN','name'=>$name,'type'=>null);
            return $result;
        }

    }
}


if (!function_exists('protectMailbox')) {
    function protectMailbox($id_user,$table,$id_table,$method = "inbox"){
        $CI =& get_instance();
        $CI->db->from('locker');
        $query = $CI->db->get();

        $table = strtolower($table);

        $result_id = kamusTable($table);

        ////////////AMBIL ID LHKPN TABLE YANG AKAN DI CEK/////////////
        $CI->db->where($result_id, $id_table);
        $CI->db->select('*');
        $CI->db->from($table);
        $query_table = $CI->db->get();
        foreach ($query_table->result() as $row)
        {
            if($method=="inbox"){
                $table_id_user = $row->ID_PENERIMA;
            }else{
                $table_id_user = $row->ID_PENGIRIM;
            }

        }
        ////////////AMBIL ID LHKPN TABLE YANG AKAN DI CEK/////////////

        if($id_user == $table_id_user){
            return false;
        }else{
            $name = 'table = '.$table.', id = '.$id_table.' , method = '.$method;

            $result = array('detect'=>'access_mailbox','keterangan'=>'user tidak memiliki ijin terhadap MAILBOX','name'=>$name,'type'=>null);
            return $result;
        }

    }
}



if (!function_exists('kamusTable')) {
    function kamusTable($table){

            $book = array(
                ['table' => 't_lhkpn_hutang','result'=>'ID_HUTANG'],
                ['table' => 't_lhkpn_keluarga','result'=>'ID_KELUARGA'],
            );

            foreach($book as $b){
                if($b['table']==$table){
                    return $b['result'];
                }
            }
            return 'ID';
    }
}


if (!function_exists('dd')) {
    function dd($value) {
        dump($value);exit;
    }
}

if (!function_exists('isExist')) {
    function isExist($data){
      if(!is_null($data)){
            return $data;
      }else{
            return '---';
      }
  }
}

if (!function_exists('convertCharXml')) {
    function convertCharXml($str){
      return str_replace('&', '&#38;', $str);
  }
}

////////////////////////////////////////MINIO HELPER///////////////////////////////////
if (!function_exists('switchMinio')) {
    function switchMinio(){

      $CI = & get_instance();
      $CI->load->library('MinioConfiguration');
      $switch = $CI->minioconfiguration->switchMinio();
      return $switch;
    }
}

if (!function_exists('storageDiskMinio')) {
    function storageDiskMinio(){
      $CI = & get_instance();
      $CI->load->library('MinioConfiguration');
      $storage = $CI->minioconfiguration->storageDiskMinio();
      return $storage;
    }
}

if (!function_exists('createPathMinio')) {
    function createPathMinio($checkPath){
      $CI =& get_instance();
      $CI->load->library('MinioConfiguration');
      $year_path = date('Y');
      if($checkPath){
        $get_name = $checkPath->name;
        $get_year = substr($get_name,0,4);
        $get_queue = substr($get_name,4,3);
        if($get_year==$year_path){
          if($checkPath->child >= $CI->minioconfiguration->childPerFolder()){
            $up_queue = ++$checkPath->name;
            $sub_path = $up_queue;
          }else{
            $sub_path = $get_name;
          }
        }else{
          $sub_path = $year_path.'001';
        }
      }else{
        $sub_path = $year_path.'001';
      }
      return $sub_path;
  }
}

if (!function_exists('checkPathMinio')) {
    function checkPathMinio($path = null, $take = 0, $id_lhkpn = null,$jenis_path=null){
      $CI =& get_instance();
      $CI->load->library('MinioConfiguration');

      $data_path = null;
      if($id_lhkpn){
        $CI->db->where(['id_lhkpn'=>$id_lhkpn,'jenis'=> $jenis_path,'storage'=>$CI->minioconfiguration->storageDiskMinio()]);
        $CI->db->select('*');
        $CI->db->from('t_minio');
        $query= $CI->db->get();
        foreach ($query->result() as $row)
        {
            $data_path = $row;
        }
      }

     if($data_path){
       $result = ['path'=>$data_path->path,'storage'=>$data_path->storage];
     }else{
        $postField = [
            'bucket'=>$CI->minioconfiguration->bucket(),
            'path'=>$path,
            'take'=>$take,
            'storage'=>storageDiskMinio(),
        ];

         $encrypt = [
             'token' => $CI->minioconfiguration->encode($postField)
         ];

         $curl = curl_init();
         curl_setopt_array($curl, array(
           CURLOPT_PORT => "1000",
           CURLOPT_URL => "http://kiluan.kpk.go.id:1000/api/minio/checkfile",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($encrypt),
           CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "content-type: application/json"
           ),
         ));
         $response = curl_exec($curl);
         $err = curl_error($curl);
         curl_close($curl);
         if ($err) {
           $result = 0;
         } else {
           $get = json_decode($response);
           if($get->status=="success"){
             $result = $get->data;
             $output_path = createPathMinio($result[0]);
             $year = substr($output_path,0,4);
             $sub_folder = substr($output_path,4,3);
             $data_path = array(
                    'id_lhkpn' => $id_lhkpn,
                    'jenis' => $jenis_path,
                    'year' => $year,
                    'sub_folder' => $sub_folder,
                    'path' => $output_path,
                    'storage' => storageDiskMinio(),
            );
            $CI->db->insert('t_minio', $data_path);
            $result = ['path'=>$output_path,'storage'=>storageDiskMinio()];
           }else{
             $result = 0;
           }
         }
     }
      return $result;
   }
}

if (!function_exists('uploadToMinio')) {
    function uploadToMinio($source, $customName=null, $path = null,$storage=null){
      // $source adalah name file pada type input
      // pembuatan $customName tanpa ekstension
      // contoh pembuatan path: folderA/folderB/folderC/

      /////////////////JWT AUTH////////////////
      $CI = & get_instance();
      $CI->load->library('MinioConfiguration');

      $tmp = $_FILES[$source]["tmp_name"];
      $ext = end(explode('.',$_FILES[$source]["name"]));
      $mime = $_FILES[$source]["type"];
      if($customName){
        $name = $customName;
      }else{
        $name = $_FILES[$source]["name"];
      }

      if($storage){
        $setStorage = $storage;
      }else{
        $setStorage = storageDiskMinio();
      }

      $file = curl_file_create($tmp, $mime, $name);
      $postField['path'] = $path;
      $postField['bucket'] = $CI->minioconfiguration->bucket();
      $postField['storage'] = $setStorage;


      $encrypt = [
          'token' => $CI->minioconfiguration->encode($postField),
          'filetoupload' => $file
      ];
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_PORT => "1000",
          CURLOPT_URL => "http://kiluan.kpk.go.id:1000/api/minio/uploadfile",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $encrypt,
          CURLOPT_HTTPHEADER => array(
            "content-type: multipart/form-data",
          ),
        ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
        $result = 0;
      } else {
        $get = json_decode($response);
        if($get->status=="success"){
          $result = 1;
        }else{
          $result = 0;
        }
      }
      return $result;
    }
}

if (!function_exists('uploadMultipleToMinio')) {
    function uploadMultipleToMinio($tmp,$mime, $customName, $path = null,$storage=null){
      // $tmp adalah lokasi path file
      // $mime adalah ekstension file
      // pembuatan $customName tanpa ekstension
      // contoh pembuatan path: folderA/folderB/folderC/

      /////////////////JWT AUTH////////////////
      $CI = & get_instance();
      $CI->load->library('MinioConfiguration');

      if($storage){
          $setStorage = $storage;
      }else{
         $setStorage = storageDiskMinio();
      }

      $file = curl_file_create($tmp, $mime, $customName);
      $postField['path'] = $path;
      $postField['bucket'] = $CI->minioconfiguration->bucket();
      $postField['storage'] = $setStorage;

      $encrypt = [
          'token' => $CI->minioconfiguration->encode($postField),
          'filetoupload' => $file
      ];
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_PORT => "1000",
          CURLOPT_URL => "http://kiluan.kpk.go.id:1000/api/minio/uploadfile",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $encrypt,
          CURLOPT_HTTPHEADER => array(
            "content-type: multipart/form-data",
          ),
        ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
        $result = 0;
      } else {
        $get = json_decode($response);
        if($get->status=="success"){
          $result = 1;
        }else{
          $result = 0;
        }
      }
      return $result;
    }
}

if (!function_exists('linkFromMinio')) {
     function linkFromMinio($name,$path = null,$table=null,$field=null,$id=null,$storage=null){

        $CI = & get_instance();
        $CI->load->library('MinioConfiguration');
        //terdapat 2 metode dalam load file dari minio
        //apabila parsing $table memiliki value
        //maka path storage disk akan mencari di database pada field StorageDisk
        //apabila parsing $table null
        //maka path storage disk lansung mengambil/include dengan $path
        if($table){
        $CI->load->model('mglobal', '', TRUE);
        $data = $CI->mglobal->get_data_by_id($table,$field,$id,false,true);
        $postField = [
            'bucket'=>$CI->minioconfiguration->bucket(),
            'name'=>$name,
            'path'=>$path,
            'storage'=>$data->STORAGE_MINIO
        ];
        }else{
            if($storage){
                $postField = [
                    'bucket'=>$CI->minioconfiguration->bucket(),
                    'name'=>$name,
                    'path'=>$path,
                    'storage'=>$storage
                ];
            }else{
                $result = 0;
                return $result;
            }
        }

      $encrypt = [
          'token' => $CI->minioconfiguration->encode($postField)
      ];
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_PORT => "1000",
        CURLOPT_URL => "http://kiluan.kpk.go.id:1000/api/minio/linkfile",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($encrypt),
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: application/json"
        ),
      ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
        $result = 0;
      } else {
        $get = json_decode($response);
        if($get->status=="success"){
          $result = $get->data;
        }else{
          $result = 0;
        }
      }
      return $result;
    }
}

if (!function_exists('deleteToMinio')) {
    function deleteToMinio($name,$path = null){
      // $name adalah nama file di minio
      // $path lokasi path di minio, contoh $path: folderA/folderB/folderC/

      /////////////////JWT AUTH////////////////
      $CI = & get_instance();
      $CI->load->library('MinioConfiguration');

      $postField = [
        'bucket'=>$CI->minioconfiguration->bucket(),
        'name'=>$name,
        'path'=>$path
      ];

      $encrypt = [
          'token' => $CI->minioconfiguration->encode($postField)
      ];
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_PORT => "1000",
        CURLOPT_URL => "http://kiluan.kpk.go.id:1000/api/minio/deletefile",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($encrypt),
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: application/json"
        ),
      ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
        $result = 0;
      } else {
        $get = json_decode($response);
        if($get->status=="success"){
          $result = $get->data;
        }else{
          $result = 0;
        }
      }
      return $result;
    }
}
////////////////////////////////////////MINIO HELPER///////////////////////////////////

if (!function_exists('replace_string_null')) {
    function replace_string_null($string) {
        return strtolower(preg_replace('/\s+/', '', $string)) == 'null' ? null : $string;
    }
}
