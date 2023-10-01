<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_harta_update extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
    }

    function get_data_keluarga_baru($id_lhkpn){
        $this->db->select('ID_KELUARGA, ID_KELUARGA_LAMA, NAMA');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('IS_ACTIVE', 1);
        return $this->db->get('t_lhkpn_keluarga')->result_array();
    }

    function get_data_harta_sebelumnya($table_harta, $id_keluarga_old = []){
        $this->db->select('ID_KELUARGA, ID_KELUARGA_LAMA, NAMA');
        $this->db->where_in('ID_KELUARGA', $id_keluarga_old);
        $this->db->where('IS_ACTIVE', 1);
        return $this->db->get($table_harta)->result_array();
    }

    function ubah_id_keluarga_json_to_string($id_pasangan_anak_array){
        $data_pasangan_anak = json_encode($id_pasangan_anak_array);
        $data_pasangan_anak = str_replace('[', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace(']', '', $data_pasangan_anak);
        $data_pasangan_anak = str_replace('"', '', $data_pasangan_anak);
        return $data_pasangan_anak;
    }

    function compare_nama_pasangan_anak($data_keluarga_old = [], $data_keluarga_new = []){
        $id_pasangan_anak_array = [];
        foreach ($data_keluarga_old as $index => $keluargas) {
            foreach ($keluargas as $key => $value) {
                if($key == 'NAMA'){
                    if($value == $data_keluarga_new[$index][$key]){
                        $id_pasangan_anak_array[] = $data_keluarga_new[$index]['ID_KELUARGA_LAMA'];
                    }
                }
            }
        }

        return $id_pasangan_anak_array;
    }

    function harta_tidak_bergerak($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        /// -------- Ambil data lhkpn sebelumnya ------------- ///
        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();

        $data_keluarga_new = $this->get_data_keluarga_baru($ID_LHKPN_NEW);

        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION

            /// --- Ambil data harta tidak bergerak pada laporan sebelumnya --- ///
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('IS_ACTIVE','1');
            $this->db->where('(IS_PELEPASAN = \'0\' OR IS_PELEPASAN IS NULL)');
            $data = $this->db->get('t_lhkpn_harta_tidak_bergerak')->result();

            if ($data) {
                foreach ($data as $row) {

                    $id_keluarga_old = $row->PASANGAN_ANAK;
                    $id_keluarga_old_array = explode(',', $id_keluarga_old);
                    $data_keluarga_old = $this->get_data_harta_sebelumnya('t_lhkpn_keluarga', $id_keluarga_old_array);
                    $id_pasangan_anak_array = $this->compare_nama_pasangan_anak($data_keluarga_old, $data_keluarga_new);
                    $data_pasangan_anak = $this->ubah_id_keluarga_json_to_string($id_pasangan_anak_array);
    
                    $temp = array(
                        'ID_HARTA' => $row->ID,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'NEGARA' => $row->NEGARA,
                        'ID_NEGARA' => $row->ID_NEGARA,
                        'JALAN' => $row->JALAN,
                        'KEL' => $row->KEL,
                        'KEC' => $row->KEC,
                        'KAB_KOT' => $row->KAB_KOT,
                        'PROV' => $row->PROV,
                        'LUAS_TANAH' => $row->LUAS_TANAH,
                        'LUAS_BANGUNAN' => $row->LUAS_BANGUNAN,
                        'JENIS_BUKTI' => $row->JENIS_BUKTI,
                        'NOMOR_BUKTI' => $row->NOMOR_BUKTI,
                        'ATAS_NAMA' => $row->ATAS_NAMA,
                        'ASAL_USUL' => $row->ASAL_USUL,
                        'PEMANFAATAN' => $row->PEMANFAATAN,
                        'KET_LAINNYA' => $row->KET_LAINNYA,
                        'MATA_UANG' => 1,
                        'NILAI_PEROLEHAN' => $row->NILAI_PEROLEHAN,
                        'NILAI_PELAPORAN' => $row->NILAI_PELAPORAN,
                        'JENIS_NILAI_PELAPORAN' => $row->JENIS_NILAI_PELAPORAN,
                        'IS_ACTIVE' => 1,
                        'STATUS' => 3,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID,
                        'PASANGAN_ANAK'=> $data_pasangan_anak,
                        'TAHUN_PEROLEHAN_AWAL'=> $row->TAHUN_PEROLEHAN_AWAL
                    );
                    $this->db->insert('t_lhkpn_harta_tidak_bergerak', $temp);
                    $ID_HARTA = $this->db->insert_id();
                    if ($ID_HARTA) {
                        $this->db->where('ID_HARTA', $row->ID);
                        $data2 = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak')->result();
                        if ($data2) {
                            foreach ($data2 as $row2) {
                                $asl_array = array(
                                    'ID_HARTA' => $ID_HARTA,
                                    'ID_ASAL_USUL' => $row2->ID_ASAL_USUL,
                                    'TANGGAL_TRANSAKSI' => $row2->TANGGAL_TRANSAKSI,
                                    'NAMA' => $row2->NAMA,
                                    'ALAMAT' => $row2->ALAMAT,
                                    'URAIAN_HARTA' => $row2->URAIAN_HARTA,
                                    'NILAI_PELEPASAN' => $row2->NILAI_PELEPASAN,
                                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => $row2->UPDATED_TIME,
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                                $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_tidak_bergerak', $asl_array);
                            }
                        }
                    }
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

    function harta_mesin($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();

        $data_keluarga_new = $this->get_data_keluarga_baru($ID_LHKPN_NEW);

        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION

            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('IS_ACTIVE','1');
            $this->db->where('(IS_PELEPASAN = \'0\' OR IS_PELEPASAN IS NULL)');
            $data = $this->db->get('t_lhkpn_harta_bergerak')->result();

            if ($data) {

                foreach ($data as $row) {

                    $id_keluarga_old = $row->PASANGAN_ANAK;
                    $id_keluarga_old_array = explode(',', $id_keluarga_old);
                    $data_keluarga_old = $this->get_data_harta_sebelumnya('t_lhkpn_keluarga', $id_keluarga_old_array);
                    $id_pasangan_anak_array = $this->compare_nama_pasangan_anak($data_keluarga_old, $data_keluarga_new);
                    $data_pasangan_anak = $this->ubah_id_keluarga_json_to_string($id_pasangan_anak_array);

                    $data = array(
                        'ID_HARTA' => $row->ID,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'KODE_JENIS' => $row->KODE_JENIS,
                        'MEREK' => $row->MEREK,
                        'MODEL' => $row->MODEL,
                        'TAHUN_PEMBUATAN' => $row->TAHUN_PEMBUATAN,
                        'NOPOL_REGISTRASI' => $row->NOPOL_REGISTRASI,
                        'JENIS_BUKTI' => $row->JENIS_BUKTI,
                        'NOMOR_BUKTI' => $row->NOMOR_BUKTI,
                        'ATAS_NAMA' => $row->ATAS_NAMA,
                        'KET_LAINNYA' => $row->KET_LAINNYA,
                        'ASAL_USUL' => $row->ASAL_USUL,
                        'PEMANFAATAN' => $row->PEMANFAATAN,
                        'MATA_UANG' => 1,
                        'NILAI_PEROLEHAN' => $row->NILAI_PEROLEHAN,
                        'NILAI_PELAPORAN' => $row->NILAI_PELAPORAN,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'IS_ACTIVE' => 1,
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID,
                        'PASANGAN_ANAK'=> $data_pasangan_anak,
                        'TAHUN_PEROLEHAN_AWAL'=> $row->TAHUN_PEROLEHAN_AWAL
                    );
                    $this->db->insert('t_lhkpn_harta_bergerak', $data);
                    $ID_HARTA = $this->db->insert_id();
                    if ($ID_HARTA) {
                        $this->db->where('ID_HARTA', $row->ID);
                        $data2 = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_bergerak')->result();
                        if ($data2) {
                            foreach ($data2 as $asl) {
                                $asl_array = array(
                                    'ID_HARTA' => $ID_HARTA,
                                    'ID_ASAL_USUL' => $asl->ID_ASAL_USUL,
                                    'TANGGAL_TRANSAKSI' => $asl->TANGGAL_TRANSAKSI,
                                    'NAMA' => $asl->NAMA,
                                    'ALAMAT' => $asl->ALAMAT,
                                    'URAIAN_HARTA' => $asl->URAIAN_HARTA,
                                    'NILAI_PELEPASAN' => $asl->NILAI_PELEPASAN,
                                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => $asl->UPDATED_TIME,
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                                $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_bergerak', $asl_array);
                            }
                        }
                    }
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

    function harta_bergerak($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();
        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION

            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('IS_ACTIVE','1');
            $this->db->where('(IS_PELEPASAN = \'0\' OR IS_PELEPASAN IS NULL)');
            $data = $this->db->get('t_lhkpn_harta_bergerak_lain')->result();

            if ($data) {
                foreach ($data as $row) {
                    $temp = array(
                        'ID_HARTA' => $row->ID,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'KODE_JENIS' => $row->KODE_JENIS,
                        'JUMLAH' => $row->JUMLAH,
                        'SATUAN' => $row->SATUAN,
                        'KETERANGAN' => $row->KETERANGAN,
                        'ASAL_USUL' => $row->ASAL_USUL,
                        'MATA_UANG' => 1,
                        'NILAI_PEROLEHAN' => $row->NILAI_PEROLEHAN,
                        'NILAI_PELAPORAN' => $row->NILAI_PELAPORAN,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'IS_ACTIVE' => 1,
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID,
                        'TAHUN_PEROLEHAN_AWAL'=> $row->TAHUN_PEROLEHAN_AWAL
                    );
                    $this->db->insert('t_lhkpn_harta_bergerak_lain', $temp);
                    $ID_HARTA = $this->db->insert_id();
                    if ($ID_HARTA) {
                        $this->db->where('ID_HARTA', $row->ID);
                        $data2 = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain')->result();
                        if ($data2) {
                            foreach ($data2 as $row2) {
                                $asl_array = array(
                                    'ID_HARTA' => $ID_HARTA,
                                    'ID_ASAL_USUL' => $row2->ID_ASAL_USUL,
                                    'TANGGAL_TRANSAKSI' => $row2->TANGGAL_TRANSAKSI,
                                    'NAMA' => $row2->NAMA,
                                    'ALAMAT' => $row2->ALAMAT,
                                    'URAIAN_HARTA' => $row2->URAIAN_HARTA,
                                    'NILAI_PELEPASAN' => $row2->NILAI_PELEPASAN,
                                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => $row2->UPDATED_TIME,
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                                $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_bergerak_lain', $asl_array);
                            }
                        }
                    }
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

    function harta_surat_berharga($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();

        $data_keluarga_new = $this->get_data_keluarga_baru($ID_LHKPN_NEW);

        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('IS_ACTIVE','1');
            $this->db->where('(IS_PELEPASAN = \'0\' OR IS_PELEPASAN IS NULL)');
            $data = $this->db->get('t_lhkpn_harta_surat_berharga')->result();
            if ($data) {
                foreach ($data as $row) {

                    $FILE = NULL;
                    if(file_exists($row->FILE_BUKTI)){
                        $file_lama = $row->FILE_BUKTI;
                        $array = explode('.', $file_lama);
                        $extension = end($array);
                        $file_name = $row->ID.'0'.time();
                        $file_baru = 'uploads/data_suratberharga/'.$this->session->userdata('USERNAME').'/'.$file_name.'.'.$extension;
                        $c = copy($file_lama, $file_baru);
                        if($c){
                            $FILE = $file_baru;
                        }
                    }

                    $id_keluarga_old = $row->PASANGAN_ANAK;
                    $id_keluarga_old_array = explode(',', $id_keluarga_old);
                    $data_keluarga_old = $this->get_data_harta_sebelumnya('t_lhkpn_keluarga', $id_keluarga_old_array);
                    $id_pasangan_anak_array = $this->compare_nama_pasangan_anak($data_keluarga_old, $data_keluarga_new);
                    $data_pasangan_anak = $this->ubah_id_keluarga_json_to_string($id_pasangan_anak_array);

                    $temp = array(
                        'ID_HARTA' => $row->ID,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'KODE_JENIS' => $row->KODE_JENIS,
                        'ATAS_NAMA' => $row->ATAS_NAMA,
                        'ASAL_USUL' => $row->ASAL_USUL,
                        'NAMA_PENERBIT' => $row->NAMA_PENERBIT,
                        //'KETERANGAN' => $row->KETERANGAN,
                        'CUSTODIAN' => $row->CUSTODIAN,
                        'MATA_UANG' => 1,
                        'NILAI_PEROLEHAN' => $row->NILAI_PEROLEHAN,
                        'NILAI_PELAPORAN' => $row->NILAI_PELAPORAN,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'FILE_BUKTI'=>$FILE,
                        'NOMOR_REKENING'=>$row->NOMOR_REKENING,
                        'IS_ACTIVE' => 1,
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID,
                        'PASANGAN_ANAK'=> $data_pasangan_anak,
                        'TAHUN_PEROLEHAN_AWAL'=> $row->TAHUN_PEROLEHAN_AWAL,
                        'KET_LAINNYA'=> $row->KET_LAINNYA
                    );
                    $this->db->insert('t_lhkpn_harta_surat_berharga', $temp);
                    $ID_HARTA = $this->db->insert_id();
                    if ($ID_HARTA) {
                        $this->db->where('ID_HARTA', $row->ID);
                        $data2 = $this->db->get('t_lhkpn_asal_usul_pelepasan_surat_berharga')->result();
                        if ($data2) {
                            foreach ($data2 as $row2) {
                                $temp2 = array(
                                    'ID_HARTA' => $ID_HARTA,
                                    'ID_ASAL_USUL' => $row2->ID_ASAL_USUL,
                                    'TANGGAL_TRANSAKSI' => $row2->TANGGAL_TRANSAKSI,
                                    'NAMA' => $row2->NAMA,
                                    'ALAMAT' => $row2->ALAMAT,
                                    'URAIAN_HARTA' => $row2->URAIAN_HARTA,
                                    'NILAI_PELEPASAN' => $row2->NILAI_PELEPASAN,
                                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => $row2->UPDATED_TIME,
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                            }
                            $this->db->insert('t_lhkpn_asal_usul_pelepasan_surat_berharga', $temp2);
                        }
                    }
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

    function harta_kas($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();

        $data_keluarga_new = $this->get_data_keluarga_baru($ID_LHKPN_NEW);

        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION

            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('IS_ACTIVE','1');
            $this->db->where('(IS_PELEPASAN = \'0\' OR IS_PELEPASAN IS NULL)');
            $data = $this->db->get('t_lhkpn_harta_kas')->result();
            
            if ($data) {
                foreach ($data as $row) {

                    $FILE = NULL;
                    if(file_exists($row->FILE_BUKTI)){
                        $file_lama = $row->FILE_BUKTI;
                        $array = explode('.', $file_lama);
                        $extension = end($array);
                        $file_name = $row->ID.'0'.time();
                        $file_baru = 'uploads/data_kas/'.$this->session->userdata('USERNAME').'/'.$file_name.'.'.$extension;
                        $c = copy($file_lama, $file_baru);
                        if($c){
                            $FILE = $file_baru;
                        }
                    }

                    $id_keluarga_old = $row->PASANGAN_ANAK;
                    $id_keluarga_old_array = explode(',', $id_keluarga_old);
                    $data_keluarga_old = $this->get_data_harta_sebelumnya('t_lhkpn_keluarga', $id_keluarga_old_array);
                    $id_pasangan_anak_array = $this->compare_nama_pasangan_anak($data_keluarga_old, $data_keluarga_new);
                    $data_pasangan_anak = $this->ubah_id_keluarga_json_to_string($id_pasangan_anak_array);

                    $temp = array(
                        'ID_HARTA' => $row->ID,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'KODE_JENIS' => $row->KODE_JENIS,
                        'ASAL_USUL' => $row->ASAL_USUL,
                        'NAMA_BANK' => $row->NAMA_BANK,
                        'NOMOR_REKENING' => $row->NOMOR_REKENING,
                        'ATAS_NAMA_REKENING' => $row->ATAS_NAMA_REKENING,
                        'MATA_UANG' => $row->MATA_UANG,
                        'NILAI_SALDO' => $row->NILAI_SALDO,
                        'KETERANGAN'=>$row->KETERANGAN,
                        'NILAI_EQUIVALEN' => $row->NILAI_EQUIVALEN,
                        'IS_ACTIVE' => 1,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'FILE_BUKTI'=>$FILE,
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID,
                        'PASANGAN_ANAK'=> $data_pasangan_anak,
                        'TAHUN_BUKA_REKENING'=> $row->TAHUN_BUKA_REKENING,
                        'NILAI_KURS'=> $row->NILAI_KURS,
                    );
                    $this->db->insert('t_lhkpn_harta_kas', $temp);
                    $ID_HARTA = $this->db->insert_id();
                    if ($ID_HARTA) {
                        $this->db->where('ID_HARTA', $row->ID);
                        $data2 = $this->db->get('t_lhkpn_asal_usul_pelepasan_kas')->result();
                        if ($data2) {
                            foreach ($data2 as $row2) {
                                $asl = array(
                                    'ID_HARTA' => $ID_HARTA,
                                    'ID_ASAL_USUL' => $row2->ID_ASAL_USUL,
                                    'TANGGAL_TRANSAKSI' => $row2->TANGGAL_TRANSAKSI,
                                    'NAMA' => $row2->NAMA,
                                    'ALAMAT' => $row2->ALAMAT,
                                    'URAIAN_HARTA' => $row2->URAIAN_HARTA,
                                    'NILAI_PELEPASAN' => $row2->NILAI_PELEPASAN,
                                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => $row2->UPDATED_TIME,
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                                $this->db->insert('t_lhkpn_asal_usul_pelepasan_kas', $asl);
                            }
                        }
                    }
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

    function harta_lain($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();

        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION

            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->where('IS_ACTIVE','1');
            $this->db->where('(IS_PELEPASAN = \'0\' OR IS_PELEPASAN IS NULL)');
            $data = $this->db->get('t_lhkpn_harta_lainnya')->result();

            if ($data) {
                foreach ($data as $row) {
                    
                    $FILE = NULL;
                    if(file_exists($row->FILE_BUKTI)){
                        $file_lama = $row->FILE_BUKTI;
                        $array = explode('.', $file_lama);
                        $extension = end($array);
                        $file_name = $row->ID.'0'.time();
                        $file_baru = 'uploads/data_hartalainnya/'.$this->session->userdata('USERNAME').'/'.$file_name.'.'.$extension;
                        $c = copy($file_lama, $file_baru);
                        if($c){
                            $FILE = $file_baru;
                        }
                    }
                    
                    $temp = array(
                        'ID_HARTA' => $row->ID,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'KODE_JENIS' => $row->KODE_JENIS,
                        'ASAL_USUL' => $row->ASAL_USUL,
                        'MATA_UANG' => 1,
                        'KETERANGAN'=>$row->KETERANGAN,
                        'NILAI_PEROLEHAN' => $row->NILAI_PEROLEHAN,
                        'NILAI_PELAPORAN' => $row->NILAI_PELAPORAN,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'FILE_BUKTI'=>$FILE,
                        'IS_ACTIVE' => 1,
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID,
                        'TAHUN_PEROLEHAN_AWAL'=> $row->TAHUN_PEROLEHAN_AWAL
                    );
                    $this->db->insert('t_lhkpn_harta_lainnya', $temp);
                    $ID_HARTA = $this->db->insert_id();
                    if ($ID_HARTA) {
                        $this->db->where('ID_HARTA', $row->ID);
                        $data2 = $this->db->get('t_lhkpn_asal_usul_pelepasan_harta_lainnya')->result();
                        if ($data2) {
                            foreach ($data2 as $row2) {
                                $temp2 = array(
                                    'ID_HARTA' => $ID_HARTA,
                                    'ID_ASAL_USUL' => $row2->ID_ASAL_USUL,
                                    'TANGGAL_TRANSAKSI' => $row2->TANGGAL_TRANSAKSI,
                                    'NAMA' => $row2->NAMA,
                                    'ALAMAT' => $row2->ALAMAT,
                                    'URAIAN_HARTA' => $ro2->URAIAN_HARTA,
                                    'NILAI_PELEPASAN' => $row2->NILAI_PELEPASAN,
                                    'CREATED_TIME' => date("Y-m-d H:i:s"),
                                    'CREATED_BY' => $this->session->userdata('NAMA'),
                                    'CREATED_IP' => get_client_ip(),
                                    'UPDATED_TIME' => $row2->UPDATED_TIME,
                                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                                    'UPDATED_IP' => get_client_ip(),
                                );
                                $this->db->insert('t_lhkpn_asal_usul_pelepasan_harta_lainnya', $temp2);
                            }
                        }
                    }
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

    function hutang($ID_LHKPN_NEW) {
        $result = 0;
        $this->db->select('ID_PN');
        $this->db->where('ID_LHKPN', $ID_LHKPN_NEW);
        $ID_PN = $this->db->get('t_lhkpn')->row()->ID_PN;

        // $ID_PN = $this->session->userdata('ID_PN');
        $this->db->where('ID_PN', $ID_PN);
        $this->db->where('ID_LHKPN !=',$ID_LHKPN_NEW);
        $this->db->where('IS_ACTIVE', '1');
        $this->db->where('STATUS !=','0');
//        $this->db->where('CASE WHEN JENIS_LAPORAN = 5 THEN STATUS >  \'2\' ELSE TRUE END', NULL, FALSE);
        $this->db->where('JENIS_LAPORAN <>','5');
        $this->db->order_by('TGL_LAPOR', 'DESC');
        $this->db->limit(1);
        $t_lhkpn = $this->db->get('t_lhkpn')->row();

        $data_keluarga_new = $this->get_data_keluarga_baru($ID_LHKPN_NEW);

        if ($t_lhkpn) {

            $ID_LHKPN = $t_lhkpn->ID_LHKPN;
            // DO ACTION

            $this->db->where('IS_ACTIVE','1');
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $data = $this->db->get('t_lhkpn_hutang')->result();
            if ($data) {
                foreach ($data as $row) {

                    $id_keluarga_old = $row->PASANGAN_ANAK;
                    $id_keluarga_old_array = explode(',', $id_keluarga_old);
                    $data_keluarga_old = $this->get_data_harta_sebelumnya('t_lhkpn_keluarga', $id_keluarga_old_array);
                    $id_pasangan_anak_array = $this->compare_nama_pasangan_anak($data_keluarga_old, $data_keluarga_new);
                    $data_pasangan_anak = $this->ubah_id_keluarga_json_to_string($id_pasangan_anak_array);

                    $temp = array(
                        'ID_HARTA' => $row->ID_HUTANG,
                        'ID_LHKPN' => $ID_LHKPN_NEW,
                        'ATAS_NAMA' => $row->ATAS_NAMA,
                        'KODE_JENIS' => $row->KODE_JENIS,
                        'NAMA_KREDITUR' => $row->NAMA_KREDITUR,
                        'AGUNAN' => $row->AGUNAN,
                        'AWAL_HUTANG' => $row->AWAL_HUTANG,
                        'SALDO_HUTANG' => $row->SALDO_HUTANG,
                        'IS_ACTIVE' => 1,
                        'CREATED_TIME' => date("Y-m-d H:i:s"),
                        'CREATED_BY' => $this->session->userdata('NAMA'),
                        'CREATED_IP' => get_client_ip(),
                        'UPDATED_TIME' => $row->UPDATED_TIME,
                        'UPDATED_BY' => $this->session->userdata('NAMA'),
                        'UPDATED_IP' => get_client_ip(),
                        'IS_LOAD'=>'1',
                        'Previous_ID'=> $row->ID_HUTANG,
                        'PASANGAN_ANAK'=> $data_pasangan_anak,
                        'KET_LAINNYA' => $row->KET_LAINNYA,
                    );
                    $this->db->insert('t_lhkpn_hutang', $temp);
                }
                $result = 1;
            }
        }
        echo $result;exit;
    }

}
