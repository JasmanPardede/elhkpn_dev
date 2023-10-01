<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Review_harta extends MY_Controller {

    const ENABLE_SEND_SMS = TRUE;

    private $sess_template_data = [
        "id_user" => NULL,
        "pesan" => NULL,
        "subject" => NULL,
        "word_location" => NULL,
        "is_trusted" => TRUE
    ];
    private $sess_template_kirim_lhkpn = [
        "data" => [], //see $this->sess_template_data
        "id_lhkpn" => NULL,
    ];

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
    }

    function index() {
        $options = array();
        $this->load->view('portal/filing/review_harta', $options);
//                $curl_data= 'SEND={"tujuan":"085640763677","isiPesan":"Kode Token Pengiriman LHKPN adalah xxxx","idModem":6}';
//                CallURLPage('http://10.102.0.70:3333/sendSMS', $curl_data);
    }

    function data_pribadi($ID_LHKPN, $OPTION = NULL) {

        if(!is_null($OPTION)){
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('STATUS_SURAT_PERNYATAAN' => '' . $OPTION));
        }

        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $data = $this->db->get('t_lhkpn_data_pribadi')->row();

        /* $date1 = $data->TGL_LAPOR;
          $date2 = date('Y-m-d');
          $diff = abs(strtotime($date2) - strtotime($date1));
          $years   = floor($diff / (365*60*60*24));

          $result = null;
          if((int)$years>5){
          $result = $data;
          } */



        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }

    function qrcode_test($data = FALSE, $return = FALSE) {
        $this->load->library('ciqrcode');
        $this->load->model('Cqrcode');
        //$this->load->model('Cqrcode_model');
        //$tgl_surat    = $this->input->post('tgl_surat');
        //$jenis_surat  = $this->input->post('jenis_surat');
        //$isi_surat    = $this->input->post('isi_surat');
        //$filename     = $this->input->post('filename');
        $tgl_surat = '2016-02-24 00:00:00';
        $jenis_surat = 'LHKPN Bukti';
        $isi_surat = '{"data":[{"tipe":"1", "judul":"Bidang", "isi": "Eksekutif"},'
                . '{"tipe":"1", "judul":"Lembaga", "isi": "KEMENTERIAN KESEHATAN"},'
                . '{"tipe":"1", "judul":"NHK", "isi": "262688"},'
                . '{"tipe":"1", "judul":"Nama", "isi": "NOEGRAHARTI"},'
                . '{"tipe":"1", "judul":"Jabatan", "isi": " KEPALA UNIT - LAYANAN PENGADAAN - RUMAH SAKIT UMUM PUSAT NASIONAL DR CIPTO MANGUNKUSUMO"},'
                . '{"tipe":"1", "judul":"Tanggal Pelaporan", "isi": "22/06/2015"},'
                . '{"tipe":"1", "judul":"Harta Tidak Bergerak", "isi": "350000000"},'
                . '{"tipe":"1", "judul":"Alat Transportasi", "isi": "251000000"},'
                . '{"tipe":"1", "judul":"Pertanian", "isi": "0,0000"},'
                . '{"tipe":"1", "judul":"Harta Bergerak Lain", "isi": "52875000"},'
                . '{"tipe":"1", "judul":"Surat Berharga", "isi": "(RP)0,0000 - (USD)0,0000"},'
                . '{"tipe":"1", "judul":"Kas", "isi": "(RP)113000000 - (USD)0,0000"},'
                . '{"tipe":"1", "judul":"Piutang", "isi": "(RP)0,0000(USD)0,0000"},'
                . '{"tipe":"1", "judul":"Hutang", "isi": "(RP)138381000(USD)0"}]}';
        $filename = '262688-96-TA1-1';

//		$data_qrcode = array(
//						'no_surat'     => $data,
//						'tgl_surat'    => $tgl_surat,
//						'jenis_surat'  => $jenis_surat,
//						'isi_surat'    => $isi_surat,
//						'filename'     => $filename,
//
//		);

        $data_qrcode = array(
            'no_surat' => 'TBN-009/2016',
            'tgl_surat' => '20/12/2016',
            'jenis_surat' => 'Pengumuman',
            'isi_surat' => 'aaaaaaaaaaaaa'
        );


        $iv = 'kpkqrcode0123456';
        $key = pack('H*', md5('basQRov'));
        $key_size = strlen($key);
        //echo "Key size: " . $key_size . "\n";
        $dataurl = 'http://ceksurat.kpk.go.id/get_datasurat/';
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv));

        $encrypted = preg_replace('/[^A-Za-z0-9\-]/', '', $encrypted);
        $data_qrcode['no_qrcode'] = $encrypted;

        $insert_cqrcode = $this->Cqrcode->insert_cqrcode($data_qrcode);
        $params['data'] = $dataurl . $encrypted;
        $params['level'] = 'L';
        $params['size'] = 3;
        $params['savename'] = FCPATH . 'tes.png';
        $this->ciqrcode->generate($params);
        $im = imagecreatefrompng("tes.png");

        if ($return) {
            return $params['savename'];
        }
        header('Content-Type: image/png');

        imagepng($im);
        imagedestroy($im);
        //header('Content-Type: image/png');
        //include 'tes.png';
    }

    /**
     * LAMPIRAN 3 (Surat Kuasa Mengumumkan)
     * @param int $ID_LHKPN
     * @param mixed $OPTION
     */

    function save_surat_persetujuan_integrasi($ID_LHKPN) {

        $data = $this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);

        // $data->persetujuan_date = $this->input->get('persetujuan_date');
        $data->persetujuan_date = date('Y-m-d');
        $data->option = $this->input->get('option');

        $SURAT_PERSETUJUAN_INTEGRASI_DATA = $this->load->view('filing/surat_persetujuan_integrasi_data', array('data' => $data), TRUE);
        
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->update('t_lhkpn', array('STATUS_PERSETUJUAN_INTEGRASI_DATA' => $data->option, 'SURAT_PERSETUJUAN_INTEGRASI_DATA' => $SURAT_PERSETUJUAN_INTEGRASI_DATA, 'TGL_PERSETUJUAN_INTEGRASI_DATA' => $data->persetujuan_date));
        
        header('Content-type: application/json');
        echo json_encode('sukses');

        exit;

    }

    function surat_kuasa_pdf($ID_LHKPN, $OPTION = NULL) {
        date_default_timezone_set('Asia/Jakarta');
//        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
//        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
//        $data = $this->db->get('t_lhkpn_data_pribadi')->row();

        $data = $this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);

        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $check = $this->db->get('t_lhkpn')->row();

        if ($check) {

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/Lampiran3Template.docx";

            /*
              $_temp_dir = FALSE, $_model_qr = FALSE, $_callable_model_function = FALSE
             */
            /*
             * penulisan qrcode
             */
            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH . "../images/qrcode/"
            ]);

            // $this->load->library('ey_barcode');

            $ALAMAT_LENGKAP = $data->ALAMAT_RUMAH . ' , ' . $data->KELURAHAN . ' , ' . $data->KECAMATAN . ' ,  ' . $data->KABKOT . ' , ' . $data->PROVINSI;

            if ($data->NEGARA == '2') {
                $ALAMAT_LENGKAP = strtoupper($data->ALAMAT_NEGARA);
            }

            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $data->NIK],
                            (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $data->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "Tempat/Tgl. Lahir", "isi" => $data->TEMPAT_LAHIR . "/" . tgl_format($data->TANGGAL_LAHIR)],
                            (object) ["tipe" => '1', "judul" => "Alamat Lengkap", "isi" => $ALAMAT_LENGKAP],
//                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($data->tgl_kirim)],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($data->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Instansi", "isi" => $data->INST_NAMA],
                        ],
                        "encrypt_data" => $ID_LHKPN . 'skm',
                        "id_lhkpn" => $ID_LHKPN,
                        "judul" => "Surat Kuasa Mengumumkan E-LHKPN",
                        "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_file = "tes_qr-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

            /*
             * end penulisan qrcode
             */

            /*
             * load template
             */
            $get_nik = $data->NIK;
            $get_nama = $data->NAMA_LENGKAP;
            $get_status = "SKM PN";
//             if($KELUARGA->HUBUNGAN==1){
//                 $get_hubungan =  "ISTRI";
//             }elseif($KELUARGA->HUBUNGAN==2){
//                 $get_hubungan =  "SUAMI";
//             }elseif($KELUARGA->HUBUNGAN==3){
//                 $get_hubungan =  "ANAK";
//             }else{
//                 $get_hubungan = "-";
//             }

            // $show_barcode = "'".$get_nik.chr(9).$get_nama.chr(9).$get_status;
            // $bc_file = "tes_bc2-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".jpg";
            // $bc_image_location = $this->ey_barcode->generate($show_barcode, $bc_file);

            $show_qr2 = "'".$get_nik.chr(9).$get_nama.chr(9).$get_status;
            $qr2_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr2_image_location = $this->lws_qr->create($show_qr2, $qr2_file);

            // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array(
            //     "image1.png" => $qr_image_location,"image2.png" => $bc_image_location
            // ));


//             $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array(
//                 "image1.png" => $qr_image_location
//             ));

            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            // if ($load_template_success) {
//                $template_string_nosttpp = 'no_sttpp#' . ($key + 1);


//                 $this->lwphpword->set_value("NAMA_LENGKAP", $data->NAMA_LENGKAP == NULL ? '-' : $data->NAMA_LENGKAP);
//                 $this->lwphpword->set_value("TEMPAT_LAHIR", $data->TEMPAT_LAHIR == NULL ? '-' : $data->TEMPAT_LAHIR);
//                 $this->lwphpword->set_value("TANGGAL_LAHIR", tgl_format($data->TANGGAL_LAHIR) == NULL ? '-' : tgl_format($data->TANGGAL_LAHIR));
//                 $this->lwphpword->set_value("NIK", $data->NIK == NULL ? '-' : $data->NIK);
//                 $this->lwphpword->set_value("ALAMAT_LENGKAP", $ALAMAT_LENGKAP == NULL ? '-' : $ALAMAT_LENGKAP);
// //                $this->lwphpword->set_value("TGL_KIRIM", tgl_format($data->tgl_kirim) == NULL ? '-' : tgl_format($data->tgl_kirim));
//                 $this->lwphpword->set_value("TGL_KIRIM", date('d-F-Y'));


//                $save_document_success = $this->lwphpword->save_to_pdf($ID_LHKPN, 'L');
                // $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE);

                $SURAT_UMUMKAN = $this->load->view('filing/surat_kuasa', array('data' => $data, 'OPTION' => $OPTION), TRUE);

                ///////////////PDF//////////////
                $data = array(
                    "NAMA_LENGKAP" => $data->NAMA_LENGKAP == NULL ? '-' : $data->NAMA_LENGKAP,
                    "TEMPAT_LAHIR" => $data->TEMPAT_LAHIR == NULL ? '-' : $data->TEMPAT_LAHIR,
                    "TANGGAL_LAHIR" => tgl_format($data->TANGGAL_LAHIR) == NULL ? '-' : tgl_format($data->TANGGAL_LAHIR),
                    "NIK" => $data->NIK == NULL ? '-' : $data->NIK,
                    "ALAMAT_LENGKAP" => $ALAMAT_LENGKAP == NULL ? '-' : $ALAMAT_LENGKAP,
                    "TGL_KIRIM" => date('d-F-Y'),
                    // "BC_IMAGE_LOCATION"=>$bc_image_location,
                    "QR_IMAGE_LOCATION"=>$qr_image_location,
                    "QR2_IMAGE_LOCATION"=>$qr2_image_location
                );
                //    $this->load->library('dom_pdf');
                $this->load->library('pdfgenerator');
                $html = $this->load->view('pdf/surat_kuasa_mengumumkan_pdf', $data, true);
                $filename = time() . '_surat_kuasa_mengumumkan_'.$ID_LHKPN;
                $method = "store";
                $path_file = "file/wrd_gen/";
                $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'landscape',$path_file);

                //////////////PDF//////////////


                if ($save_document_success) {
                    $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
                    $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => $OPTION, 'SURAT_UMUMKAN' => $SURAT_UMUMKAN, 'CETAK_SURAT_UMUMKAN_TIME' => date('Y-m-d H:i:s')));

                    $output_filename = "Surat-Kuasa-Mengumumkan-" . date('d-F-Y') . "." . $ID_LHKPN;

                    $pesan = $this->load->view('filing/review_harta/pesan_surat_kuasa_mengumumkan', array(
                        "nama_pn" => $data->NAMA_LENGKAP,
                        "nama_instansi" => $data->NAMA_LEMBAGA,
                            ), TRUE);

                    $subject = "Lampiran Surat Kuasa Mengumumkan LHKPN";

                    $surat_mengumumkan = (object) $this->sess_template_data;
                    $surat_mengumumkan->id_user = $this->session->userdata('ID_USER');
                    $surat_mengumumkan->pesan = $pesan;
                    $surat_mengumumkan->subject = $subject;
                    // $surat_mengumumkan->word_location = "../../../file/wrd_gen/" . $filename . ".pdf";

                    $this->__send_session_kirim_lhkpn($ID_LHKPN, $surat_mengumumkan, TRUE);
                    unset($surat_mengumumkan);

                    // $this->__send_to_mailbox($this->session->userdata('ID_USER'), $pesan, $subject, "../../../file/wrd_gen/" . $save_document_success->document_name . ".docx", TRUE);
                    //$this->lwphpword->download($save_document_success, $output_filename, 'pdf');
                    //gwe_dump($save_document_success, TRUE);
                    // $this->lwphpword->download($save_document_success->document_path, $output_filename);
                }
            $save_document_success = $this->pdfgenerator->generate($html, $filename, 'stream', 'A4', 'landscape');
            $temp_dir = APPPATH."../images/qrcode/";
            unlink($temp_dir.$qr_file);
            unlink($temp_dir.$qr2_file);
            // $temp_dir_br = APPPATH."../uploads/barcode/";
            // unlink($temp_dir_br.$bc_file);
            unlink($path_file.$filename.'.pdf');
            return;
            // }
        }
    }

    function surat_kuasa_mengumumkan_individual($ID_LHKPN, $OPTION = NULL) {
        date_default_timezone_set('Asia/Jakarta');

        $data = $this->mglobal->get_detail_pn_lhkpn($ID_LHKPN, TRUE, TRUE);

        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $check = $this->db->get('t_lhkpn')->row();

        if ($check) {

            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/Lampiran3Template.docx";

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH . "../images/qrcode/"
            ]);

            $this->load->library('ey_barcode');

            $ALAMAT_LENGKAP = $data->ALAMAT_RUMAH . ' , ' . $data->KELURAHAN . ' , ' . $data->KECAMATAN . ' ,  ' . $data->KABKOT . ' , ' . $data->PROVINSI;

            if ($data->NEGARA == '2') {
                $ALAMAT_LENGKAP = strtoupper($data->ALAMAT_NEGARA);
            }

            $qr_content_data = json_encode((object) [
                        "data" => [
                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $data->NIK],
                            (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $data->NAMA_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "Tempat/Tgl. Lahir", "isi" => $data->TEMPAT_LAHIR . "/" . tgl_format($data->TANGGAL_LAHIR)],
                            (object) ["tipe" => '1', "judul" => "Alamat Lengkap", "isi" => $ALAMAT_LENGKAP],
                            (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($data->tgl_kirim_final)],
                            (object) ["tipe" => '1', "judul" => "Instansi", "isi" => $data->INST_NAMA],
                        ],
                        "encrypt_data" => $ID_LHKPN . 'skm',
                        "id_lhkpn" => $ID_LHKPN,
                        "judul" => "Surat Kuasa Mengumumkan E-LHKPN",
                        "tgl_surat" => date('Y-m-d'),
            ]);

            $qr_file = "tes_qr-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
            $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

            $get_nik = $data->NIK;
            $get_nama = $data->NAMA_LENGKAP;
            $get_status = "SKM PN";

            $show_barcode = "'".$get_nik.chr(9).$get_nama.chr(9).$get_status;

            $bc_file = "tes_bc2-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".jpg";
            $bc_image_location = $this->ey_barcode->generate($show_barcode, $bc_file);

            // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array(
            //     "image1.png" => $qr_image_location,"image2.png" => $bc_image_location
            // ));

            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            // if ($load_template_success) {
            //     $this->lwphpword->set_value("NAMA_LENGKAP", $data->NAMA_LENGKAP == NULL ? '-' : $data->NAMA_LENGKAP);
            //     $this->lwphpword->set_value("TEMPAT_LAHIR", $data->TEMPAT_LAHIR == NULL ? '-' : $data->TEMPAT_LAHIR);
            //     $this->lwphpword->set_value("TANGGAL_LAHIR", tgl_format($data->TANGGAL_LAHIR) == NULL ? '-' : tgl_format($data->TANGGAL_LAHIR));
            //     $this->lwphpword->set_value("NIK", $data->NIK == NULL ? '-' : $data->NIK);
            //     $this->lwphpword->set_value("ALAMAT_LENGKAP", $ALAMAT_LENGKAP == NULL ? '-' : $ALAMAT_LENGKAP);
            //     $this->lwphpword->set_value("TGL_KIRIM", date('d-F-Y'));

                $SURAT_UMUMKAN = $this->load->view('filing/surat_kuasa', array('data' => $data, 'OPTION' => $OPTION), TRUE);

                ///////////////PDF//////////////
                $data = array(
                    "NAMA_LENGKAP" => $data->NAMA_LENGKAP == NULL ? '-' : $data->NAMA_LENGKAP,
                    "TEMPAT_LAHIR" => $data->TEMPAT_LAHIR == NULL ? '-' : $data->TEMPAT_LAHIR,
                    "TANGGAL_LAHIR" => tgl_format($data->TANGGAL_LAHIR) == NULL ? '-' : tgl_format($data->TANGGAL_LAHIR),
                    "NIK" => $data->NIK == NULL ? '-' : $data->NIK,
                    "ALAMAT_LENGKAP" => $ALAMAT_LENGKAP == NULL ? '-' : $ALAMAT_LENGKAP,
                    "TGL_KIRIM" => date('d-F-Y'),
                    "BC_IMAGE_LOCATION"=>$bc_image_location,
                    "QR_IMAGE_LOCATION"=>$qr_image_location
                );
                $this->load->library('pdfgenerator');
                $html = $this->load->view('pdf/surat_kuasa_mengumumkan_pdf', $data, true);
                $filename = time() . 'surat_kuasa_mengumumkan'.$ID_LHKPN;
                $method = "store";
                $path_file = "file/wrd_gen/";
                $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'landscape',$path_file);

                //////////////PDF//////////////

                if ($save_document_success) {
                    $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
                    $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => $OPTION, 'SURAT_UMUMKAN' => $SURAT_UMUMKAN, 'CETAK_SURAT_UMUMKAN_TIME' => date('Y-m-d H:i:s')));

                    $output_filename = "Surat-Kuasa-Mengumumkan-" . date('d-F-Y') . "." . $ID_LHKPN;

                    $pesan = $this->load->view('filing/review_harta/pesan_surat_kuasa_mengumumkan', array(
                        "nama_pn" => $data->NAMA_LENGKAP,
                        "nama_instansi" => $data->NAMA_LEMBAGA,
                            ), TRUE);

                    $subject = "Lampiran Surat Kuasa Mengumumkan LHKPN";

                    $surat_mengumumkan = (object) $this->sess_template_data;
                    $surat_mengumumkan->id_user = $this->session->userdata('ID_USER');
                    $surat_mengumumkan->pesan = $pesan;
                    $surat_mengumumkan->subject = $subject;
                    $surat_mengumumkan->word_location = "../../../file/wrd_gen/" . $filename . ".pdf";

                    //ini buat kirim skm ke mailbox
                    // $this->__send_to_mailbox($this->session->userdata('ID_USER'), $pesan, $subject, "../../../file/wrd_gen/".$filename. ".pdf", TRUE);
                    //ini buat preview skm
//                    $save_document_success = $this->pdfgenerator->generate($html, $filename, 'stream', 'A4', 'landscape');
                }
            $temp_dir = APPPATH."../images/qrcode/";
            unlink($temp_dir.$qr_file);
            $temp_dir_br = APPPATH."../uploads/barcode/";
            unlink($temp_dir_br.$bc_file);
            // }
        }
    }

    /**
     *
     * @param object $sess_template_data $this->sess_kirim_lhkpn
     */
    private function __send_session_kirim_lhkpn($ID_LHKPN, $sess_template_data, $lampiran_3 = FALSE) {
        /**
         * check kemudian remove session terlebih dahulu
         * dengan maksud agar tidak tumpang tindih
         */
        if ($this->session->userdata('sess_kirim_lhkpn') && $lampiran_3) {
            $this->session->unset_userdata('sess_kirim_lhkpn');

            $this->session->set_userdata('sess_kirim_lhkpn', (object) $this->sess_template_kirim_lhkpn);
        }


        $sess_kirim_lhkpn = $this->session->userdata('sess_kirim_lhkpn');
        $sess_kirim_lhkpn->data[] = $sess_template_data;
        $sess_kirim_lhkpn->id_lhkpn = $ID_LHKPN;

        $this->session->set_userdata('sess_kirim_lhkpn', $sess_kirim_lhkpn);

        unset($sess_kirim_lhkpn);
    }

    function __send_to_mailbox($idUser, $pesan, $subject, $file = FALSE, $trusted = FALSE, $id_pengirim = 1, $idlhkpn = NULL, $idkeluarga = NULL) {
        $this->load->model('Msuratkeluar');
        $this->Msuratkeluar->send_message($id_pengirim, $idUser, "", $subject, $pesan, FALSE, $file, $trusted, $idlhkpn, $idkeluarga);
    }

    function surat_kuasa_lembarpenyerahan($ID_LHKPN, $OPTION = NULL) {

        date_default_timezone_set('Asia/Jakarta');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $data = $this->db->get('t_lhkpn_data_pribadi')->row();
        $judul = strtoupper($data->NIK . '_' . substr($data->TGL_LAPOR, 0, 4));
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $check = $this->db->get('t_lhkpn')->row();
        if ($check) {
            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));
            $template_file = "../file/template/lembarpenyerahanformulir.docx";
            $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);
            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";
            if ($load_template_success) {
                $ALAMAT_LENGKAP = $data->ALAMAT_RUMAH . ' , ' . $data->KELURAHAN . ' , ' . $data->KECAMATAN . ' ,  ' . $data->KABKOT . ' , ' . $data->PROVINSI;

                if ($data->NEGARA == '2') {
                    $ALAMAT_LENGKAP = strtoupper($data->ALAMAT_NEGARA);
                }
                $this->lwphpword->set_value("NAMA_LENGKAP", $data->NAMA_LENGKAP);
                $this->lwphpword->set_value("TEMPAT_LAHIR", $data->TEMPAT_LAHIR);
                $this->lwphpword->set_value("TANGGAL_LAHIR", tgl_format($data->TANGGAL_LAHIR));
                $this->lwphpword->set_value("NIK", $data->NIK);
                $this->lwphpword->set_value("ALAMAT_LENGKAP", $ALAMAT_LENGKAP);
                $this->lwphpword->set_value("TGL_KIRIM", tgl_format($data->tgl_kirim));
                $save_document_success = $this->lwphpword->save_document();

                $SURAT_UMUMKAN = $this->load->view('filing/surat_kuasa', array('data' => $data, 'OPTION' => $OPTION), TRUE);
                if ($save_document_success) {
                    $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
                    $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => $OPTION, 'SURAT_UMUMKAN' => $SURAT_UMUMKAN, 'CETAK_SURAT_UMUMKAN_TIME' => date('Y-m-d H:i:s')));

                    $output_filename = "Surat Kuasa Mengumumkan" . date('d-F-Y') . ".docx";
                    $this->lwphpword->download($save_document_success, $output_filename);
                }
                unlink("file/wrd_gen/".explode('wrd_gen/', $save_document_success)[1]);
            }
        }
    }

    /**
     * @author Lahir Wisada Santoso <lahirwisada@gmail.com>
     *
     * @param int $ID_LHKPN Menjelaskan Laporan LHKPN
     * @param int $INDEX Index Urutan daftar keluarga yang akan dicetak, awal akan berisi 1
     * @param int $OPTION Menyatakan telah menyetujui atau belum, tergantung dari radio button yg di klik, 1 atau 0
     */
    function data_keluarga($ID_LHKPN, $INDEX, $OPTION = NULL) {

        date_default_timezone_set('Asia/Jakarta');

        if (!empty($OPTION)) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => '' . $OPTION));
        }

        $this->db->select('t_lhkpn.*,CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN', FALSE);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $LHKPN = $this->db->get('t_lhkpn')->row();

        $this->db->select('
			t_lhkpn.ID_PN,
			t_lhkpn.TGL_LAPOR,
            m_jabatan.INST_SATKERKD,
			NULL AS ID,
			NULL AS ID_OLD,
			t_lhkpn_data_pribadi.NAMA_LENGKAP AS NAMA,
			t_lhkpn_data_pribadi.NIK,
			t_lhkpn_data_pribadi.TEMPAT_LAHIR,
			t_lhkpn_data_pribadi.TANGGAL_LAHIR,
			t_lhkpn_data_pribadi.ALAMAT_RUMAH,
            t_lhkpn_data_pribadi.KELURAHAN,
			t_lhkpn_data_pribadi.KECAMATAN,
			t_lhkpn_data_pribadi.KABKOT,
			t_lhkpn_data_pribadi.PROVINSI,
			NULL AS HUBUNGAN,
			NULL STATUS_KELUARGA,
			/*CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_data_pribadi.TANGGAL_LAHIR)/365)-1 AS UMUR,*/
			TIMESTAMPDIFF(YEAR,t_lhkpn_data_pribadi.TANGGAL_LAHIR,t_lhkpn.tgl_lapor) AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN,
            t_lhkpn.STATUS_CETAK_SURAT_KUASA
		', FALSE);
        $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $this->db->join('t_lhkpn_jabatan', "t_lhkpn_jabatan.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN");
        $this->db->join('m_jabatan', "m_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN");
        $PN = $this->db->get('t_lhkpn_data_pribadi')->row();

        $this->db->select('
    		t_lhkpn.ID_PN,
    		t_lhkpn.TGL_LAPOR,
    		t_lhkpn_keluarga.ID_KELUARGA AS ID,
    		t_lhkpn_keluarga.ID_KELUARGA_LAMA AS ID_OLD,
    		REPLACE(t_lhkpn_keluarga.NAMA, ",", " ") AS NAMA,
            t_lhkpn_keluarga.NIK,
    		t_lhkpn_keluarga.TEMPAT_LAHIR,
    		t_lhkpn_keluarga.TANGGAL_LAHIR,
    		t_lhkpn_keluarga.ALAMAT_RUMAH,
    		t_lhkpn_keluarga.HUBUNGAN,
    		t_lhkpn_keluarga.STATUS_CETAK_SURAT_KUASA AS STATUS_KELUARGA,
    		/*CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,*/
    		TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,t_lhkpn.tgl_lapor) AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
    	', FALSE);
        $this->db->group_by('t_lhkpn_keluarga.ID_KELUARGA');
        $this->db->order_by('t_lhkpn_keluarga.TANGGAL_LAHIR', 'ASC');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
//        $this->db->where('(CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1) >=17');
        $this->db->where('TIMESTAMPDIFF(YEAR,t_lhkpn_keluarga.TANGGAL_LAHIR,t_lhkpn.tgl_lapor) >=17');
        $this->db->where_in('t_lhkpn_keluarga.HUBUNGAN', array('1', '2', '3'));

//        if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
        if ($LHKPN->IS_COPY == '0' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
            $this->db->where('t_lhkpn_keluarga.ID_KELUARGA_LAMA IS NULL');
        }

        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN');
        $KELUARGA = $this->db->get('t_lhkpn_keluarga')->result();
//         var_dump($this->db->last_query());
//         break;
        if ($PN->ALAMAT_RUMAH) {
            $alamat_pn = $PN->ALAMAT_RUMAH;
        } else {
            $alamat_pn = $PN->ALAMAT_NEGARA;
        }

        $temp = array();
        $temp[1] = array(
            'ID' => NULL,
            'tgl_kirim' => $LHKPN->tgl_kirim,
            'NAMA' => $PN->NAMA,
            'TTL' => $PN->TEMPAT_LAHIR . ' - ' . tgl_format($PN->TANGGAL_LAHIR),
            'TEMPAT_LAHIR' => $PN->TEMPAT_LAHIR,
            'TANGGAL_LAHIR' => tgl_format($PN->TANGGAL_LAHIR),
            'NOMOR_KTP' => $PN->NIK,
            'NIK' => $PN->NIK,
            'ALAMAT' => $PN->ALAMAT_RUMAH . ', ' . $PN->KELURAHAN . ' , ' . $PN->KECAMATAN . ' ,  ' . $PN->KABKOT . ' , ' . $PN->PROVINSI,
            'LAST' => '0',
            'NEXT' => 'KUASA_KELUARGA2(2,"#ModalKuasaKeluarga")',
            'IS_KEMENKEU' => ($PN->INST_SATKERKD == $this->config->item('id_kemenkeu')) ? true : false,
            'UMUR' => $PN->UMUR,
            'LIMA_TAHUN' => '0',
            'STATUS_CETAK_SURAT_KUASA' => $PN->STATUS_CETAK_SURAT_KUASA
        );

        $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[1]), TRUE);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->update('t_lhkpn', array('STATUS_CETAK_SURAT_KUASA' => $OPTION, 'SURAT_KUASA' => $SURAT_KUASA, 'CETAK_SURAT_KUASA_TIME' => date('Y-m-d H:i:s')));

        $i_data = 1;
        $i = 2;
//        $i = 1;

        /**
         * PN ditambahkan pada array Keluarga, karena pn juga harus mencetak surat kuasa
         *
         * posisi PN akan selalu berada pada index array ke 0
         *
         *
         */
//        array_unshift($KELUARGA, $PN);
//        gwe_dump(array(count($KELUARGA), $i_data), TRUE);

        foreach ($KELUARGA as $row) {
            if ($i_data == count($KELUARGA)) {
                $last = '1';
//                 $next = '';
                $next = 'KUASA_KELUARGA2(' . ($i + 1) . ',"#ModalKuasaKeluarga")';
            } else {
                $last = '0';
                $next = 'KUASA_KELUARGA2(' . ($i + 1) . ',"#ModalKuasaKeluarga")';
            }
            if ((int) $row->TIME_LHKPN > 5) {
                $LIMA_TAHUN = '1';
            } else {
                $LIMA_TAHUN = '0';
            }
            $temp[$i] = array(
                'ID' => $row->ID,
                'NAMA' => $row->NAMA,
                'TTL' => $row->TEMPAT_LAHIR . ' - ' . tgl_format($row->TANGGAL_LAHIR),
                'NOMOR_KTP' => $row->NIK,
                'ALAMAT' => $row->ALAMAT_RUMAH,
                'LAST' => $last,
                'NEXT' => $next,
                'IS_KEMENKEU' => ($PN->INST_SATKERKD == $this->config->item('id_kemenkeu')) ? true : false,
                'UMUR' => $row->UMUR,
                'LIMA_TAHUN' => $LIMA_TAHUN,
                'STATUS_CETAK_SURAT_KUASA' => $row->STATUS_KELUARGA
            );

            $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[$i]), TRUE);
            $this->db->where('ID_KELUARGA', $row->ID);
            $this->db->update('t_lhkpn_keluarga', array('SURAT_KUASA' => $SURAT_KUASA));
            $i_data++;
            $i++;
        }

        $result = $temp[$INDEX];
        if ($INDEX == '1') {
            /**
             * IS_COPY terisi jika sudah ada data sebelumnya
             */
            if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) {
//                $result = $temp[2];
                $result = $temp[1];
            }
        }

        if($result == null){
             
            if($temp['1']['IS_KEMENKEU'] ==  true){
                $result = "IS_KEMENKEU";
            }
        }

        header('Content-type: application/json');
        echo json_encode($result);


        exit;
    }

    function info_harta($index) {
        $data = array();
        $data[4] = 'Data Harta Tidak Bergerak';
        $data[5] = 'Data Harta Bergerak Lain';
        $data[6] = 'Data Harta Bergerak';
        $data[7] = 'Data Harta Kas / Setara Kas';
        $data[8] = 'Data Harta Surat Berharga';
        $data[9] = 'Data Harta Lainnya';
        return $data[$index];
        exit;
    }

    function __count_data_harta($ID_LHKPN) {
        $query_harta = "select(select count(*) from (SELECT
                            t_lhkpn_harta_tidak_bergerak.ID_HARTA
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_tidak_bergerak`
                              ON `t_lhkpn_harta_tidak_bergerak`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              union
                          SELECT
                            t_lhkpn_harta_bergerak.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_bergerak`
                              ON `t_lhkpn_harta_bergerak`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_bergerak_lain.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_bergerak_lain`
                              ON `t_lhkpn_harta_bergerak_lain`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_surat_berharga.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_surat_berharga`
                              ON `t_lhkpn_harta_surat_berharga`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_kas.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_kas`
                              ON `t_lhkpn_harta_kas`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                          SELECT
                            t_lhkpn_harta_lainnya.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_harta_lainnya`
                              ON `t_lhkpn_harta_lainnya`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                              WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "'
                              UNION
                           SELECT
                            t_lhkpn_hutang.`ID_HARTA`
                          FROM
                            `t_lhkpn`
                            JOIN `t_lhkpn_hutang`
                              ON `t_lhkpn_hutang`.`ID_LHKPN` = `t_lhkpn`.`ID_LHKPN`
                          WHERE `t_lhkpn`.`ID_LHKPN` = '" . $ID_LHKPN . "') as co_harta) as c_harta";
        $count_harta = $this->db->query($query_harta)->row();
        return intval($count_harta->c_harta);
        exit;
    }

    function checklhkpn($ID_LHKPN, $INDEX = NULL) {

        $this->load->model('mlhkpn');

        $data = $this->mlhkpn->data_lhkpn();

        $data_lhkpn = $this->mlhkpn->getValue('t_lhkpn','where id_lhkpn = '.$ID_LHKPN.'')[0];
        $max_tahun_wl = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $data_lhkpn->ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'IS_WL' => '1'], 'TAHUN_WL', NULL, ['TAHUN_WL', 'DESC'])[0]->TAHUN_WL;

        $wl_tahun_now = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $data_lhkpn->ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')], 'TAHUN_WL')[0];
        $wl_thn_minus_1 = $this->mglobal->get_data_all('t_pn_jabatan', NULL, ['ID_PN' => $data_lhkpn->ID_PN, 'IS_ACTIVE' => '1', 'IS_DELETED' => '0', 'IS_CURRENT' => '1', 'ID_STATUS_AKHIR_JABAT' => '0', 'is_wl' => '1', 'tahun_wl' => date('Y')-1], 'TAHUN_WL')[0];

        $wl_tahun_now = $wl_tahun_now ? 1 : 0;
        $wl_thn_minus_1 = $wl_thn_minus_1 ? 1 : 0;


        $lhkpn_active = $this->mglobal->get_data_all('T_LHKPN',  NULL, ['T_LHKPN.IS_ACTIVE' => '1', 'T_LHKPN.ID_PN' => $data_lhkpn->ID_PN]);
        
        if(Count($lhkpn_active) > 1){
            $history = true;
        }else{
            $history = false;
        }

        ////////////////////VALIDASI STATUS LHKPN DAN TANGGAL PERIODE///////////////////////////
        $kill = false;
        if($data_lhkpn->STATUS==0 || $data_lhkpn->STATUS==7){
            $get_pn = $this->mglobal->get_data_by_id('t_lhkpn','id_lhkpn',$ID_LHKPN,false,true);

            $get_all_lhkpn = $this->mglobal->get_data_all_array('t_lhkpn',null,['ID_PN'=>$get_pn->ID_PN,'IS_ACTIVE'=>1],null,'id_lhkpn != '.$ID_LHKPN);

            $pelaporan_tanggal_today = tgl_format(date('Y-m-d'));
            $tahun_now = date('Y')-1;
            if($data_lhkpn->JENIS_LAPORAN==4){
                $tahun_lapor = substr($data_lhkpn->tgl_lapor,0,4);
                if($tahun_now!=$tahun_lapor){
                    $kill = true;
                }
                if(Count($get_all_lhkpn)==0){
                    $pelaporan_tanggal = '1 Januari '.$tahun_now;
                }else{
                    $tanggal_get = array();
                    foreach($get_all_lhkpn as $gal){
                        array_push($tanggal_get,$gal['tgl_lapor']);
                    }
                    rsort($tanggal_get);
                    if(substr($tanggal_get[0],0,4)<$tahun_now){
                        $pelaporan_tanggal = '1 Januari '.$tahun_now;
                    }else{
                        $pelaporan_tanggal = tgl_format($tanggal_get[0]);
                    }
                }
            }else{
                $tahun_elhkpn = date_format(date_create($get_pn->tgl_lapor),'Y');
                if($tahun_elhkpn < $tahun_now){
                    $kill = true;
                }
                $hasil_checking = 0;
                foreach($get_all_lhkpn as $gal){
                    if($get_pn->tgl_lapor <= $gal['tgl_lapor']){
                        $hasil_checking++;
                    }
                }
                if($hasil_checking >= 1){
                   $kill = true;
                }
                if(Count($get_all_lhkpn)==0){
                    $pelaporan_tanggal = '1 Januari '.$tahun_now;
                }else{
                    $tanggal_get = array();
                    foreach($get_all_lhkpn as $gal){
                        array_push($tanggal_get,$gal['tgl_lapor']);
                    }
                    rsort($tanggal_get);
                    if(substr($tanggal_get[0],0,4)<$tahun_now){
                        $pelaporan_tanggal = '1 Januari '.$tahun_now;
                    }else{
                        $pelaporan_tanggal = tgl_format($tanggal_get[0]);
                    }
                }
            }
            $data_kill = [
                "index" => 99,
                "table" => "t_lhkpn_harta_tidak_bergerak",
                "notif" => "Tahun/Tanggal Pelaporan untuk LHKPN Anda belum sesuai. Silahkan melakukan edit Tahun/Tanggal Pelaporan di tabel Riwayat Harta dengan cara klik tombol Edit Jenis Laporan",
                "notif_status" => "TIDAKKKKKKKKKKKKKK.",
                "view" => 99,
                "grid" => "#harta_tidak_bergerak",
                "ctr" => 0,
                "title" => "Data Harta Harta Tanah / Bangunan",
                "total_harta" => 0,
                "pelaporan_tahun" =>$tahun_now,
                "pelaporan_today" => $pelaporan_tanggal_today,
                "pelaporan_tanggal" => $pelaporan_tanggal,
                "state_id_lhkpn" => $ID_LHKPN
            ];
        }

        ////////////////////////// VALIDASI TAHUN LAPOR = MAX TAHUN WL ////////////
        if($data_lhkpn->STATUS == 0){ //status draft 

            $curr_tahun = date('Y'); //tahun sekarang

            $tgl_lapor = explode("-", $data_lhkpn->tgl_lapor);
            $thn_lapor = $tgl_lapor[0];

            // tahun lapor < tahun n-1
            if($thn_lapor < $curr_tahun-1){
                $kill = true;
            }

            $alert_msg = "";

            if($thn_lapor != $max_tahun_wl){ 
                $kill = true;
                
                if($wl_thn_minus_1 == 1 && $wl_tahun_now == 0 && $history && $thn_lapor == $curr_tahun){
                    $alert_msg = "E";
                }else if($wl_thn_minus_1 == 1 && $history){
                    $alert_msg = "A";
                }else if($history == false && $wl_tahun_now == 1 && $wl_thn_minus_1 == 1){
                    $alert_msg = "B";
                }else if($history == false && $wl_tahun_now == 0 && $wl_thn_minus_1 == 1){
                    $alert_msg = "C";
                }else if($thn_lapor < $max_tahun_wl){
                    $alert_msg = "D";
                }else if($history == false && $wl_tahun_now == 0 && $wl_thn_minus_1 == 0 && $thn_lapor >= $curr_tahun-1){
                    $alert_msg = "B";
                }
                // else{ 
                //     $alert_msg = "E";
                // } 
                
                if($wl_tahun_now == 1 || $wl_thn_minus_1 == 1){ 

                    if($wl_tahun_now == 0 && $wl_thn_minus_1 == 1 && $thn_lapor < $curr_tahun-1 && $history){
                        $alert_msg = "E";
                        $kill = true;

                    }else if($wl_tahun_now == 0 && $wl_thn_minus_1 == 1 && $thn_lapor < $curr_tahun-1 && $history == false){
                        $alert_msg = "C";
                        $kill = true;
                    
                    // jika ada draft di tahun n, sebelum tahun n di NON WL
                    }else if($history == false && $wl_tahun_now == 0 && $thn_lapor != $tahun_now){
                        $alert_msg = "C";
                        $kill = true;  
                    
                    //  jika ada draft di tahun n-1, sebelum tahun n-1 di NON WL
                    }else if($wl_thn_minus_1 == 0 && $thn_lapor != $max_tahun_wl){ 
                        $kill = true; 

                    // jika tahun n dan n-1 dan tahun lapor > tahun n-1, maka bisa lapor 
                    }else if($wl_tahun_now == 1 && $wl_thn_minus_1 == 1 && $thn_lapor >= $curr_tahun-1){  
                        $kill = false;
                    }

                }else{
                    // jika wl n dan wl n - 1 (non-active)
                    if($thn_lapor <= $curr_tahun-1 && $history == false){ 
                        $alert_msg = "B";
                    }else if($thn_lapor <= $curr_tahun-1 && $history){
                        $alert_msg = "A";;
                    }
                    
                }
               

            }else{ 
                if($wl_tahun_now == 0 && $wl_thn_minus_1 == 0 && $thn_lapor <= $curr_tahun-1 && $history == false){ 
                    $alert_msg = "B";
                }
                if($wl_tahun_now == 0 && $wl_thn_minus_1 == 0 && $thn_lapor <= $curr_tahun-1 && $history){
                    $alert_msg = "A";
                }
                
            } 
            
            switch($alert_msg){
                case "A":
                    $data_kill["pelaporan_tanggal"] = '1 Januari '.($curr_tahun-1);;
                    $data_kill["pelaporan_today"] = $pelaporan_tanggal_today;
                    break;
                case "B": 
                    $data_kill["pelaporan_tanggal"] = '1 Januari '.($curr_tahun-1);
                    $data_kill["pelaporan_today"] = $pelaporan_tanggal_today;
                    array_push($data_kill, $data_kill['is_thn_berjalan'] = '1');
                    break;
                case "C": 
                    $data_kill["pelaporan_tanggal"] = '1 Januari '.($curr_tahun-1);
                    $data_kill["pelaporan_today"] ='31 Desember '.($curr_tahun-1);
                    array_push($data_kill, $data_kill['is_thn_berjalan'] = '1');
                    break;
                case "D":
                    $data_kill["pelaporan_tanggal"] = '1 Januari '.$max_tahun_wl;
                    $data_kill["pelaporan_today"] = $pelaporan_tanggal_today;
                    array_push($data_kill, $data_kill['is_thn_berjalan'] = '1');
                    break;
                case "E": 
                    $data_kill["pelaporan_tanggal"] = '1 Januari '.$tahun_now;
                    $data_kill["pelaporan_today"] = '31 Desember '.$tahun_now;
                    break;
                default:
                    break;
            }
        }


        if($kill){
            header('Content-type: application/json');
            echo json_encode($data_kill);
            exit;
        }

        $c_dt_harta = $this->__count_data_harta($ID_LHKPN);

        $i = 1;
        if ($INDEX) {
            $i = $INDEX + 1;
        }
        $ctr = 0;

        while ($i <= 13) {

            if ($i == 11) { // MODUL PENERIMAAN
                $this->db->select('SUM(PN+PASANGAN) AS JML', false);
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $pnr = $this->db->get('t_lhkpn_penerimaan_kas2')->row();
                if ((int) $pnr->JML > 0) {
                    $check = TRUE;
                } else {
                    $check = FALSE;
                }
            } else if ($i == 1) {

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $check_data = $this->db->get($data[$i]['table'])->row();

                $id_user = $this->session->userdata('ID_USER');
                $email_pribadi = $check_data->EMAIL_PRIBADI;

                if ($email_pribadi != NULL) {
                    $this->db->where('IS_ACTIVE', '1');
                    $this->db->where('ID_USER !=', $id_user);
                    $this->db->where('EMAIL', $email_pribadi);
                    $exist_email = $this->db->get('t_user')->row();
                }

                if ($check_data->NIK == '' || $check_data->NAMA_LENGKAP == '' || $check_data->NPWP == '' || $check_data->JENIS_KELAMIN == '' || $check_data->TEMPAT_LAHIR == '' || $check_data->TANGGAL_LAHIR == '' ||
                    $check_data->STATUS_PERKAWINAN == '' || $check_data->AGAMA == '' || $check_data->HP == '' || $check_data->EMAIL_PRIBADI == '' || $check_data->NEGARA == '' || $check_data->PROVINSI == '' || $check_data->KABKOT == '' || $check_data->KECAMATAN == '' || $check_data->KELURAHAN == '' || $check_data->ALAMAT_RUMAH == '') {
                    $data[$i]['notif'] = 'Data Pribadi belum diisi, harap lengkapi data pribadi terlebih dahulu.';
                    $check = FALSE;
                } else if ($exist_email) {
                    $data[$i]['notif'] = 'Alamat email sudah didaftarkan oleh akun lain, silahkan diganti dengan alamat email lainnya.';
                    $check = FALSE;
                } else {
                    $check = TRUE;
                }

            } else if ($i == 2) {

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $check_data = $this->db->get($data[$i]['table'])->result();
                $jum_jab = count($check_data);

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->where('IS_PRIMARY', '1');
                $check_primary = $this->db->get($data[$i]['table'])->result();

                if (!$check_data) {
                    $data[$i]['notif'] = 'Data Jabatan belum diisi, apakah Anda yakin ? Bila Ya klik tombol Lanjutkan.';
                    $check = FALSE;
                } else if ($jum_jab > 0 && !$check_primary && $check_data) {
                    $data[$i]['notif'] = 'Data Jabatan utama belum dipilih, Isi data terlebih dahulu!';
                    $check = FALSE;
                } else if (!$check_data && !$check_primary) {
                    $data[$i]['notif'] = 'Data Jabatan belum diisi atau Anda belum memilih data Jabatan utama, apakah Anda yakin ? Bila Ya klik tombol Lanjutkan.';
                    $check = FALSE;
                } else {
                    $check = TRUE;
                }
            } else if ($i == 3) {

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->where('IS_ACTIVE', 1);
                $check_data = $this->db->get($data[$i]['table'])->result();

                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $status=$this->db->get('t_lhkpn_data_pribadi')->row();

                if($status->STATUS_PERKAWINAN == 'Menikah') {
                    $jum_kel = count($check_data);
                    if ($jum_kel == 0) {
                        $check = FALSE;
                    } else {
                        foreach ($check_data as $check_kel) {
                            if ($check_kel->NIK == NULL) {
                                $data[$i]['notif'] = 'Masih terdapat isian yang belum lengkap di Data Keluarga. Silahkan melengkapi data dengan klik tombol <a class="btn btn-sm" style="background-color: green;color: white;border-radius: 4px" disabled><i class="fa fa-pencil"></i></a> pada Data Keluarga.';
                                $check = FALSE;
                                break;
                            } else {
                                $check = TRUE;
                            }
                        }
                    }
                } else {
                    foreach ($check_data as $check_kel) {
                        if ($check_kel->NIK == NULL) {
                            $data[$i]['notif'] = 'Masih terdapat isian yang belum lengkap di Data Keluarga. Silahkan melengkapi data dengan klik tombol <a class="btn btn-sm" style="background-color: green;color: white;border-radius: 4px" disabled><i class="fa fa-pencil"></i></a> pada Data Keluarga.';
                            $check = FALSE;
                            break;
                        } else {
                            $check = TRUE;
                        }
                    }
                }

            } else if ($i == 12) { // MODUL PENGELUARAN
                $this->db->select('SUM(JML) AS JML_PENGELUARAN', false);
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $png = $this->db->get('t_lhkpn_pengeluaran_kas2')->row();
                if ((int) $png->JML_PENGELUARAN > 0) {
                    $check = TRUE;
                } else {
                    $check = FALSE;
                }

            } else if ($i >= 4 && $i <= 9) { // MODUL HARTA

                /**
                 * @deprecated since 07-maret-2017
                 * @author lahirwisada@gmail.com
                 */
                /*
                  $this->db->where('ID_LHKPN', $ID_LHKPN);
                  $this->db->where('IS_CHECKED', '1');
                  $this->db->or_where('ID_HARTA IS NULL');
                  $check_pilih = $this->db->get($data[$i]['table'])->result();
                 *
                 */

                /**
                 * cek data lama ketika belum dilakukan aksi apapun
                 */
                $check_pilih = $this->mlhkpn->check_data_review_harta($data[$i]['table'], $ID_LHKPN);

//                if ($i == 5) {
//                    echo $this->db->last_query();
//                }


                $check_data = $this->mlhkpn->check_data_review_harta($data[$i]['table'], $ID_LHKPN, TRUE);

//                if ($i == 5) {
//                    echo $this->db->last_query();
//                }
//                $this->db->where('ID_LHKPN', $ID_LHKPN);
//                $check_data = $this->db->get($data[$i]['table'])->result();

                if ($c_dt_harta == '0') {
                    $data[$i]['notif'] = 'Data harta belum diisi, silahkan diisi terlebih dahulu sebelum mengirimkan LHKPN.';
                    $check = FALSE;
                } else {

                    $title = $this->mlhkpn->info_harta($i);

                    if (!$check_data) { // Data Kosong
                        
                        if($title == 'Data Harta Kas / Setara Kas') {
                            $data[$i]['notif'] = $title . ' belum diisi, silahkan diisi terlebih dahulu sebelum melanjutkan.';
                        }else{
                            $data[$i]['notif'] = $title . ' belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.';
                        }
                        //                    $data[$i]['notif'] = $title . ' belum diisi.';
                        $check = FALSE;
                        //                } else if ($check_data && !$check_pilih) {
                    } else if ($check_data && $check_pilih > 0) {
                        $data[$i]['notif'] = $data[$i]['notif_status'];
                        $check = FALSE;
                        //                } else if (!$check_data && !$check_pilih) {
                    } else if (!$check_data && $check_pilih > 0) {
                        $data[$i]['notif'] = $data[$i]['notif_status'];
                        $check = FALSE;
                    } else {

                        if($title == 'Data Harta Kas / Setara Kas') { 

                            /// semua harta ber-status  "Lepas" ///
                            $check_harta_pelepasan = $this->mlhkpn->check_data_harta_pelepasan($data[$i]['table'], $ID_LHKPN);

                            if($check_harta_pelepasan == $check_data){
                                $data[$i]['notif'] = $title . ' belum diisi atau berstatus "Lepas", silahkan diisi terlebih dahulu sebelum melanjutkan.';
                                $check = FALSE;

                            }else if($check_data && $check_pilih == 0 && $i == '8'){ /// jika harta berharga kosong ///
                                $ctr++;
                                $data[$i]['ctr'] = $ctr;
                                $check = TRUE;
                            }

                        }else{
                            $ctr++;
                            $data[$i]['ctr'] = $ctr;
                            $check = TRUE;
                        }
                
                        
                    }

                    
                }
            } else { // MODUL LAINNYA
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $check = $this->db->get($data[$i]['table'])->result();
            }

            if (!$check || ($ctr == 0 && ($i >= 4 && $i <= 9))) {

//                header('Content-type: application/json');
//                echo json_encode($data[$i]);
                break;
            }
            $i++;
        }
        if (!$check || $ctr == 0) {
            header('Content-type: application/json');
            echo json_encode($data[$i]);
            exit;
        }
    }

    // function harta() {
    //     $data = array();
    //     $data[1] = 't_lhkpn_harta_tidak_bergerak';
    //     $data[2] = 't_lhkpn_harta_bergerak';
    //     $data[3] = 't_lhkpn_harta_bergerak_lain';
    //     $data[4] = 't_lhkpn_harta_surat_berharga';
    //     $data[5] = 't_lhkpn_harta_kas';
    //     $data[6] = 't_lhkpn_harta_lainnya';
    //     $data[7] = 't_lhkpn_hutang';
    //     return $data;
    //     exit;
    // }

    function jumlah($ID_LHKPN) {
        $data = $this->harta();
        $result = array();
        foreach ($data as $z) {
            if ($z == 't_lhkpn_harta_kas') {
                $key = 'NILAI_EQUIVALEN';
            } else if ($z == 't_lhkpn_hutang') {
                $key = 'SALDO_HUTANG';
            } else {
                $key = 'NILAI_PELAPORAN';
            }
            $this->db->select_sum($key);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            if ($z != 't_lhkpn_hutang') {
                $this->db->where('IS_PELEPASAN <> \'1\' ');
            }
            $this->db->where('IS_ACTIVE', '1');
            $hasil = $this->db->get($z)->result();
            if ($hasil[0]->$key) {
                $result[] = $hasil[0]->$key;
            } else {
                $result[] = 0;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    /**
     * @see MY_CONTROLLER
     * @param type $ID_LHKPN
     * @param type $also_redirect
     */
    function ____kirim_lhkpn($ID_LHKPN, $also_redirect = TRUE) {
        $usr_name = $this->session->userdata('USERNAME');
        $datapn = @$this->mglobal->get_data_all('T_USER', [
                    ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
                    ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
                    ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
                    ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
                    ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
                    ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
                        ], ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '1'], 'STATUS, ID_USER, T_PN.NIK, T_USER.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_NAMA, T_LHKPN.JENIS_LAPORAN, T_LHKPN.TGL_LAPOR, T_PN.EMAIL')[0];

//        echo $this->db->last_query();exit;

        $j_lap = ($datapn->JENIS_LAPORAN == 1) ? 'Khusus, Calon Penyelenggara' : ($datapn->JENIS_LAPORAN == 2) ? 'Khusus, Awal Menjabat' : ($datapn->JENIS_LAPORAN == 3) ? 'Khusus, Akhir Menjabat' : 'Periodik';

        $tahun = get_format_tanggal_lapor_lhkpn($datapn->JENIS_LAPORAN, $datapn->TGL_LAPOR);
        $pesan_valid = '
           <html>
	<head>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                padding: 1px;
				border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #4CAF50;
                color: white;
            }

        </style>
    </head>
    <body>
        <div>
            <div class="row">
                <div class="col-lg-12">
                    <div>Yth. Sdr ' . @$datapn->NAMA . '</div>
                </div>
            </div>

        </div>
        <div><br>
        </div>
        <div>' . @$datapn->INST_NAMA . '</div>
        <div><br>

        </div>
        <div>Di Tempat </div>
        <div>
            <br>
            <div>Bersama ini kami informasikan bahwa LHKPN yang Bapak/Ibu kirimkan telah kami terima dengan ringkasan sebagai berikut : </div><br/><br/>
            <div>
				<table>
				  <tr>
					<td align="center" valign="right"><img src="https://elhkpn.kpk.go.id/images/KPK_Logo.svg.png" align="middle"></td>
					<td colspan="2" ><p><strong><H4><center><br>KOMISI PEMBERANTASAN KORUPSI<br>REPUBLIK INDONESIA</center></H4></strong><br><center>Jl. Kuningan Persada Kav. 4, Setiabudi<br>Jakarta 12950</center></p></td>
					</tr>
				  <tr>
					  <td colspan="3"><H4><p><strong><center>LEMBAR PENYERAHAN FORMULIR<BR>LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</center></strong></p></H4><br></td>
					</tr>
					<tr>
						<td><strong>Atas Nama</strong></td>
						<td width="5%" align="center">:</td>
						<td width="72%">' . @$datapn->NAMA . '</td>
					</tr>
					<tr>
						<td><strong>Jabatan</strong></td>
						<td align="center">:</td>
						<td>' . @$datapn->NAMA_JABATAN . '</td>
					</tr>
					<tr>
						<td><strong>Bidang</strong></td>
						<td align="center">:</td>
						<td>' . @$datapn->BDG_NAMA . '</td>
					</tr>
					<tr>
						<td><strong>Lembaga</strong></td>
						<td align="center">:</td>
						<td>' . @$datapn->INST_NAMA . '</td>
					</tr>
					<tr>
						<td><strong>Tanggal / Tahun Pelaporan</strong></td>
						<td align="center">:</td>
						<td>' . $tahun . '</td>
					</tr>
				</table>
            </div>
        </div><br>
        <div> Email konfirmasi Lembar Penyerahan LHKPN ini  bukan merupakan Tanda Terima LHKPN. Tanda terima LHKPN akan kami kirimkan setelah Dokumen Kelengkapan telah kami terima dan LHKPN telah diverifikasi oleh Direktorat Pendaftaran dan Pemeriksaan LHKPN. Untuk informasi lebih lanjut, silakan menghubungi  kami kembali melalui email elhkpn@kpk.go.id atau call center 198.</div>
        <br>
        <div>  Atas kerjasama yang diberikan, Kami ucapkan terima kasih</div>
        <br/><br/>
        <div>  Direktorat Pendaftaran dan Pemeriksaan LHKPN</div><br/>
        <div>  -------------------------------------------------</div>
        <br/>
        <div> Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div><br/><br/><br/>
        <div>  Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</div>

    </div>


        ';

        if ($ID_LHKPN) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $curr_date = date('Y-m-d');

            if ($datapn->STATUS == '0') {
                $update = $this->db->update('t_lhkpn', array('STATUS' => '1', 'tgl_kirim_final' => $curr_date));
            } else {
                $update = $this->db->update('t_lhkpn', array('STATUS' => '1'));
                $history = [
                    'ID_LHKPN' => $ID_LHKPN,
                    'ID_STATUS' => 8,
                    'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                    'USERNAME_PENERIMA' => '',
                    'DATE_INSERT' => date('Y-m-d H:i:s'),
                    'CREATED_IP' => $this->input->ip_address()
                ];

                $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
            }

            if ($update) {
                $this->db->where('ID_LHKPN', $ID_LHKPN);
                $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
                $pn = $this->db->get('t_lhkpn')->row();
                $tahun = substr($pn->TGL_LAPOR, 0, 4);

                $data = array(
                    'ID_PENGIRIM' => 1,
                    'ID_PENERIMA' => $this->session->userdata('ID_USER'),
                    'SUBJEK' => 'Lembar Penyerahan e-LHKPN',
                    'PESAN' => $pesan_valid,
                    'FILE' => null,
                    'TANGGAL_KIRIM' => date('Y-m-d H:i:s'),
                    'ID_LHKPN' => $ID_LHKPN,
                    'IS_ACTIVE' => '1'
                );
                $send = $this->db->insert('T_PESAN_KELUAR', $data);
                if ($send) {
                    $data2 = array(
                        'ID_PENGIRIM' => 1,
                        'ID_PENERIMA' => $this->session->userdata('ID_USER'),
                        'SUBJEK' => 'Lembar Penyerahan e-LHKPN',
                        'PESAN' => $pesan_valid,
                        'FILE' => null,
                        'TANGGAL_MASUK' => date('Y-m-d H:i:s'),
                        'ID_LHKPN' => $ID_LHKPN,
                        'IS_ACTIVE' => '1'
                    );
                    $send2 = $this->db->insert('T_PESAN_MASUK', $data2);

                    /*
                     * add by eko
                     * insert status submit lhkpn ke history status lhkpn
                     */
                    $history = [
                        'ID_LHKPN' => $ID_LHKPN,
                        'ID_STATUS' => 2,
                        'USERNAME_PENGIRIM' => $this->session->userdata('USR'),
                        'USERNAME_PENERIMA' => '',
                        'DATE_INSERT' => date('Y-m-d H:i:s'),
                        'CREATED_IP' => $this->input->ip_address()
                    ];

                    $this->mglobal->insert('T_LHKPN_STATUS_HISTORY', $history);
                }
                $pengirim = "E-Filling LHKPN";
                $idUser = 1;
                $penerima = $datapn->EMAIL;

                /**
                 * Eksekusi disini
                 */
                $sess_kirim_lhkpn = $this->session->userdata('sess_kirim_lhkpn');

                if ($sess_kirim_lhkpn &&
                        $sess_kirim_lhkpn->id_lhkpn == $ID_LHKPN &&
                        is_array($sess_kirim_lhkpn->data)) {

                    foreach ($sess_kirim_lhkpn->data as $sess_data) {
                        $this->__send_to_mailbox($sess_data->id_user, $sess_data->pesan, $sess_data->subject, $sess_data->word_location, $sess_data->is_trusted);
                    }
                }
                $this->session->unset_userdata('sess_kirim_lhkpn');
                unset($sess_kirim_lhkpn);

                ng::mail_send($penerima, 'E-Filling LHKPN', $pesan_valid);

                $this->session->set_flashdata('success_message', 'success_message');
//                $this->session->set_flashdata('message', 'Data LHKPN Tahun <b>' . $tahun . '</b> atas nama <b>' . strtoupper($pn->NAMA) . '</b> berhasil di kirim.');
                $this->session->set_flashdata('message', 'Data LHKPN atas nama <b>' . strtoupper($pn->NAMA) . '</b> berhasil di kirim.');
                if ($also_redirect) {
                    redirect('portal/filing');
                }
            }
        } else {
            if ($also_redirect) {
                redirect('portal/filing');
            }
        }
    }

    function check_token() {
        $this->db->where('ID_LHKPN', $this->input->post('ID_LHKPN'));
        $this->db->where('TOKEN_PENGIRIMAN', strtolower($this->input->post('nomor_token')));
        $check = $this->db->get('t_lhkpn')->row();
        if ($check) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function create_token($ID_LHKPN) {
        /* $datapn = @$this->mglobal->get_data_all('T_USER', [
          ['table' => 'T_PN', 'on' => 'T_PN.NIK = T_USER.USERNAME'],
          ['table' => 'T_LHKPN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN'],
          ['table' => 'T_LHKPN_JABATAN', 'on' => 'T_LHKPN_JABATAN.ID_LHKPN = T_LHKPN.ID_LHKPN'],
          ['table' => 'M_JABATAN', 'on' => 'M_JABATAN.ID_JABATAN = T_LHKPN_JABATAN.ID_JABATAN'],
          ['table' => 'M_INST_SATKER', 'on' => 'M_INST_SATKER.INST_SATKERKD = T_LHKPN_JABATAN.LEMBAGA'],
          ['table' => 'M_BIDANG', 'on' => 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID']
          ], ['T_USER.IS_ACTIVE' => '1', 'T_LHKPN.ID_LHKPN' => $ID_LHKPN, 'IS_PRIMARY' => '1'], 'ID_USER, T_PN.NIK, T_USER.NAMA, M_JABATAN.NAMA_JABATAN, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_NAMA, T_LHKPN.JENIS_LAPORAN, T_LHKPN.TGL_LAPOR, T_PN.EMAIL')[0]; */
        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN,TRUE,TRUE);
        $random = createRandomPassword(5);
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $result = $this->db->update('t_lhkpn', array('TOKEN_PENGIRIMAN' => strtolower($random)));
        $server_code = base64_encode(strtolower($random));

//        $pesan_valid = '
//           <html>
//	<head>
//        <style>
//            table {
//                border-collapse: collapse;
//                width: 100%;
//            }
//            th, td {
//                padding: 1px;
//				border-bottom: 1px solid #ddd;
//            }
//            th {
//                background-color: #4CAF50;
//                color: white;
//            }
//
//        </style>
//    </head>
//    <body>
//        <div>
//            <div class="row">
//                <div class="col-lg-12">
//                    <div>Yth. Sdr ' . @$datapn->NAMA . '</div>
//                </div>
//            </div>
//
//        </div>
//        <div><br>
//        </div>
//        <div>' . @$datapn->INST_NAMA . '</div>
//        <div><br>
//
//        </div>
//        <div>Di Tempat </div>
//        <div>
//            <br>
//            <div>Bersama ini kami informasikan kode token pengiriman LHKPN anda : ' . strtolower($random) . '</div><br/><br/>
//            <div>Server Code : ' . strtoupper($server_code) . '</div><br/><br/>
//        </div>
//        <br>
//        <div>  Atas kerjasama yang diberikan, Kami ucapkan terima kasih</div>
//        <br/><br/>
//        <div>  Direktorat Pendaftaran dan Pemeriksaan LHKPN</div><br/>
//        <div>  -------------------------------------------------</div>
//        <br/>
//        <div> Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div><br/><br/><br/>
//        <div>  Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</div>
//
//    </div>
//
//
//        ';

        $pesan_valid = '<!DOCTYPE html>
                    <html>
                    <head>
                    <title></title>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <style type="text/css">
                        /* FONTS */
                        @media screen {
                            @font-face {
                              font-family: \'Lato\';
                              font-style: normal;
                              font-weight: 400;
                              src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
                            }

                            @font-face {
                              font-family: \'Lato\';
                              font-style: normal;
                              font-weight: 700;
                              src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
                            }

                            @font-face {
                              font-family: \'Lato\';
                              font-style: italic;
                              font-weight: 400;
                              src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
                            }

                            @font-face {
                              font-family: \'Lato\';
                              font-style: italic;
                              font-weight: 700;
                              src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
                            }
                        }

                        /* CLIENT-SPECIFIC STYLES */
                        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                        img { -ms-interpolation-mode: bicubic; }

                        /* RESET STYLES */
                        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
                        table { border-collapse: collapse !important; }
                        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

                        /* iOS BLUE LINKS */
                        a[x-apple-data-detectors] {
                            color: inherit !important;
                            text-decoration: none !important;
                            font-size: inherit !important;
                            font-family: inherit !important;
                            font-weight: inherit !important;
                            line-height: inherit !important;
                        }

                        /* MOBILE STYLES */
                        @media screen and (max-width:600px){
                            h1 {
                                font-size: 32px !important;
                                line-height: 32px !important;
                            }
                        }

                        /* ANDROID CENTER FIX */
                        div[style*="margin: 16px 0;"] { margin: 0 !important; }
                    </style>
                    </head>
                    <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td bgcolor="#e48683" align="center">
                                <br>
                                <br>
                                <br>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                    <tr>
                                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                                          <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                      <p style="margin: 0;">Yth. Sdr. <strong>'.@$datapn->NAMA.'</strong><br><strong>'.@$datapn->INST_NAMA.'</strong><br>Di Tempat<br><br></p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                      <p style="margin: 0;">Bersama ini kami informasikan kode token pengiriman LHKPN anda :</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="center">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" >
                                    <tr>
                                      <td bgcolor="#FFECD1" align="left" style="padding: 20px 0 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                        <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0; text-align: center;">Kode Token e-LHKPN : </h2>
                                        <h2 style="font-size: 18px; font-weight: 400; color: #111111; margin: 0; text-align: center;">'.strtolower($random).'</h2><br>
                                        <h2 style="font-size: 14px; font-weight: 400; color: #111111; margin: 0;">Server Code : '.strtoupper($server_code).'</h2>
                                      </td>
                                    </tr>
                                </table>
                            </td>
                                  </tr>
                                    <tr>
                                      <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                        <p style="margin: 0;">Pastikan kode token yang anda input sesuai dengan server code.</p>
                                      </td>
                                    </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                      <p style="margin: 0;">Atas kerjasama yang diberikan, Kami ucapkan terima kasih.<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                                      <hr style="border: 0; border-bottom: 1px dashed #000;">
                                      <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                                    </td>
                                  </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                  <tr>
                                    <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                                      <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                                    </td>
                                  </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    </body>
                    </html>';

//                $curl_data= 'SEND={"tujuan":"085640763677","isiPesan":"Kode Token Pengiriman LHKPN adalah xxxx","idModem":6}';
//        $curl_data = 'SEND={"tujuan":"' . $datapn->HANDPHONE . '","isiPesan":"Kode Token Pengiriman LHKPN adalah ' . $random . '", "idModem":5}';
//        CallURLPage('http://192.168.2.39:3333/sendSMS?SEND={"idOutbox":20,"tujuan":"' . $datapn->NO_HP . '","isiPesan":"Kode Token Pengiriman LHKPN adalah '.$random.'", "idModem":"5", "jmlPesan":1}');
//        if (self::ENABLE_SEND_SMS) {
//        CallURLPage('http://10.101.131.229/playsms/index.php?app=ws&u=lhkpn&h=6924510c31f6708def860303eb420359&op=pv&to='.$datapn->HANDPHONE.'&msg=Kode+Token+Pengiriman+LHKPN+adalah+'.$random.'%0a+(Server+Code+:+'.strtoupper($server_code).')');
//        }

        if ($result == '1') {
//            echo "1";
            $data = array(
                'server_code' => strtoupper($server_code),
                'nohp' => $datapn->HP,
                'email' => $datapn->EMAIL_PRIBADI,
            );
            // echo strtoupper($server_code);
            echo json_encode($data);
//            ng::mail_send($datapn->EMAIL, 'Token E-Filling LHKPN', $pesan_valid);
//             CallURLPage('http://10.101.131.229/playsms/index.php?app=ws&u=lhkpn&h=6924510c31f6708def860303eb420359&op=pv&to='.$datapn->HANDPHONE.'&msg=Kode+Token+e-LHKPN+adalah+'.$random.'+(Server+Code+:+'.strtoupper($server_code).')');
//              CallURLPage('http://api.multichannel.id:8088/sms/async/otp?uid=cecri2018&passwd=cec20181026&sender=e-LHKPN%20KPK&message=Kode+Token+e-LHKPN+adalah+'.strtolower($random).'+(Server+Code+:+'.strtoupper($server_code).')+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198&msisdn='.$datapn->HANDPHONE.'');
//              CallURLPage('http://api.elpiamessenger.com/api/v3/sendsms/plain?user=kpkotp_api&password=kpk2019&SMSText=Kode%20Token%20e-LHKPN%20adalah%20'.strtolower($random).'%20(Server%20Code%20:%20'.strtoupper($server_code).')%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->HANDPHONE.'');
            ng::logActivity("Permintaan Token Submit LHKPN, NIK = ".$datapn->NIK.", tanggal =  ".date('Y-m-d H:i:s').", ID_LHKPN = ".$ID_LHKPN.", TOKEN = ".strtolower($random));
        } else {
            echo "0";
        }
    }



    function send_token_to_email($ID_LHKPN) {
        $datapn = @$this->mglobal->get_detail_pn_lhkpn($ID_LHKPN,FALSE,TRUE);
        $result =  $this->mglobal->get_data_by_id('t_lhkpn', 'ID_LHKPN', $ID_LHKPN,false,true);
        $random = $result->TOKEN_PENGIRIMAN;

        $server_code = base64_encode($random);

        $pesan_valid = '<!DOCTYPE html>
                    <html>
                    <head>
                    <title></title>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <style type="text/css">
                        /* FONTS */
                        @media screen {
                            @font-face {
                              font-family: \'Lato\';
                              font-style: normal;
                              font-weight: 400;
                              src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');
                            }

                            @font-face {
                              font-family: \'Lato\';
                              font-style: normal;
                              font-weight: 700;
                              src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');
                            }

                            @font-face {
                              font-family: \'Lato\';
                              font-style: italic;
                              font-weight: 400;
                              src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');
                            }

                            @font-face {
                              font-family: \'Lato\';
                              font-style: italic;
                              font-weight: 700;
                              src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');
                            }
                        }

                        /* CLIENT-SPECIFIC STYLES */
                        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                        img { -ms-interpolation-mode: bicubic; }

                        /* RESET STYLES */
                        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
                        table { border-collapse: collapse !important; }
                        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

                        /* iOS BLUE LINKS */
                        a[x-apple-data-detectors] {
                            color: inherit !important;
                            text-decoration: none !important;
                            font-size: inherit !important;
                            font-family: inherit !important;
                            font-weight: inherit !important;
                            line-height: inherit !important;
                        }

                        /* MOBILE STYLES */
                        @media screen and (max-width:600px){
                            h1 {
                                font-size: 32px !important;
                                line-height: 32px !important;
                            }
                        }

                        /* ANDROID CENTER FIX */
                        div[style*="margin: 16px 0;"] { margin: 0 !important; }
                    </style>
                    </head>
                    <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td bgcolor="#e48683" align="center">
                                <br>
                                <br>
                                <br>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#e48683" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                    <tr>
                                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                                          <h1 style="font-size: 48px; font-weight: 400; margin: 0;"></h1>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                      <p style="margin: 0;">Yth. Sdr. <strong>'.@$datapn->NAMA.'</strong><br><strong>'.@$datapn->INST_NAMA.'</strong><br>Di Tempat<br><br></p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                      <p style="margin: 0;">Bersama ini kami informasikan kode token pengiriman LHKPN anda :</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="center">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" >
                                    <tr>
                                      <td bgcolor="#FFECD1" align="left" style="padding: 20px 0 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                        <h2 style="font-size: 16px; font-weight: 400; color: #111111; margin: 0; text-align: center;">Kode Token e-LHKPN : </h2>
                                        <h2 style="font-size: 18px; font-weight: 400; color: #111111; margin: 0; text-align: center;">'.strtolower($random).'</h2><br>
                                        <h2 style="font-size: 14px; font-weight: 400; color: #111111; margin: 0;">Server Code : '.strtoupper($server_code).'</h2>
                                      </td>
                                    </tr>
                                </table>
                            </td>
                                  </tr>
                                    <tr>
                                      <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                        <p style="margin: 0;">Pastikan kode token yang anda input sesuai dengan server code.</p>
                                      </td>
                                    </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;" >
                                      <p style="margin: 0;">Atas kerjasama yang diberikan, Kami ucapkan terima kasih.<br><br>Direktorat Pendaftaran dan Pemeriksaan LHKPN</p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 25px;" >
                                      <hr style="border: 0; border-bottom: 1px dashed #000;">
                                      <p style="margin: 0;"><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>.</i></p>
                                    </td>
                                  </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                                  <tr>
                                    <td bgcolor="#f4f4f4" align="left" style="padding: 30px 30px 30px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;" >
                                      <p style="margin: 0;">&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</p>
                                    </td>
                                  </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    </body>
                    </html>';

//                $curl_data= 'SEND={"tujuan":"085640763677","isiPesan":"Kode Token Pengiriman LHKPN adalah xxxx","idModem":6}';
//                  ng::logSmsActivity($datapn->USERNAME, $datapn->ID_ROLE, $datapn->HP, "Kode Token e-LHKPN adalah ".strtolower($random)." (Server Code : ".strtoupper($server_code).") Info : elhkpn@kpk.go.id atau 198", 'Permintaan Token Submit LHKPN');
//        $curl_data = 'SEND={"tujuan":"' . $datapn->HANDPHONE . '","isiPesan":"Kode Token Pengiriman LHKPN adalah ' . $random . '", "idModem":5}';
//        CallURLPage('http://192.168.2.39:3333/sendSMS?SEND={"idOutbox":20,"tujuan":"' . $datapn->NO_HP . '","isiPesan":"Kode Token Pengiriman LHKPN adalah '.$random.'", "idModem":"5", "jmlPesan":1}');
//        if (self::ENABLE_SEND_SMS) {
//        CallURLPage('http://10.101.131.229/playsms/index.php?app=ws&u=lhkpn&h=6924510c31f6708def860303eb420359&op=pv&to='.$datapn->HANDPHONE.'&msg=Kode+Token+Pengiriman+LHKPN+adalah+'.$random.'%0a+(Server+Code+:+'.strtoupper($server_code).')');
//        }

        if ($result) {
            echo strtoupper($server_code);
            CallURLPage('http://appelpiamessenger.com/api/v3/sendsms/plain?user=kpkotp_api&password=client2021&SMSText=Kode%20Token%20e-LHKPN%20adalah%20'.strtolower($random).'%20(Server%20Code%20:%20'.strtoupper($server_code).')%20%0aInfo%20:%20elhkpn@kpk.go.id/02125578396%20atau%20198&GSM='.$datapn->HANDPHONE.'');
	    ng::logSmsActivity($datapn->USERNAME, $datapn->ID_ROLE, $datapn->HANDPHONE, "Kode Token e-LHKPN adalah ".strtolower($random)." (Server Code : ".strtoupper($server_code).") Info : elhkpn@kpk.go.id atau 198", 'Permintaan Token Submit LHKPN');
//            CallURLPage('https://smsgw.sprintasia.net/api/msg.php?u=3LHKPNKPK&p=ce5ukieS&d='.$datapn->HANDPHONE.'&m=Kode+Token+e-LHKPN+adalah+'.strtolower($random).'+(Server+Code+:+'.strtoupper($server_code).')+%0aInfo+:+elhkpn@kpk.go.id/02125578396+atau+198');
            ng::mail_send($datapn->EMAIL, 'Token E-Filling LHKPN', $pesan_valid);
//            CallURLPage('http://otp.citrahost.com/citra-sms.api.php?action=send&outhkey=a577139a3a00060fe8602edcac7a66b9&secret=35224ce1cf7364c07ac3ea75850ab7c3&pesan=Kode%20Token%20e-LHKPN%20adalah%20'.strtolower($random).'%20(Server%20Code%20:%20'.strtoupper($server_code).')%0a%20Info%20:%20elhkpn@kpk.go.id%20atau%20198&to='.$datapn->HANDPHONE.'');
        } else {
            echo "0";
        }
    }

    /**
     * monggo pake yg __send_to_mailbox
     * @param type $pengirim
     * @param type $idUser
     * @param type $penerima
     * @param type $subject
     * @param type $pesan
     * @param type $send_to_mail
     */
//    function sentMail($pengirim, $idUser, $penerima, $subject, $pesan, $send_to_mail = TRUE) {
//        //$this->load->model('Msuratkeluar');
//        //return $this->msuratkeluar->send_message($pengirim, $idUser, $penerima, $subject, $pesan, $send_to_mail);
//    }

    function surat_kuasa_pdf2($ID_LHKPN, $INDEX, $OPTION = NULL, $PRINT = FALSE) {


        if($this->session->userdata('ID_ROLE') == 5){

            ////////////////SISTEM KEAMANAN////////////////
                $state_id_pn = $this->session->userdata('ID_PN');
                $check_protect = protectLhkpn($state_id_pn,$ID_LHKPN);
                if($check_protect){
                    $method = __METHOD__;
                    $this->load->model('mglobal');
                    $this->mglobal->recordLogAttacker($check_protect,$method);
                    echo 'Anda tidak memiliki akses pada data ini !!!';
                    return;
                }
            ////////////////SISTEM KEAMANAN////////////////

        }

        date_default_timezone_set('Asia/Jakarta');

        if (!empty($OPTION)) {
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('STATUS_SURAT_UMUMKAN' => '' . $OPTION));
        }

        $this->db->select('t_lhkpn.*,CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN, j.NAMA_LEMBAGA, minst.INST_NAMA', FALSE);
//        $this->db->join('t_pn as p', 'p.ID_PN = t_lhkpn.ID_PN', 'LEFT');
        $this->db->join('t_pn_jabatan as j', 'j.ID_PN = t_lhkpn.ID_PN AND j.IS_CURRENT = 1', 'LEFT');
        $this->db->join('t_lhkpn_jabatan as tj', 'tj.ID_LHKPN = t_lhkpn.ID_LHKPN AND tj.IS_PRIMARY = 1', 'LEFT');
        $this->db->join('m_jabatan as mj', 'mj.ID_JABATAN = tj.ID_JABATAN', 'LEFT');
        $this->db->join('m_inst_satker as minst', 'minst.INST_SATKERKD = mj.INST_SATKERKD', 'LEFT');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $LHKPN = $this->db->get('t_lhkpn')->row();

        if ($LHKPN) {
            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/Lampiran4Template.docx";

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "callable_model_function" => "insert_cqrcode_with_filename",
                "temp_dir"=>APPPATH . "../images/qrcode/"
            ]);

            $this->load->library('ey_barcode');



            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            $this->db->select('
			t_lhkpn.ID_PN,
			t_lhkpn.TGL_LAPOR,
			NULL AS ID,
			NULL AS ID_OLD,
			t_lhkpn_data_pribadi.NAMA_LENGKAP AS NAMA,
			t_lhkpn_data_pribadi.NIK,
			t_lhkpn_data_pribadi.TEMPAT_LAHIR,
			t_lhkpn_data_pribadi.TANGGAL_LAHIR,
			t_lhkpn_data_pribadi.ALAMAT_RUMAH,
			t_lhkpn_data_pribadi.KELURAHAN,
			t_lhkpn_data_pribadi.KECAMATAN,
			t_lhkpn_data_pribadi.KABKOT,
			t_lhkpn_data_pribadi.PROVINSI,
			NULL AS HUBUNGAN,
			NULL STATUS_KELUARGA,
			CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_data_pribadi.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
		', FALSE);
            $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
            $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
            $PN = $this->db->get('t_lhkpn_data_pribadi')->row();

            $this->db->select('
    		t_lhkpn.ID_PN,
    		t_lhkpn.TGL_LAPOR,
    		t_lhkpn_keluarga.ID_KELUARGA AS ID,
    		t_lhkpn_keluarga.ID_KELUARGA_LAMA AS ID_OLD,
    		REPLACE(t_lhkpn_keluarga.NAMA, ",", " ") AS NAMA,
			t_lhkpn_keluarga.NIK,
    		t_lhkpn_keluarga.TEMPAT_LAHIR,
    		t_lhkpn_keluarga.TANGGAL_LAHIR,
    		t_lhkpn_keluarga.ALAMAT_RUMAH,
    		t_lhkpn_keluarga.HUBUNGAN,
    		t_lhkpn_keluarga.STATUS_CETAK_SURAT_KUASA AS STATUS_KELUARGA,
    		CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
    	', FALSE);

            $this->db->group_by('t_lhkpn_keluarga.ID_KELUARGA');
            $this->db->order_by('t_lhkpn_keluarga.TANGGAL_LAHIR', 'ASC');
            $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
            $this->db->where('(CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1) >=17');
            $this->db->where_in('t_lhkpn_keluarga.HUBUNGAN', array('1', '2', '3'));

//        if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
            if ($LHKPN->IS_COPY == '0' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
                $this->db->where('t_lhkpn_keluarga.ID_KELUARGA_LAMA IS NULL');
            }

            $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN');
            $KELUARGA = $this->db->get('t_lhkpn_keluarga')->result();

            /**
             * just check if template loaded successful
             * but will be override on ..
             */
            // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            // if ($load_template_success) {
                if ($PN->ALAMAT_RUMAH) {
                    $alamat_pn = $pn->ALAMAT_RUMAH;
                } else {
                    $alamat_pn = $pn->ALAMAT_NEGARA;
                }

                $temp = array();
                $temp[1] = array(
                    'ID' => NULL,
                    'tgl_kirim' => $LHKPN->tgl_kirim,
                    'NAMA' => $PN->NAMA,
                    'TTL' => $PN->TEMPAT_LAHIR . ' - ' . tgl_format($PN->TANGGAL_LAHIR),
                    'TEMPAT_LAHIR' => $PN->TEMPAT_LAHIR,
                    'TANGGAL_LAHIR' => tgl_format($PN->TANGGAL_LAHIR),
                    'NOMOR_KTP' => $PN->NIK,
                    'NIK' => $PN->NIK,
                    'ALAMAT' => $PN->ALAMAT_RUMAH . ', ' . $PN->KELURAHAN . ' , ' . $PN->KECAMATAN . ' ,  ' . $PN->KABKOT . ' , ' . $PN->PROVINSI,
                    'LAST' => '0',
                    'NEXT' => 'KUASA_KELUARGA2(2,"#ModalKuasaKeluarga")',
                    'UMUR' => $PN->UMUR,
                    'LIMA_TAHUN' => '0',
                    'STATUS_CETAK_SURAT_KUASA' => '1'
                );

                $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[1]), TRUE);
                $this->db->where('ID_LHKPN', $ID_LHKPN);
//                 $this->db->update('t_lhkpn', array('STATUS_CETAK_SURAT_KUASA' => $OPTION, 'SURAT_KUASA' => $SURAT_KUASA, 'CETAK_SURAT_KUASA_TIME' => date('Y-m-d H:i:s')));
                $this->db->update('t_lhkpn', array('SURAT_KUASA' => $SURAT_KUASA));

                $nama_pn = $PN->NAMA;
                $nik_pn = $PN->NIK;

                $i_data = 1;
                $i = 2;
//                $i = 1;
//                $pnku = array($PN);
//                $KELUARGA = array_merge($pnku, $KELUARGA);

                foreach ($KELUARGA as $row) {
                    if ($i_data == count($KELUARGA)) {
                        $last = '1';
                        $next = '';
                    } else {
                        $last = '0';
                        $next = 'KUASA_KELUARGA2(' . ($i + 1) . ',"#ModalKuasaKeluarga")';
                    }
                    if ((int) $row->TIME_LHKPN > 5) {
                        $LIMA_TAHUN = '1';
                    } else {
                        $LIMA_TAHUN = '0';
                    }
                    $temp[$i] = array(
                        'ID' => $row->ID,
                        'tgl_kirim' => $LHKPN->tgl_kirim,
                        'NAMA' => $row->NAMA,
                        'TEMPAT_LAHIR' => $row->TEMPAT_LAHIR,
                        'TANGGAL_LAHIR' => tgl_format($row->TANGGAL_LAHIR),
                        'TTL' => $row->TEMPAT_LAHIR . ' - ' . tgl_format($row->TANGGAL_LAHIR),
                        'NIK' => $row->NIK,
                        'ALAMAT' => $row->ALAMAT_RUMAH,
                        'LAST' => $last,
                        'NEXT' => $next,
                        'UMUR' => $row->UMUR,
                        'LIMA_TAHUN' => $LIMA_TAHUN,
                        'STATUS_CETAK_SURAT_KUASA' => $row->STATUS_KELUARGA,
                        'HUBUNGAN' => $row->HUBUNGAN
                    );
                    
                    $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $temp[$i]), TRUE);
                    $this->db->where('ID_KELUARGA', $row->ID);
                    $this->db->update('t_lhkpn_keluarga', array('SURAT_KUASA' => $SURAT_KUASA));
                    $i_data++;
                    $i++;
                }

                $result = $temp[$INDEX];

//                gwe_dump(array($result), TRUE);

                if ($INDEX == '1') {
                    if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) {
//                        $result = $temp[2];
                        $result = $temp[1];
                    }
                }

                $tgl_lahir_qr = $result['ID'] ? ($result['TEMPAT_LAHIR'] == NULL ? '-' : $result['TEMPAT_LAHIR']) : tgl_format($result['TANGGAL_LAHIR']) == NULL ? '-' : tgl_format($result['TANGGAL_LAHIR']);

                $code = $result['ID'] == NULL ? $ID_LHKPN : $result['ID'];

                $qr_content_data = json_encode((object) [
                            "data" => [
//                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $data->NIK],
                                (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $result['NAMA']],
                                (object) ["tipe" => '1', "judul" => "NIK", "isi" => $result['NIK']],
//                                (object) ["tipe" => '1', "judul" => "Tempat/Tgl. Lahir", "isi" => $result['TEMPAT_LAHIR'] . "/" . $tgl_lahir_qr],
                                (object) ["tipe" => '1', "judul" => "Tempat/Tgl. Lahir", "isi" => $result['TTL']],
                                (object) ["tipe" => '1', "judul" => "Alamat Lengkap", "isi" => $result['ALAMAT'] == NULL ? '-' : $result['ALAMAT']],
//                                (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($result['tgl_kirim']) == NULL ? '-' : tgl_format($result['tgl_kirim'])],
                                (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($LHKPN->tgl_kirim_final)],
                                (object) ["tipe" => '1', "judul" => "Nama PN", "isi" => $nama_pn],
                                (object) ["tipe" => '1', "judul" => "NIK PN", "isi" => $nik_pn],
                                (object) ["tipe" => '1', "judul" => "Instansi", "isi" => $LHKPN->INST_NAMA],
                            ],
                            "encrypt_data" => $code . "sk",
                            "id_lhkpn" => $ID_LHKPN,
                            "judul" => "Surat Kuasa E-LHKPN",
//                            "tgl_surat" => tgl_format($result['tgl_kirim']) == NULL ? NULL : $result['tgl_kirim'],
                            "tgl_surat" => date('Y-m-d'),
                ]);

//                if($INDEX != '1'){
//                    echo $qr_content_data;exit;
//                }
    
                $qr_file = "tes_qr2-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
                $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

                /*
                 * end penulisan qrcode
                 */

                /*
                 * load template
                 */

                $get_nik = $nik_pn;
                $get_nama = $nama_pn;
                if($result['HUBUNGAN']=='1'){
                    $get_hubungan =  "SK ISTRI";
                }elseif($result['HUBUNGAN']=='2'){
                    $get_hubungan =  "SK SUAMI";
                }elseif($result['HUBUNGAN']=='3'){
                    $get_hubungan =  "SK ANAK";
                }else{
                    $get_hubungan = "SK PN";
                }

                // $show_barcode = "'".$get_nik.chr(9).$get_nama.chr(9).$get_hubungan;
                // $bc_file = "tes_bc2-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".jpg";
                // $bc_image_location = $this->ey_barcode->generate($show_barcode, $bc_file);

               $qr_info = "'".$get_nik.chr(95).$get_nama.chr(95).$get_hubungan;
               $show_qr2 = ($get_hubungan == "SK PN") ? $qr_info : $qr_info.chr(95).$KELUARGA->NAMA;
               
               $qr2_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
               $qr2_image_location = $this->lws_qr->create($show_qr2, $qr2_file);

                // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array(
                //     "image1.png" => $qr_image_location,"image2.png" => $bc_image_location
                // ));


                $prefix_jenis = "PN";

                $nama_subject = $result['NAMA'] == NULL ? '-' : $result['NAMA'];

//                 $this->lwphpword->set_value("NAMA_LENGKAP", $nama_subject);
//                 $this->lwphpword->set_value("TEMPAT_LAHIR", $result['TEMPAT_LAHIR'] == NULL ? '-' : $result['TEMPAT_LAHIR']);
//                 $this->lwphpword->set_value("TANGGAL_LAHIR", $result['TANGGAL_LAHIR'] == NULL ? '-' : $result['TANGGAL_LAHIR']);
//                 $this->lwphpword->set_value("NIK", $result['NIK'] == NULL ? '-' : $result['NIK']);
//                 $this->lwphpword->set_value("ALAMAT_LENGKAP", $result['ALAMAT'] == NULL ? '-' : $result['ALAMAT']);


// //                    $this->lwphpword->set_value("TGL_KIRIM", tgl_format($result['tgl_kirim']) == NULL ? '-' : tgl_format($result['tgl_kirim']));
//                 $this->lwphpword->set_value("TGL_KIRIM", date('d-F-Y'));

                if ($result['ID']) {
                    $this->db->where('ID_KELUARGA', $result['ID']);
                    $this->db->update('t_lhkpn_keluarga', array(
                        'STATUS_CETAK_SURAT_KUASA' => '1',
                        'CETAK_SURAT_KUASA_TIME' => date('Y-m-d H:i:s')
                    ));

                    $prefix_jenis = "KELUARGA";
                }


                $uniqe_file = null;
                if($result['ID']){
                    $uniqe_file = $result['ID'];
                }else{
                    $uniqe_file = $ID_LHKPN;
                }

                
                ///////////////PDF//////////////
                $data = array(
                    "NAMA_LENGKAP" => $nama_subject,
                    "TEMPAT_LAHIR" => $result['TEMPAT_LAHIR'] == NULL ? '-' : $result['TEMPAT_LAHIR'],
                    "TANGGAL_LAHIR" => $result['TANGGAL_LAHIR'] == NULL ? '-' : $result['TANGGAL_LAHIR'],
                    "NIK" => $result['NIK'] == NULL ? '-' : $result['NIK'],
                    "ALAMAT_LENGKAP" => $result['ALAMAT'] == NULL ? '-' : $result['ALAMAT'],
                    "TGL_CETAK" => date('d-F-Y'),
                    // "BC_IMAGE_LOCATION"=>$bc_image_location,
                    "QR_IMAGE_LOCATION"=>$qr_image_location,
                    "QR2_IMAGE_LOCATION"=>$qr2_image_location
                );
                //    $this->load->library('dom_pdf');

                $this->load->library('pdfgenerator');
                $html = $this->load->view('pdf/surat_kuasa_pdf', $data, true);
                $filename = time() . '_surat_kuasa_'.$uniqe_file;
                $method = "store";
                $path_file = "file/wrd_gen/";
                $save_document_success = $this->pdfgenerator->generate($html, $filename, $method, 'A4', 'landscape',$path_file);
                //////////////PDF//////////////
//                $temp_dir = APPPATH."../images/qrcode/";
//                unlink($temp_dir.$qr_file);
//                $temp_dir_br = APPPATH."../uploads/barcode/";
//                unlink($temp_dir_br.$bc_file);

                // $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE);

                if ($save_document_success) {

                    // $arr_nama = explode(" ", $result['NAMA']);
                    // $fn_nama = implode("-", $arr_nama);
                    // unset($arr_nama);

                    // $output_filename = "Surat-Kuasa-" . $prefix_jenis . "-an." . str_replace(',', '', $fn_nama) . "-Tanggal-" . date('d-F-Y') . "." . $ID_LHKPN;

                    $output_filename = $filename.'.pdf';

                    $pesan = $this->load->view('filing/review_harta/pesan_surat_kuasa', [
                        "nama_pn" => $PN->NAMA,
                        "nama_instansi" => $LHKPN->NAMA_LEMBAGA,
                            ], TRUE);

                    $subject = "Lampiran Surat Kuasa a.n. " . $nama_subject;


                    $surat_kuasa = (object) $this->sess_template_data;
                    $surat_kuasa->id_user = $this->session->userdata('ID_USER');
                    $surat_kuasa->pesan = $pesan;
                    $surat_kuasa->subject = $subject;
                    // $surat_kuasa->word_location = "../../../file/wrd_gen/".$output_filename;
                    // $surat_kuasa->word_location = "../../../file/wrd_gen/" . $save_document_success->document_name . ".docx";

                    // $this->__send_session_kirim_lhkpn($ID_LHKPN, $surat_kuasa);
                    // unset($surat_kuasa);
                    if(!$PRINT) {
                        if($result['ID'] == NULL) {
                            $idkel = '1';
                        } else {
                            $idkel = $result['ID'];
                        }
                        $this->__send_to_mailbox($this->session->userdata('ID_USER'), $pesan, $subject, NULL, TRUE, 1, $ID_LHKPN, $idkel);
                    }
                    $save_document_success = $this->pdfgenerator->generate($html, $filename, 'stream', 'A4', 'landscape');
                    unlink($path_file.$filename.'.pdf');
                    $temp_dir = APPPATH."../images/qrcode/";
                    unlink($temp_dir.$qr_file);
                    unlink($temp_dir.$qr2_file);
                    // $temp_dir_br = APPPATH."../uploads/barcode/";
                    // unlink($temp_dir_br.$bc_file);
                    return;
                        // $this->lwphpword->download($save_document_success->document_path, $output_filename);
                }
            // }
        }
    }

    function cetak_surat_kuasa_individual($ID_KELUARGA, $ID_LHKPN, $OPTION = NULL) {
        date_default_timezone_set('Asia/Jakarta');


        if($this->session->userdata('ID_ROLE') == 5){
            ////////////////SISTEM KEAMANAN////////////////
            $state_id_pn = $this->session->userdata('ID_PN');
            $check_protect = protectFilling($state_id_pn,'t_lhkpn_keluarga',$ID_KELUARGA);
            if($check_protect){
                $method = __METHOD__;
                $this->load->model('mglobal');
                $this->mglobal->recordLogAttacker($check_protect,$method);
                echo 'Anda tidak memiliki akses pada data ini !!!';
                return;
            }
            ////////////////SISTEM KEAMANAN////////////////


            ////////////////SISTEM KEAMANAN////////////////
                $state_id_pn = $this->session->userdata('ID_PN');
                $check_protect = protectLhkpn($state_id_pn,$ID_LHKPN);
                if($check_protect){
                    $method = __METHOD__;
                    $this->load->model('mglobal');
                    $this->mglobal->recordLogAttacker($check_protect,$method);
                    echo 'Anda tidak memiliki akses pada data ini !!!';
                    return;
                }
            ////////////////SISTEM KEAMANAN////////////////
        }


        $this->db->select('t_lhkpn.*,CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN, j.NAMA_LEMBAGA, minst.INST_NAMA', FALSE);
//        $this->db->join('t_pn as p', 'p.ID_PN = t_lhkpn.ID_PN', 'LEFT');
        $this->db->join('t_pn_jabatan as j', 'j.ID_PN = t_lhkpn.ID_PN AND j.IS_CURRENT = 1', 'LEFT');
        $this->db->join('t_lhkpn_jabatan as tj', 'tj.ID_LHKPN = t_lhkpn.ID_LHKPN AND tj.IS_PRIMARY = 1', 'LEFT');
        $this->db->join('m_jabatan as mj', 'mj.ID_JABATAN = tj.ID_JABATAN', 'LEFT');
        $this->db->join('m_inst_satker as minst', 'minst.INST_SATKERKD = mj.INST_SATKERKD', 'LEFT');
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $LHKPN = $this->db->get('t_lhkpn')->row();

        if ($LHKPN) {
            $this->load->library('lwphpword/lwphpword', array(
                "base_path" => APPPATH . "../file/wrd_gen/",
                "base_url" => base_url() . "file/wrd_gen/",
                "base_root" => base_url(),
            ));

            $template_file = "../file/template/Lampiran4Template.docx";

            $this->load->library('lws_qr', [
                "model_qr" => "Cqrcode",
                "callable_model_function" => "insert_cqrcode_with_filename",
		"temp_dir"=>APPPATH . "../images/qrcode/"
            ]);

            // $this->load->library('ey_barcode');

            $this->lwphpword->save_path = APPPATH . "../file/wrd_gen/";

            $this->db->select('
			t_lhkpn.ID_PN,
			t_lhkpn.TGL_LAPOR,
			NULL AS ID,
			NULL AS ID_OLD,
			t_lhkpn_data_pribadi.NAMA_LENGKAP AS NAMA,
			t_lhkpn_data_pribadi.NIK,
			t_lhkpn_data_pribadi.TEMPAT_LAHIR,
			t_lhkpn_data_pribadi.TANGGAL_LAHIR,
			t_lhkpn_data_pribadi.ALAMAT_RUMAH,
			t_lhkpn_data_pribadi.KELURAHAN,
			t_lhkpn_data_pribadi.KECAMATAN,
			t_lhkpn_data_pribadi.KABKOT,
			t_lhkpn_data_pribadi.PROVINSI,
			NULL AS HUBUNGAN,
			NULL STATUS_KELUARGA,
			CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_data_pribadi.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
		', FALSE);
            $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
            $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
            $PN = $this->db->get('t_lhkpn_data_pribadi')->row();

            $this->db->select('
    		t_lhkpn.ID_PN,
    		t_lhkpn.TGL_LAPOR,
    		t_lhkpn_keluarga.ID_KELUARGA AS ID,
    		t_lhkpn_keluarga.ID_KELUARGA_LAMA AS ID_OLD,
    		REPLACE(t_lhkpn_keluarga.NAMA, ",", " ") AS NAMA,
			t_lhkpn_keluarga.NIK,
    		t_lhkpn_keluarga.TEMPAT_LAHIR,
    		t_lhkpn_keluarga.TANGGAL_LAHIR,
    		t_lhkpn_keluarga.ALAMAT_RUMAH,
    		t_lhkpn_keluarga.HUBUNGAN,
    		t_lhkpn_keluarga.STATUS_CETAK_SURAT_KUASA AS STATUS_KELUARGA,
    		CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1 AS UMUR,
    		CEIL(DATEDIFF(NOW(),t_lhkpn.TGL_LAPOR)/365)-1 AS TIME_LHKPN
    	', FALSE);

            $this->db->group_by('t_lhkpn_keluarga.ID_KELUARGA');
            $this->db->order_by('t_lhkpn_keluarga.TANGGAL_LAHIR', 'ASC');
            $this->db->where('t_lhkpn_keluarga.ID_KELUARGA', $ID_KELUARGA);
            $this->db->where('(CEIL(DATEDIFF(t_lhkpn.tgl_lapor,t_lhkpn_keluarga.TANGGAL_LAHIR)/365)-1) >=17');
            $this->db->where_in('t_lhkpn_keluarga.HUBUNGAN', array('1', '2', '3'));

//        if ($LHKPN->IS_COPY == '1' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
            if ($LHKPN->IS_COPY == '0' && (int) $LHKPN->TIME_LHKPN < 5) { // JIKA LAPORAN KE 2
                $this->db->where('t_lhkpn_keluarga.ID_KELUARGA_LAMA IS NULL');
            }

            $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_keluarga.ID_LHKPN');
            $KELUARGA = $this->db->get('t_lhkpn_keluarga')->row();

            /**
             * just check if template loaded successful
             * but will be override on ..
             */
            // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file);

            // if ($load_template_success) {

                $dataSK = array(
                    'ID' => $KELUARGA->ID,
                    'tgl_kirim' => $LHKPN->tgl_kirim,
                    'NAMA' => $KELUARGA->NAMA,
                    'TEMPAT_LAHIR' => $KELUARGA->TEMPAT_LAHIR,
                    'TANGGAL_LAHIR' => tgl_format($KELUARGA->TANGGAL_LAHIR),
                    'TTL' => $KELUARGA->TEMPAT_LAHIR . ' - ' . tgl_format($KELUARGA->TANGGAL_LAHIR),
                    'NIK' => $KELUARGA->NIK,
                    'ALAMAT' => $KELUARGA->ALAMAT_RUMAH,
                    'LAST' => '',
                    'NEXT' => '',
                    'UMUR' => $KELUARGA->UMUR,
                    'LIMA_TAHUN' => $LHKPN->TIME_LHKPN,
                    'STATUS_CETAK_SURAT_KUASA' => $KELUARGA->STATUS_KELUARGA
                );

                $SURAT_KUASA = $this->load->view('filing/surat_kuasa2', array('data' => $dataSK), TRUE);
                $this->db->where('ID_KELUARGA', $ID_KELUARGA);
                $this->db->update('t_lhkpn_keluarga', array('SURAT_KUASA' => $SURAT_KUASA));

                $result = $dataSK;

//                gwe_dump(array($result), TRUE);

                $tgl_lahir_qr = $KELUARGA->ID ? ($KELUARGA->TEMPAT_LAHIR == NULL ? '-' : $KELUARGA->TEMPAT_LAHIR) : tgl_format($KELUARGA->TANGGAL_LAHIR) == NULL ? '-' : tgl_format($KELUARGA->TANGGAL_LAHIR);

                $code = $result['ID'] == NULL ? $ID_LHKPN : $result['ID'];

                $qr_content_data = json_encode((object) [
                            "data" => [
//                            (object) ["tipe" => '1', "judul" => "NIK", "isi" => $data->NIK],
                                (object) ["tipe" => '1', "judul" => "Nama Lengkap", "isi" => $KELUARGA->NAMA],
                                (object) ["tipe" => '1', "judul" => "NIK", "isi" => $KELUARGA->NIK],
//                                (object) ["tipe" => '1', "judul" => "Tempat/Tgl. Lahir", "isi" => $result['TEMPAT_LAHIR'] . "/" . $tgl_lahir_qr],
                                (object) ["tipe" => '1', "judul" => "Tempat/Tgl. Lahir", "isi" => $result['TTL']],
                                (object) ["tipe" => '1', "judul" => "Alamat Lengkap", "isi" => $KELUARGA->ALAMAT_RUMAH == NULL ? '-' : $KELUARGA->ALAMAT_RUMAH],
//                                (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($result['tgl_kirim']) == NULL ? '-' : tgl_format($result['tgl_kirim'])],
                                (object) ["tipe" => '1', "judul" => "Tanggal Kirim", "isi" => tgl_format($LHKPN->tgl_kirim_final)],
                                (object) ["tipe" => '1', "judul" => "Nama PN", "isi" => $PN->NAMA],
                                (object) ["tipe" => '1', "judul" => "NIK PN", "isi" => $PN->NIK],
                                (object) ["tipe" => '1', "judul" => "Instansi", "isi" => $LHKPN->INST_NAMA],
                            ],
                            "encrypt_data" => $code . "sk",
                            "id_lhkpn" => $ID_LHKPN,
                            "judul" => "Surat Kuasa E-LHKPN",
//                            "tgl_surat" => tgl_format($result['tgl_kirim']) == NULL ? NULL : $result['tgl_kirim'],
                            "tgl_surat" => date('Y-m-d'),
                ]);

                $qr_file = "tes_qr2-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
                $qr_image_location = $this->lws_qr->create($qr_content_data, $qr_file);

                /*
                 * end penulisan qrcode
                 */

                /*
                 * load template
                 */
                $get_nik = $PN->NIK;
                $get_nama = $PN->NAMA;
                if($KELUARGA->HUBUNGAN=='1'){
                    $get_hubungan =  "SK ISTRI";
                }elseif($KELUARGA->HUBUNGAN=='2'){
                    $get_hubungan =  "SK SUAMI";
                }elseif($KELUARGA->HUBUNGAN=='3'){
                    $get_hubungan =  "SK ANAK";
                }else{
                    $get_hubungan = "SK PN";
                }

                // $show_barcode = "'".$get_nik.chr(9).$get_nama.chr(9).$get_hubungan;
                // $bc_file = "tes_bc2-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".jpg";
                // $bc_image_location = $this->ey_barcode->generate($show_barcode, $bc_file);

                $qr_info = "'".$get_nik.chr(95).$get_nama.chr(95).$get_hubungan;
                $show_qr2 = ($get_hubungan == "SK PN") ? $qr_info : $qr_info.chr(95).$KELUARGA->NAMA;

                $qr2_file = "tes_qr_new-" . $ID_LHKPN . "-" . date('Y-m-d_H-i-s') . ".png";
                $qr2_image_location = $this->lws_qr->create($show_qr2, $qr2_file);

                // $load_template_success = $this->lwphpword->load_template(APPPATH . $template_file, array(
                //     "image1.png" => $qr_image_location,"image2.png" => $bc_image_location
                // ));

                $prefix_jenis = "KELUARGA";

                $nama_subject = $KELUARGA->NAMA == NULL ? '-' : $KELUARGA->NAMA;

                // $this->lwphpword->set_value("NAMA_LENGKAP", $nama_subject);
                // $this->lwphpword->set_value("TEMPAT_LAHIR", $KELUARGA->TEMPAT_LAHIR == NULL ? '-' : $KELUARGA->TEMPAT_LAHIR);
                // $this->lwphpword->set_value("TANGGAL_LAHIR", $KELUARGA->TANGGAL_LAHIR == NULL ? '-' : tgl_format($KELUARGA->TANGGAL_LAHIR));
                // $this->lwphpword->set_value("NIK", $KELUARGA->NIK == NULL ? '-' : $KELUARGA->NIK);
                // $this->lwphpword->set_value("ALAMAT_LENGKAP", $KELUARGA->ALAMAT_RUMAH == NULL ? '-' : $KELUARGA->ALAMAT_RUMAH);


//                    $this->lwphpword->set_value("TGL_KIRIM", tgl_format($result['tgl_kirim']) == NULL ? '-' : tgl_format($result['tgl_kirim']));
                // $this->lwphpword->set_value("TGL_KIRIM", date('d-F-Y'));

                if ($ID_KELUARGA) {
                    $this->db->where('ID_KELUARGA', $ID_KELUARGA);
                    $this->db->update('t_lhkpn_keluarga', array(
                        'STATUS_CETAK_SURAT_KUASA' => '1',
                        'CETAK_SURAT_KUASA_TIME' => date('Y-m-d H:i:s')
                    ));
                }


                $uniqe_file = null;
                if($ID_KELUARGA){
                    $uniqe_file = $ID_KELUARGA;
                }else{
                    $uniqe_file = $ID_LHKPN;
                }



                ///////////////PDF//////////////
                $data = array(
                    "NAMA_LENGKAP" => $nama_subject,
                    "TEMPAT_LAHIR" => $result['TEMPAT_LAHIR'] == NULL ? '-' : $result['TEMPAT_LAHIR'],
                    "TANGGAL_LAHIR" => $result['TANGGAL_LAHIR'] == NULL ? '-' : $result['TANGGAL_LAHIR'],
                    "NIK" => $result['NIK'] == NULL ? '-' : $result['NIK'],
                    "ALAMAT_LENGKAP" => $result['ALAMAT'] == NULL ? '-' : $result['ALAMAT'],
                    "TGL_CETAK" => date('d-F-Y'),
                    // "BC_IMAGE_LOCATION"=>$bc_image_location,
                    "QR_IMAGE_LOCATION"=>$qr_image_location,
                    "QR2_IMAGE_LOCATION"=>$qr2_image_location
                );
                //    $this->load->library('dom_pdf');
                $this->load->library('pdfgenerator');
                $html = $this->load->view('pdf/surat_kuasa_pdf', $data, true);
                $filename = time() . '_surat_kuasa_'.$uniqe_file;
                $this->pdfgenerator->generate($html, $filename, true, 'A4', 'landscape');
                $temp_dir = APPPATH."../images/qrcode/";
                unlink($temp_dir.$qr_file);
                unlink($temp_dir.$qr2_file);
                // $temp_dir_br = APPPATH."../uploads/barcode/";
                // unlink($temp_dir_br.$bc_file);
                return;
                //////////////PDF//////////////

            //     $save_document_success = $this->lwphpword->save_document(FALSE, '', TRUE);

            //     if ($save_document_success) {
            //         $arr_nama = explode(" ", $KELUARGA->NAMA);
            //         $fn_nama = implode("-", $arr_nama);
            //         $output_filename = "Surat-Kuasa-" . $prefix_jenis . "-an." . str_replace(',', '', $fn_nama) . "-Tanggal-" . date('d-F-Y') . "." . $ID_LHKPN;
            //         $this->lwphpword->download($save_document_success->document_path, $output_filename);
            //     }
            // }
        }
    }

    function SEND_SMS($ID_LHKPN) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->join('t_lhkpn_data_pribadi', 't_lhkpn_data_pribadi.ID_LHKPN = t_lhkpn.ID_LHKPN');
        $LHKPN = $this->db->get('t_lhkpn')->row();
    }

}