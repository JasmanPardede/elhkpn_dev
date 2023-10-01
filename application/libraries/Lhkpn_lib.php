<?php
class Lhkpn_lib {
    public $CI;
    public $username;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->username = $this->CI->session->userdata('USERNAME');
    }


    public function copylhkpn($pn = '0', $tahun = '2015', $entryvia = '1', $type = '')
    {
        $this->CI->load->database();
        $this->CI->load->model('mglobal');
        $jenis = $this->CI->input->post('JENIS_LAPORAN');
        if ($jenis == '4') {
            $tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->CI->input->post('TAHUN_PELAPORAN')) . '-12-30'));
            $thn = date('Y', strtotime($tgl));
        } else {
            $tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->CI->input->post('TANGGAL_PELAPORAN'))));
            $thn = date('Y', strtotime($tgl));
        }

        $cekPn = $this->CI->mglobal->get_data_all('T_LHKPN', NULL, ['ID_PN' => $pn, 'YEAR(TGL_LAPOR)' => $thn]);

        if(!empty($cekPn)){
            $this->CI->db->trans_rollback();
            return false;
        }else{
            $lhkpn = $this->check_tahun_terakhir($pn, $tahun);
            
            if ($lhkpn == false) {
                $pn = $this->CI->input->post('ID_PN');

                $data = [];
                $data['ID_PN'] = $pn;
                $data['JENIS_LAPORAN'] = $jenis;
                $data['TGL_LAPOR'] = $tgl;
                $data['IS_ACTIVE'] = 1;
                $data['ENTRY_VIA'] = $entryvia;
                $data['CREATED_TIME'] = time();
                $data['CREATED_IP'] = $_SERVER["REMOTE_ADDR"];
    //            if ($type == 'penerimaan') {
    //                $data['STATUS'] = '1';
    //            }
    //            else
    //            {
                    $data['STATUS'] = '0';
    //            }

                    
                $result = $this->CI->mglobal->insert('T_LHKPN', $data);


                $id = $result['id'];
                $reckel = $this->CI->mglobal->get_data_all('T_LHKPN', NULL, ['ID_PN' => $pn])[0];
                $IDLHKPNLAMA = $reckel->ID_LHKPN;

                $detailkel = $this->CI->mglobal->get_data_all('t_lhkpn_keluarga', NULL, ['ID_LHKPN' => $IDLHKPNLAMA]);
                if($detailkel){
                      foreach ($detailkel as $kl) {
                            $arr_keluarga = array(
                                'ID_KELUARGA_LAMA' => $kl->ID_KELUARGA,
                                'ID_LHKPN' => $id,
                                'NAMA' => $kl->NAMA,
                                'HUBUNGAN' => $kl->HUBUNGAN,
                                'STATUS_HUBUNGAN' => $kl->STATUS_HUBUNGAN,
                                'TEMPAT_LAHIR' => $kl->TEMPAT_LAHIR,
                                'TANGGAL_LAHIR' => $kl->TANGGAL_LAHIR,
                                'JENIS_KELAMIN' => $kl->JENIS_KELAMIN,
                                'TEMPAT_NIKAH' => $kl->TEMPAT_NIKAH,
                                'TANGGAL_NIKAH' => $kl->TANGGAL_NIKAH,
                                'TEMPAT_CERAI' => $kl->TEMPAT_CERAI,
                                'TANGGAL_CERAI' => $kl->TANGGAL_CERAI,
                                'PEKERJAAN' => $kl->PEKERJAAN,
                                'ALAMAT_RUMAH' => $kl->ALAMAT_RUMAH,
                                'NOMOR_TELPON' => $kl->NOMOR_TELPON,
                                'IS_ACTIVE' => 1,
                                'CREATED_TIME' => time(),
                                'CREATED_BY' => $this->CI->session->userdata('USERNAME'),
                                'CREATED_IP' => $this->CI->input->ip_address(),
                                'UPDATED_TIME' => time(),
                                'UPDATED_BY' => $this->CI->session->userdata('USERNAME'),
                                'UPDATED_IP' => $this->CI->input->ip_address(),
                            );
                            $this->CI->mglobal->insert('t_lhkpn_keluarga', $arr_keluarga);
                        }
                    }else{
                     $this->loadwskeluarga($id);
                    }
                    // $this->loadwskeluarga($id);
                    // $this->loadAdminduk($id);
               
                // Copy Data Pribadi dan Jabatan
                $rec = $this->CI->mglobal->get_data_all('T_PN', NULL, ['ID_PN' => $pn])[0];

                $data = [];
                $data['ID_LHKPN'] = $id;
                $data['NAMA_LENGKAP'] = $rec->NAMA;
                $data['JENIS_KELAMIN'] = $rec->JNS_KEL;
                $data['TEMPAT_LAHIR'] = $rec->TEMPAT_LAHIR;
                $data['TANGGAL_LAHIR'] = $rec->TGL_LAHIR;
                $data['NIK'] = $rec->NIK;
                $data['STATUS_PERKAWINAN'] = $rec->ID_STATUS_NIKAH;
                $data['AGAMA'] = $rec->ID_AGAMA;
                $data['NPWP'] = $rec->NPWP;
                $data['ALAMAT_RUMAH'] = $rec->ALAMAT_TINGGAL;
                $data['EMAIL_PRIBADI'] = $rec->EMAIL;
                $data['PROVINSI'] = $rec->PROV;
                $data['KECAMATAN'] = $rec->KEC;
                $data['KELURAHAN'] = $rec->KEL;
                $data['KABKOT'] = $rec->KAB_KOT;
                $data['FOTO'] = $rec->FOTO;
                $data['IS_ACTIVE'] = '1';
                // $data['TELPON_RUMAH'] = $rec->KAB_KOT;
                // $data['KABKOT'] = $rec->KAB_KOT;
                // $id=$data['ID_LHKPN'];
             
                $this->CI->mglobal->insert('T_LHKPN_DATA_PRIBADI', $data);

                $htngJab = $this->CI->mglobal->get_data_all('T_PN_JABATAN', NULL, ['ID_PN' => $pn], 'COUNT(ID) AS JML')[0];
                $htjab1  = $this->CI->mglobal->get_data_all('T_PN_JABATAN', NULL, ['ID_PN' => $pn, 'IS_CURRENT' => 0], 'COUNT(ID) AS JML')[0];
                if($htjab1 == $htngJab){
                    $where = ['ID_PN' => $pn, 'IS_CURRENT' => 0];
                    $order = ['ID', 'DESC'];
                    $limit = '1';
                }else{
                    $where = ['ID_PN' => $pn, 'IS_CURRENT' => 1, 'ID_STATUS_AKHIR_JABAT' => '0'];
                    $order = NULL;
                    $limit = NULL;
                }
                $jab = $this->CI->mglobal->get_data_all('T_PN_JABATAN', NULL, $where, '*', NULL, $order, NULL, $limit);

               
                foreach ($jab as $row) {

                    $this->CI->db->like('UK_NAMA',$row->UNIT_KERJA);
                    $this->CI->db->limit(1);
                    $tmp = $this->CI->db->get('m_unit_kerja')->row();

                    $data = [];
                    $data['ID_LHKPN'] = $id;
                    $data['ID_JABATAN'] = $row->ID_JABATAN;
                    $data['DESKRIPSI_JABATAN'] = $row->DESKRIPSI_JABATAN;
                    $data['ESELON'] = $row->ESELON;
                    $data['LEMBAGA'] = $row->LEMBAGA;
                    $data['UNIT_KERJA'] = $tmp->UK_ID;
                    $data['TMT'] = $row->TMT;
                    $data['SD'] = $row->SD;
                    $data['ALAMAT_KANTOR'] = $row->ALAMAT_KANTOR;
                    $data['EMAIL_KANTOR'] = $row->EMAIL_KANTOR;
                    $data['ID_STATUS_AKHIR_JABAT'] = $row->ID_STATUS_AKHIR_JABAT;

                    $this->CI->mglobal->insert('T_LHKPN_JABATAN', $data);
                }

                $check = $this->CI->db->trans_commit();

                return $id;
            } else {
                foreach ($lhkpn['data'] as $key => $value) {
                    $id = $value['ID_LHKPN'];
                    unset($value['ID_LHKPN']);
                    unset($value['USERNAME_ENTRI']);
                    unset($value['STATUS_PERBAIKAN_NASKAH']);
                    unset($value['CATATAN_PERBAIKAN_NASKAH']);
                    $jenis = $this->CI->input->post('JENIS_LAPORAN');
                    
                    $value['JENIS_LAPORAN'] = $jenis;
                    $value['tgl_lapor'] = $tgl;
                    $value['STATUS']    = '0';
                    $value['IS_ACTIVE'] = 1;
                    $value['IS_COPY']   = '1';
                    $value['entry_via'] = $entryvia;

                    $result = $this->CI->mglobal->insert('T_LHKPN', $value);
                    $IDBARU = $this->CI->db->insert_id();
                   
                    if ($result['status']) {
                        $detail = [];

                        $detail['KELUARGA'] = $this->getdatakeluarga($id);
                        foreach ($detail['KELUARGA'] as $key => $value) {
                            unset($value['ID_KELUARGA']);
                            $value['ID_LHKPN'] = $result['id'];
                            $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                            $value['CREATED_BY'] = $this->username;
                            $value['CREATED_IP'] = $this->CI->input->ip_address();
                            $value['UPDATED_TIME'] = '';
                            $value['UPDATED_BY'] = '';
                            $value['UPDATED_IP'] = '';

                            $this->CI->mglobal->insert('T_LHKPN_KELUARGA', $value);
                        }

                        // $detail['HUTANG'] = $this->getdatahutang($id);
                        // foreach ($detail['HUTANG'] as $key => $value) {
                        //     // $value['ID_HARTA'] = $value['ID_HUTANG'];
                        //     unset($value['ID_HUTANG']);
                        //     $value['ID_LHKPN'] = $result['id'];
                        //     $value['STATUS']    = '1';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY'] = $this->username;
                        //     $value['CREATED_IP'] = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY'] = '';
                        //     $value['UPDATED_IP'] = '';

                        //     $this->CI->mglobal->insert('T_LHKPN_HUTANG', $value);
                        // }

                        $detail['PRIBADI'] = $this->getdatapribadi($id);
                        foreach ($detail['PRIBADI'] as $key => $value) {
                            unset($value['ID']);
                            $value['ID_LHKPN'] = $result['id'];
                            $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                            $value['CREATED_BY'] = $this->username;
                            $value['CREATED_IP'] = $this->CI->input->ip_address();
                            $value['UPDATED_TIME'] = '';
                            $value['UPDATED_BY'] = '';
                            $value['UPDATED_IP'] = '';

                            $this->CI->mglobal->insert('T_LHKPN_DATA_PRIBADI', $value);
                        }

                        $detail['JABATAN']        = $this->getdatajabatan($id);
                        foreach ($detail['JABATAN'] as $key => $value) {
                            unset($value['ID']);
                            $value['ID_LHKPN']     = $result['id'];
                            $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                            $value['CREATED_BY']   = $this->username;
                            $value['CREATED_IP']   = $this->CI->input->ip_address();
                            $value['UPDATED_TIME'] = '';
                            $value['UPDATED_BY']   = '';
                            $value['UPDATED_IP']   = '';

                            $this->CI->mglobal->insert('T_LHKPN_JABATAN', $value);
                        }

                        // $detail['PENERIMAAN']        = $this->getdatapenerimaan($id);
                        // foreach ($detail['PENERIMAAN'] as $key => $value) {
                        //     unset($value['ID_PENERIMAAN_KAS']);
                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $this->CI->mglobal->insert('T_LHKPN_PENERIMAAN_KAS', $value);
                        // }

                        // $detail['PENGELUARAN']        = $this->getdatapengeluaran($id);
                        // foreach ($detail['PENGELUARAN'] as $key => $value) {
                        //     unset($value['ID_PENGELUARAN_KAS']);
                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $this->CI->mglobal->insert('T_LHKPN_PENGELUARAN_KAS', $value);
                        // }

                        // $detail['FASILITAS']        = $this->getdatafasilitas($id);
                        // foreach ($detail['FASILITAS'] as $key => $value) {
                        //     unset($value['ID']);
                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $this->CI->mglobal->insert('T_LHKPN_FASILITAS', $value);
                        // }

                        // $detail['HARTA']                     = [];
                        // $detail['HARTA']['BERGERAK']         = $this->getdatahartabergerak($id);
                        // foreach ($detail['HARTA']['BERGERAK'] as $key => $value) {
                        //     $value['ID_HARTA']     = $value['ID'];
                        //     unset($value['ID']);
                        //     list($value, $detailA) = $this->isExistArray($value, 'detail'); // Asal Usul Pelepasan
                        //     list($value, $detailB) = $this->isExistArray($value, 'pelepasan'); // Pelepasan

                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['STATUS']       = '1';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';
                        //     $value['IS_CHECKED']   = '0';

                        //     $idT = $this->CI->mglobal->insert('T_LHKPN_HARTA_BERGERAK', $value);

                        //     $this->insertTo($detailA, 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', $idT['id']);
                        //     $this->insertTo($detailB, 'T_LHKPN_PELEPASAN_HARTA_BERGERAK', $idT['id'], $result['id']);
                        // }

                        // $detail['HARTA']['BERGERAK_LAIN']    = $this->getdatahartabergeraklain($id);
                        // foreach ($detail['HARTA']['BERGERAK_LAIN'] as $key => $value) {
                        //     $value['ID_HARTA']     = $value['ID'];
                        //     unset($value['ID']);
                        //     list($value, $detailA) = $this->isExistArray($value, 'detail'); // Asal Usul Pelepasan
                        //     list($value, $detailB) = $this->isExistArray($value, 'pelepasan'); // Pelepasan

                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['STATUS']       = '1';
                        //     $value['IS_CHECKED']   = '0';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $idT = $this->CI->mglobal->insert('T_LHKPN_HARTA_BERGERAK_LAIN', $value);

                        //     $this->insertTo($detailA, 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', $idT['id']);
                        //     $this->insertTo($detailB, 'T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', $idT['id'], $result['id']);
                        // }

                        // $detail['HARTA']['KAS']              = $this->getdatahartakas($id);
                        // foreach ($detail['HARTA']['KAS'] as $key => $value) {
                        //     $value['ID_HARTA']     = $value['ID'];
                        //     unset($value['ID']);
                        //     list($value, $detailA) = $this->isExistArray($value, 'detail'); // Asal Usul Pelepasan
                        //     list($value, $detailB) = $this->isExistArray($value, 'pelepasan'); // Pelepasan

                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['STATUS']       = '1';
                        //     $value['IS_CHECKED']   = '0';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $idT = $this->CI->mglobal->insert('T_LHKPN_HARTA_KAS', $value);

                        //     $this->insertTo($detailA, 'T_LHKPN_ASAL_USUL_PELEPASAN_KAS', $idT['id']);
                        //     $this->insertTo($detailB, 'T_LHKPN_PELEPASAN_HARTA_KAS', $idT['id'], $result['id']);
                        // }

                        // $detail['HARTA']['LAINNYA']          = $this->getdatahartalainnya($id);
                        // foreach ($detail['HARTA']['LAINNYA'] as $key => $value) {
                        //     $value['ID_HARTA']     = $value['ID'];
                        //     unset($value['ID']);
                        //     list($value, $detailA) = $this->isExistArray($value, 'detail'); // Asal Usul Pelepasan
                        //     list($value, $detailB) = $this->isExistArray($value, 'pelepasan'); // Pelepasan

                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['STATUS']       = '1';
                        //     $value['IS_CHECKED']   = '0';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $idT = $this->CI->mglobal->insert('T_LHKPN_HARTA_LAINNYA', $value);

                        //     $this->insertTo($detailA, 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA', $idT['id']);
                        //     $this->insertTo($detailB, 'T_LHKPN_PELEPASAN_HARTA_LAINNYA', $idT['id'], $result['id']);
                        // }

                        // $detail['HARTA']['SURAT_BERHARGA']   = $this->getdatahartasuratberharga($id);
                        // foreach ($detail['HARTA']['SURAT_BERHARGA'] as $key => $value) {
                        //     $value['ID_HARTA']     = $value['ID'];
                        //     unset($value['ID']);
                        //     list($value, $detailA) = $this->isExistArray($value, 'detail'); // Asal Usul Pelepasan
                        //     list($value, $detailB) = $this->isExistArray($value, 'pelepasan'); // Pelepasan

                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['STATUS']       = '1';
                        //     $value['IS_CHECKED']   = '0';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $idT = $this->CI->mglobal->insert('T_LHKPN_HARTA_SURAT_BERHARGA', $value);

                        //     $this->insertTo($detailA, 'T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA', $idT['id']);
                        //     $this->insertTo($detailB, 'T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', $idT['id'], $result['id']);
                        // }

                        // $detail['HARTA']['TIDAK_BERGERAK']   = $this->getdatahartatidakbergerak($id);
                        // foreach ($detail['HARTA']['TIDAK_BERGERAK'] as $key => $value) {
                        //     $value['ID_HARTA']     = $value['ID'];
                        //     unset($value['ID']);
                        //     list($value, $detailA) = $this->isExistArray($value, 'detail'); // Asal Usul Pelepasan
                        //     list($value, $detailB) = $this->isExistArray($value, 'pelepasan'); // Pelepasan

                        //     $value['ID_LHKPN']     = $result['id'];
                        //     $value['STATUS']       = '1';
                        //     $value['IS_CHECKED']   = '0';
                        //     $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                        //     $value['CREATED_BY']   = $this->username;
                        //     $value['CREATED_IP']   = $this->CI->input->ip_address();
                        //     $value['UPDATED_TIME'] = '';
                        //     $value['UPDATED_BY']   = '';
                        //     $value['UPDATED_IP']   = '';

                        //     $idT = $this->CI->mglobal->insert('T_LHKPN_HARTA_TIDAK_BERGERAK', $value);

                        //     $this->insertTo($detailA, 'T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK', $idT['id']);
                        //     $this->insertTo($detailB, 'T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', $idT['id'], $result['id']);
                        


                        $this->CI->db->trans_commit();
                        return $IDBARU;
                    } else {
                        $id = $value['ID_LHKPN'];
                       
                        $this->CI->db->trans_rollback();
                        return false;
                    }
                     // $this->loadAdminduk($IDBARU);
                }
            }
        }
    }

    /*
     * @todo ambil data terahir belum benar
     *
     *
     * */
    private function check_tahun_terakhir($pn, $tahun)
    {
        $where = ['ID_PN' => $pn, 'year(TGL_LAPOR) <=' => $tahun, 'IS_ACTIVE' => '1'];
        // $where      = ['ID_PN' => $pn, 'year(TGL_LAPOR)' => $tahun, 'IS_ACTIVE' => '1'];

        $data = $this->CI->mglobal->get_data_all_array('T_LHKPN', NULL, $where, '*', "STATUS != '0'", ['TGL_LAPOR', 'desc'], 0, 1);
        if (empty($data)) {
            return false;
        } else {
            $res['data'] = $data;
            $res['tahun'] = $tahun;

            return $res;
        }
    }
    private function loadwskeluarga($id) {
        $dt_ws = 'http://localhost/lhkpn/file/json/pn_keluarga.json';
        $json = file_get_contents($dt_ws);
        $json_kel = json_decode($json, true);

        if ($json_kel) {
            foreach ($json_kel as $kel_js) {
                $arr_keluarga = array(
                    'ID_LHKPN' => $id,
                    'NAMA' => $kel_js['nama_lengkap'],
                    'NIK' => $kel_js['NIK'],
                    'HUBUNGAN' => $kel_js['hubungan'],
                    'TEMPAT_LAHIR' => $kel_js['tempat_lahir'],
                    'TANGGAL_LAHIR' => $kel_js['tanggal_lahir'],
                    'JENIS_KELAMIN' => $kel_js['jenis_kelamin'],
                    'PEKERJAAN' => $kel_js['pekerjaan'],
                    'ALAMAT_RUMAH' => $kel_js['alamat_rumah'],
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' =>$this->CI->session->userdata('USERNAME'),
                    'CREATED_IP' => $this->CI->input->ip_address()
                );
                // $this->db->insert('t_lhkpn_keluarga', $arr_keluarga);
                $this->CI->mglobal->insert('T_LHKPN_KELUARGA', $arr_keluarga);
            }
        }
    }

    private function loadAdminduk($id) {
        $dt_ws = 'http://localhost/lhkpn/file/json/pn_pribadi.json';
        $json = file_get_contents($dt_ws);
        $json_pn = json_decode($json, true);
        $NIK_LOG = $this->CI->session->userdata('NIK');

        if ($json_pn) {
            foreach ($json_pn as $pn_js) {
                if ($pn_js['NIK'] == $NIK_LOG) {
                    $arr_pn = array(
                        'NAMA_LENGKAP' => $pn_js['nama_lengkap'],
                        'AGAMA' => $pn_js['Agama'],
                        'TEMPAT_LAHIR' => $pn_js['tempat_lahir'],
                        'TANGGAL_LAHIR' => $pn_js['tanggal_lahir'],
                        'JENIS_KELAMIN' => $pn_js['jenis_kelamin'],
                        'NEGARA' => $pn_js['Negara'],
                        'PROVINSI' => $pn_js['provinsi'],
                        'KABKOT' => $pn_js['kabupaten_kota'],
                        'KECAMATAN' => $pn_js['kecamatan'],
                        'KELURAHAN' => $pn_js['kelurahan_desa'],
                        'ALAMAT_RUMAH' => $pn_js['alamat_rumah']
                    );
                    $where = ['ID_LHKPN' => $id];
                    // $this->CI->db->where('ID_LHKPN', $IDBARU);
                    $this->CI->mglobal->update('t_lhkpn_data_pribadi', $arr_pn, $where, NULL);
                }
            }
        }
    }


    private function getdatakeluarga($id)
    {
        $data = $this->CI->mglobal->get_data_all_array('T_LHKPN_KELUARGA', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahutang($id)
    {
        $data = $this->CI->mglobal->get_data_all_array('T_LHKPN_HUTANG', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatapribadi($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_DATA_PRIBADI', NULL, ['ID_LHKPN' => $id]);
        return $data;
    }

    private function getdatahartabergerak($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_HARTA_BERGERAK', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0']);
        foreach($data as $key => $row){
            $data[$key]['detail'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK', NULL, ['ID_HARTA' => $row['ID']]);
            $data[$key]['pelepasan'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_PELEPASAN_HARTA_BERGERAK', NULL, ['ID_HARTA' => $row['ID']]);
        }

        return $data;
    }

    private function getdatahartabergeraklain($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0']);
        foreach($data as $key => $row){
            $data[$key]['detail'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_BERGERAK_LAIN', NULL, ['ID_HARTA' => $row['ID']]);
            $data[$key]['pelepasan'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_PELEPASAN_HARTA_BERGERAK_LAIN', NULL, ['ID_HARTA' => $row['ID']]);
        }
        return $data;
    }

    private function getdatahartakas($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_HARTA_KAS', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0']);
        foreach($data as $key => $row){
            $data[$key]['detail'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_ASAL_USUL_PELEPASAN_KAS', NULL, ['ID_HARTA' => $row['ID']]);
            $data[$key]['pelepasan'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_PELEPASAN_HARTA_KAS', NULL, ['ID_HARTA' => $row['ID']]);
        }
        return $data;
    }

    private function getdatahartalainnya($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_HARTA_LAINNYA', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0']);
        foreach($data as $key => $row){
            $data[$key]['detail'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_LAINNYA', NULL, ['ID_HARTA' => $row['ID']]);
            $data[$key]['pelepasan'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_PELEPASAN_HARTA_LAINNYA', NULL, ['ID_HARTA' => $row['ID']]);
        }
        return $data;
    }

    private function getdatahartasuratberharga($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0']);
        foreach($data as $key => $row){
            $data[$key]['detail'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_ASAL_USUL_PELEPASAN_SURAT_BERHARGA', NULL, ['ID_HARTA' => $row['ID']]);
            $data[$key]['pelepasan'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_PELEPASAN_HARTA_SURAT_BERHARGA', NULL, ['ID_HARTA' => $row['ID']]);
        }
        return $data;
    }

    private function getdatahartatidakbergerak($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1', 'IS_PELEPASAN' => '0']);
        foreach($data as $key => $row){
            $data[$key]['detail'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_ASAL_USUL_PELEPASAN_HARTA_TIDAK_BERGERAK', NULL, ['ID_HARTA' => $row['ID']]);
            $data[$key]['pelepasan'] = $this->CI->mglobal->get_data_all_array('T_LHKPN_PELEPASAN_HARTA_TIDAK_BERGERAK', NULL, ['ID_HARTA' => $row['ID']]);
        }
        return $data;
    }

    private function getdatajabatan($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_JABATAN', NULL, ['ID_LHKPN' => $id, 'ID_STATUS_AKHIR_JABAT' => '0']);
        return $data;
    }

    private function getdatapenerimaan($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_PENERIMAAN_KAS', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatapengeluaran($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_PENGELUARAN_KAS', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatafasilitas($id){
        $data   = $this->CI->mglobal->get_data_all_array('T_LHKPN_FASILITAS', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    /**
     * @param $value
     * @return array
     */
    private function isExistArray($array, $value)
    {
        $detailA = '';
        if (isset($array[$value])) {
            $detailA = $array[$value];
            unset($array[$value]);
            return array($array, $detailA);
        }else{

        }

        return array($array, $detailA);
    }

    private function insertTo($array, $table, $id, $id_lhkpn = NULL)
    {
        if (isset($array)) {
            foreach ($array as $rows) {
                unset($rows['ID']);
                $rows['ID_HARTA'] = $id;
                if(!is_null($id_lhkpn)) {
                    $rows['ID_LHKPN'] = $id_lhkpn;
                }
                $this->CI->mglobal->insert($table, $rows);
            }
        }
    }
}