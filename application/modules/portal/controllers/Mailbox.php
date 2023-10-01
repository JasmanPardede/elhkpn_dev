<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mailbox extends MY_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
        $this->ID_PN = $this->session->userdata('ID_PN');
        $this->check_lhkpn_draft = $this->db->where("ID_PN = ". $this->ID_PN ." AND IS_ACTIVE = 1 AND JENIS_LAPORAN <> '5' AND entry_via = '0' AND (STATUS = '0' OR STATUS = '2')")->count_all_results("t_lhkpn");
    }

    function index() {
        $options = array(
            'title' => 'Inbox',
            'check_lhkpn_draft' => $this->check_lhkpn_draft,
        );
        $this->load->view('template/header', $options);
        $this->load->view('mailbox/index', $options);
        $this->load->view('template/footer', $options);
    }

    function outbox() {
        $options = array(
            'title' => 'Mailbox',
            'check_lhkpn_draft' => $this->check_lhkpn_draft,
        );
        $this->load->view('template/header', $options);
        $this->load->view('mailbox/outbox', $options);
        $this->load->view('template/footer', $options);
    }

    function user() {
        $q = $this->input->post_get('q');

        $where = "
		(t_user.ID_ROLE = '2' OR
		(t_user.INST_SATKERKD = '" . $this->user_instansi_id . "' AND t_user.ID_ROLE = '3') OR
		(t_user.UK_ID = '" . $this->user_uk_id . "' AND t_user.ID_ROLE = '4'))
		";

        if ($q != "") {
            $where .= " AND t_user.NAMA LIKE '%" . $q . "%' ";
        }

        $this->db->select('NAMA, INST_NAMA, USERNAME, ID_ROLE');
        $this->db->where($where);
        $this->db->limit(10);
        $this->db->order_by('NAMA', 'ASC');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        //$this->db->join('t_user_role', 't_user_role.ID_ROLE = t_user.ID_ROLE', 'LEFT');
        $this->db->group_by('t_user.ID_USER');

        $data = $this->db->get('t_user')->result();

//        echo $this->db->last_query();exit;

        $array = array();

        foreach ($data as $row) {

            if ($row->INST_NAMA) {
                $text = $row->NAMA . '( ' . $row->INST_NAMA . ' )';
            } else {
                $text = '(' . $row->NAMA . ')' . '-' . $row->NIP;
            }

            $array[] = array(
                'id' => $row->ID_USER,
                'text' => $text
            );
        }

        $this->to_json($array);
    }

    function send() {

        $url = null;
        $user = $this->session->userdata('USR');
        $id = $this->session->userdata('ID_USER');

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


        $filependukung = @$_FILES['filename'];
        $extension = end((explode(".", $_FILES['filename']['name'])));
        $c = save_file($filependukung['tmp_name'], $filependukung['name'], $filependukung['size'], "./uploads/mail_out/" . $id . "/", 0, 10000);
        if ($c) {
            $url = time() . "-" . trim($filependukung['name']);
        } else {
            $url = null;
        }



        $penerima = explode(',', $this->input->post('ID_PENERIMA')[0]);
        $pesan_valid = $this->input->post('txtPesan');



        $jml_kirim = 0;
        foreach ($penerima as $key) {
            $data = array(
                'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                'ID_PENERIMA' => $key,
                'SUBJEK' => $this->input->post('subject'),
                'PESAN' => $pesan_valid,
                'FILE' => $url,
                'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                'IS_ACTIVE' => '1'
            );
            $send = $this->db->insert('T_PESAN_KELUAR', $data);
            if ($send) {
                $data2 = array(
                    'ID_PENGIRIM' => $this->session->userdata('ID_USER'),
                    'ID_PENERIMA' => $key,
                    'SUBJEK' => $this->input->post('subject'),
                    'PESAN' => $pesan_valid,
                    'FILE' => $url,
                    'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                    'IS_ACTIVE' => '1'
                );
                $send2 = $this->db->insert('T_PESAN_MASUK', $data2);
                if ($send2) {
                    $jml_kirim++;
                }
            }
            $user = $this->mglobal->get_data_all('T_USER', null, ['ID_USER = ' => $key], 'NAMA,EMAIL')[0];
            $subject = $this->input->post('subject');
            //ng::mail_send($user->EMAIL, $subject, $this->input->post('PESAN'), NULL, 'uploads/mail_out/' . $id . '/' . $url, NULL);
        }


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        $status = $this->db->trans_status();
        if ($status) {
            $this->session->set_flashdata('success_message', 'success_message');
            $this->session->set_flashdata('message', '<strong>' . $jml_kirim . '</strong> Pesan Berhasil Terkirim !!');
        } else {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan sistem !!');
        }
        redirect('portal/mailbox/inbox');
    }

    function delete($ID) {
        ////////////////SISTEM KEAMANAN////////////////
            $state_id_user = $this->session->userdata('ID_USER');
            $check_protect = protectMailbox($state_id_user,'T_PESAN_MASUK',$ID);
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                $this->session->set_flashdata('error_message', 'error_message');
                $this->session->set_flashdata('message', 'Anda tidak memiliki akses pada data ini !!');
                redirect('portal/mailbox');
                return;
            }
        ////////////////SISTEM KEAMANAN////////////////


        if ($ID) {
            $this->db->where('ID', $ID);
            $v = $this->db->delete('T_PESAN_MASUK');
            if ($v) {
                $this->session->set_flashdata('success_message', 'success_message');
                $this->session->set_flashdata('message', 'Pesan Berhasil DiHapus !!');
            }
            redirect('portal/mailbox');
        }
    }

    function delete_multi() {
        $ID = $this->input->post("ID_DELETE");
        $ARR = explode(',', $ID);
        $count = 0;
        if ($ARR) {
            foreach ($ARR as $key) {
                ////////////////SISTEM KEAMANAN////////////////
                $state_id_user = $this->session->userdata('ID_USER');
                $check_protect = protectMailbox($state_id_user,'T_PESAN_MASUK',$key);
                if($check_protect){
                    $method = __METHOD__;
                    $this->load->model('mglobal');
                    $this->mglobal->recordLogAttacker($check_protect,$method);
                    $this->session->set_flashdata('error_message', 'error_message');
                    $this->session->set_flashdata('message', 'Anda tidak memiliki akses pada data ini !!');
                    redirect('portal/mailbox');
                    return;
                }
              ////////////////SISTEM KEAMANAN////////////////
            }

            foreach ($ARR as $key) {
                $this->db->where('ID', $key);
                $v = $this->db->delete('T_PESAN_MASUK');
                if ($v) {
                    $count++;
                }
            }
        }
        if ($count > 0) {
            $this->session->set_flashdata('success_message', 'success_message');
            $this->session->set_flashdata('message', $count . ' Pesan Berhasil DiHapus !!');
        } else {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan sistem !!');
        }
        redirect('portal/mailbox');
    }

    function delete_oubox($ID) {
        if ($ID) {
            $this->db->where('ID', $ID);
            $v = $this->db->delete('T_PESAN_KELUAR');
            if ($v) {
                $this->session->set_flashdata('success_message', 'success_message');
                $this->session->set_flashdata('message', 'Pesan Berhasil DiHapus !!');
            }
            redirect('portal/mailbox');
        }
    }

    function delete_multi_outbox() {
        $ID = $this->input->post("ID_DELETE");
        $ARR = explode(',', $ID);
        $count = 0;
        if ($ARR) {
            foreach ($ARR as $key) {
                $this->db->where('ID', $key);
                $v = $this->db->delete('T_PESAN_KELUAR');
                if ($v) {
                    $count++;
                }
            }
        }
        if ($count > 0) {
            $this->session->set_flashdata('success_message', 'success_message');
            $this->session->set_flashdata('message', $count . ' Pesan Berhasil DiHapus !!');
        } else {
            $this->session->set_flashdata('error_message', 'error_message');
            $this->session->set_flashdata('message', 'Mohon Maaf , ada kesalahan sistem !!');
        }
        redirect('portal/mailbox/outbox');
    }

    function json_inbox() {
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        if ($cari) {
            $this->db->like('T_USER.NAMA', $cari);
            $this->db->or_like('T_PESAN_MASUK.SUBJEK', $cari);
            $this->db->or_like('m_inst_satker.INST_NAMA', $cari);
        }

        //echo $this->db->last_query();exit;

        $this->load->model('Msuratmasuk');
        list($obj, $jml) = $this->Msuratmasuk->get_all($this->session->userdata('ID_USER'), $cari, $iDisplayLength, $iDisplayStart);

        if ($obj) {
            foreach ($obj as $list) {

                if ($list->ID_PENGIRIM == 1) {
                    $text = 'E-Filling LHKPN';
                } else {
                    if ($list->INST_NAMA) {
                        $text = $list->NAMA . '( ' . $list->INST_NAMA . ' )';
                    } else {
                        $text = $list->NAMA;
                    }
                }

                $upload = null;
                if ($list->FILE) {
                    $real_file = ng::filesize_formatted('uploads/mail_out/' . $list->ID_PENGIRIM . '/' . $list->FILE);
                    $link = base_url() . 'uploads/mail_out/' . $list->ID_PENGIRIM . '/' . $list->FILE;
                    $upload = "<a href='" . $link . "' target='_blank'><i class='fa fa-file'></i> " . $real_file . "</a>";
                } else {
                    $upload = "<label class='label label-info'>Tidak ada file</label>";
                }

                $edit = "<a title='Lihat Pesan' style='margin-right:5px;' id='" . $list->ID . "'  href='javascript:void(0)' class='btn btn-default btn-sm edit-action'><i class='fa fa-search-plus'></i></a>";
                $delete = "<a title='Hapus Pesan' id='" . $list->ID . "'  href='javascript:void(0)' class='btn btn-default btn-sm delete-action'><i class='fa fa-trash' style='color:red'></i></a>";
                
                if ( $list->SUBJEK == 'Lembar Penyerahan dan Ikhtisar LHKPN' ) {
                    $cetak = "<a title='Cetak Ikhtisar' id='" . $list->ID . "' idlhkpn='" . $list->ID_LHKPN . "' cetak='ikhtisar' href='javascript:void(0)' class='btn btn-default btn-sm cetak-action'><i class='fa fa-print' style='color:green'></i></a>";
                } else if ( $list->SUBJEK == 'Pengumuman Harta Kekayaan PN' && !empty($list->ID_LHKPN) ) {
                    $cetak = "<a title='Cetak Pengumuman' id='" . $list->ID . "' idlhkpn='" . $list->ID_LHKPN . "' cetak='pengumuman' href='javascript:void(0)' class='btn btn-default btn-sm cetak-action'><i class='fa fa-print' style='color:green'></i></a>";
                } else if ( $list->SUBJEK == 'Daftar Kekurangan LHKPN' && !empty($list->ID_LHKPN) ) {
                    $cetak = "<a title='Cetak Lampiran Kekurangan' id='" . $list->ID . "' idlhkpn='" . $list->ID_LHKPN . "' cetak='verifikasi0' href='javascript:void(0)' class='btn btn-default btn-sm cetak-action'><i class='fa fa-print' style='color:green'></i></a>";
                } else if ( $list->SUBJEK == 'Tanda Terima ( Verifikasi )' && !empty($list->ID_LHKPN) ) {
                    $cetak = "<a title='Cetak Tanda Terima' id='" . $list->ID . "' idlhkpn='" . $list->ID_LHKPN . "' cetak='verifikasi1' href='javascript:void(0)' class='btn btn-default btn-sm cetak-action'><i class='fa fa-print' style='color:green'></i></a>";
                } else if ( $list->SUBJEK == 'Lampiran Surat Kuasa Mengumumkan LHKPN' && !empty($list->ID_LHKPN) ) {
                    $cetak = "<a title='Cetak Surat Kuasa Mengumumkan' id='" . $list->ID . "' idlhkpn='" . $list->ID_LHKPN . "' cetak='skm' href='javascript:void(0)' class='btn btn-default btn-sm cetak-action'><i class='fa fa-print' style='color:green'></i></a>";
                } else if ( substr($list->SUBJEK, 0, 25) == 'Lampiran Surat Kuasa a.n.' && !empty($list->ID_KELUARGA) && !empty($list->ID_LHKPN) ) {
                    $cetak = "<a title='Cetak Surat Kuasa' id='" . $list->ID . "' idlhkpn='" . strtr(base64_encode($list->ID_LHKPN), '+/=', '-_~') . "' idkel='" . strtr(base64_encode($list->ID_KELUARGA), '+/=', '-_~') . "' cetak='sk' href='javascript:void(0)' class='btn btn-default btn-sm cetak-action'><i class='fa fa-print' style='color:green'></i></a>";
                } else {
                    $cetak = "";
                }
                
                $i++;
                $id_check = $list->ID;
                $tahuns = $list->TANGGAL_MASUK;
                $times = strtotime($tahuns);
                $tahun = date('d-m-Y', $times);

                $aaData[] = array(
                    '<input type="checkbox" class="t-body-center" value="' . $id_check . '" id="id-' . $id_check . '">',
                    $i,
                    $text,
                    $list->SUBJEK,
                    //$upload,
                    $tahun,
                    $edit . '' . $delete . '' . $cetak
                );
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
    }

    function json_outbox() {
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        if ($cari) {
            $this->db->like('T_USER.NAMA', $cari);
            $this->db->or_like('T_PESAN_KELUAR.SUBJEK', $cari);
            $this->db->or_like('m_inst_satker.INST_NAMA', $cari);
        }
        $this->db->limit($iDisplayLength, $iDisplayStart);
        $this->db->where('T_PESAN_KELUAR.IS_ACTIVE', '1');
        $this->db->where('T_PESAN_KELUAR.ID_PENGIRIM', $this->session->userdata('ID_USER'));
        $this->db->order_by('T_PESAN_KELUAR.TANGGAL_KIRIM', 'DESC');
        $this->db->join('T_USER', 'T_USER.ID_USER = T_PESAN_KELUAR.ID_PENGIRIM');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
        $obj = $this->db->get('T_PESAN_KELUAR')->result();
        if ($obj) {
            foreach ($obj as $list) {

                if ($list->INST_NAMA) {
                    $text = $list->NAMA . '( ' . $list->INST_NAMA . ' )';
                } else {
                    $text = $list->NAMA;
                }

                $upload = null;
                if ($list->FILE) {
                    $real_file = ng::filesize_formatted('uploads/mail_out/' . $list->ID_PENGIRIM . '/' . $list->FILE);
                    $link = base_url() . 'uploads/mail_out/' . $list->ID_PENGIRIM . '/' . $list->FILE;
                    $upload = "<a href='" . $link . "' target='_blank'><i class='fa fa-file'></i> " . $real_file . "</a>";
                } else {
                    $upload = "<label class='label label-info'>Tidak ada file</label>";
                }

                $edit = "<a style='margin-right:5px;' id='" . $list->ID . "'  href='javascript:void(0)' class='btn btn-default btn-sm edit-action'><i class='fa fa-search-plus'></i></a>";
                $delete = "<a id='" . $list->ID . "'  href='javascript:void(0)' class='btn btn-default btn-sm delete-action'><i class='fa fa-trash' style='color:red'></i></a>";

                $i++;
                $id_check = $list->ID;
                $tahun = $list->TANGGAL_KIRIM;

                $time = strtotime($tahun);
                $tahun = date('d-m-Y', $time);
                $aaData[] = array(
                    '<input type="checkbox" class="t-body-center" value="' . $id_check . '" id="id-' . $id_check . '">',
                    $i,
                    $text,
                    $list->SUBJEK,
                    $upload,
                    $tahun,
                    $edit . '' . $delete
                );
            }
        }
        $this->db->where('T_PESAN_MASUK.IS_ACTIVE', '1');
        $this->db->where('T_PESAN_MASUK.ID_PENGIRIM', $this->session->userdata('ID_USER'));
        $this->db->order_by('T_PESAN_MASUK.TANGGAL_MASUK', 'DESC');
        $this->db->join('T_USER', 'T_USER.ID_USER = T_PESAN_MASUK.ID_PENGIRIM');
        $jml = $this->db->get('T_PESAN_MASUK')->num_rows();
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

    function show_outbox() {
        $id = $this->input->post_get('id');

        ////////////////SISTEM KEAMANAN////////////////
            $state_id_user = $this->session->userdata('ID_USER');
            $check_protect = protectMailbox($state_id_user,'T_PESAN_MASUK',$id);
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                echo 'alert_security';
                return;
            }
        ////////////////SISTEM KEAMANAN////////////////



        $this->load->model('Msuratmasuk');
        $data = $this->Msuratmasuk->get_detail($id);

        header('Content-Type: application/json');
        echo json_encode(array($data));
    }

    function test_session() {
        print_r($this->session->userdata);
    }

    function cetakbuktielhkpn($inbox_id=null) {
        $tahun = "";
        if (!isset($_GET['xpesa'])) {
            $ID = FALSE;
        } else {
            $ID = $_GET['xpesa'];
        }

        if($inbox_id){
            $ID = $inbox_id;
        }

        if ($ID) {
            $this->db->where('ID', $ID);
            $this->db->join('t_user', 't_user.ID_USER = T_PESAN_MASUK.ID_PENERIMA');
            $data = $this->db->get('T_PESAN_MASUK')->row();
        }
        IF ($data) {
            $this->db->select("
			`T_USER`.`NAMA`, `M_JABATAN`.`NAMA_JABATAN`, `M_UNIT_KERJA`.`UK_NAMA`, `M_SUB_UNIT_KERJA`.`SUK_NAMA`,`M_INST_SATKER`.`INST_NAMA`,`M_BIDANG`.`BDG_NAMA`,`T_LHKPN`.`TGL_LAPOR`,
			`T_LHKPN`.`JENIS_LAPORAN`,`T_LHKPN`.`ID_LHKPN_PREV`
			FROM `T_USER`
			  LEFT JOIN `T_PN`
				ON `T_PN`.`NIK` = `T_USER`.`USERNAME`
			  LEFT JOIN `T_LHKPN`
				ON `T_PN`.`ID_PN` = `T_LHKPN`.`ID_PN`
			  LEFT JOIN `T_LHKPN_JABATAN`
				ON `T_LHKPN_JABATAN`.`ID_LHKPN` = `T_LHKPN`.`ID_LHKPN`
			  LEFT JOIN `M_JABATAN`
				ON `M_JABATAN`.`ID_JABATAN` = `T_LHKPN_JABATAN`.`ID_JABATAN`
			  LEFT JOIN `M_SUB_UNIT_KERJA`
				ON `M_SUB_UNIT_KERJA`.`SUK_ID` = `M_JABATAN`.`SUK_ID`
			  LEFT JOIN `M_UNIT_KERJA`
				ON `M_UNIT_KERJA`.`UK_ID` = `M_JABATAN`.`UK_ID`
			  LEFT JOIN `M_INST_SATKER`
				ON `M_INST_SATKER`.`INST_SATKERKD` = `M_JABATAN`.`INST_SATKERKD`
			  LEFT JOIN `M_BIDANG`
				ON `M_BIDANG`.`BDG_ID` = `M_INST_SATKER`.`INST_BDG_ID`
			  LEFT JOIN `T_PESAN_MASUK`
				ON `T_PESAN_MASUK`.`ID` = '$data->ID'
			WHERE `T_USER`.`IS_ACTIVE` ='1'
			  AND `T_USER`.`ID_USER` = '$data->ID_USER'
			  AND `IS_PRIMARY` = '1'
			  -- AND `t_lhkpn`.`STATUS` = '1'
			  AND `M_JABATAN`.`NAMA_JABATAN` IS NOT NULL
			  AND `T_LHKPN`.`ID_LHKPN` = `T_PESAN_MASUK`.`ID_LHKPN`
			", FALSE);
            $data = $this->db->get()->row();
            //echo $this->db->last_query();exit;

            $tahun = get_format_tanggal_lapor_lhkpn($data->JENIS_LAPORAN, $data->TGL_LAPOR);
            //echo $this->db->last_query();exit;

            $this->db->select('STATUS');
            $this->db->where("ID_LHKPN = ". $data->ID_LHKPN_PREV);
            $check_status_lhkpn_prev = $this->db->get('T_LHKPN')->row();
            $ID_LHKPN_PREV = $check_status_lhkpn_prev->STATUS;

            $tambahan_kalimat = "Berdasrkan data yang kami miliki bahwa laporan LHKPN terdahulu Saudara tercatat tidak lengkap, oleh karena itu mohon segera mencetak dan menandatangani di atas meterai setiap nama dalam Surat Kuasa yang terlampir dalam email ini dan mengirimkannya ke Direktorat Pendaftaran dan Pemeriksaan LHKPN KPK. Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta dan Data Keluarga aplikasi e-Filing LHKPN.";

        }
        if ($data) {

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/lembarpenyerahanformulir.docx";

            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            if ($load_template_success) {
                $this->lwphpword->set_value("NAMA_LENGKAP", isExist($data->NAMA));
                $this->lwphpword->set_value("JABATAN", isExist($data->NAMA_JABATAN));
                $this->lwphpword->set_value("SUK", isExist($data->SUK_NAMA));
                $this->lwphpword->set_value("UK", isExist($data->UK_NAMA));
                $this->lwphpword->set_value("BIDANG", isExist($data->BDG_NAMA));
                $this->lwphpword->set_value("LEMBAGA", isExist($data->INST_NAMA));
                $this->lwphpword->set_value("TANGGAL", isExist($tahun));
                $this->lwphpword->set_value("TAMBAHAN_KALIMAT", ($ID_LHKPN_PREV == '2' || $ID_LHKPN_PREV == '5' || $ID_LHKPN_PREV == '6') ? $tambahan_kalimat : " ");

                $save_document_success = $this->lwphpword->save_document();

                if ($save_document_success) {
                    $output_filename = "Bukti Pengiriman LHKPN" . date('d-F-Y') . ".docx";
                    $this->lwphpword->download($save_document_success, $output_filename);
                    //delete file after download
                    unlink($save_document_success);
                }
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        }
    }

}