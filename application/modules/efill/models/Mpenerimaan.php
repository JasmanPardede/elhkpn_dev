<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mpenerimaan extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function from_copylhkpn_LHKPN_lib($pn = '0', $tahun = '2015', $entryvia = '1', $type = '') {
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

        if (!empty($cekPn)) {
            $this->CI->db->trans_rollback();
            return false;
        } else {
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
                $data['STATUS'] = '0';

                $result = $this->CI->mglobal->insert('T_LHKPN', $data);

                $id = $result['id'];
                $reckel = $this->CI->mglobal->get_data_all('T_LHKPN', NULL, ['ID_PN' => $pn])[0];
                $IDLHKPNLAMA = $reckel->ID_LHKPN;

                $detailkel = $this->CI->mglobal->get_data_all('t_lhkpn_keluarga', NULL, ['ID_LHKPN' => $IDLHKPNLAMA]);
                if ($detailkel) {
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
                } else {
                    $this->loadwskeluarga($id);
                }

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

                $this->CI->mglobal->insert('T_LHKPN_DATA_PRIBADI', $data);

                $htngJab = $this->CI->mglobal->get_data_all('T_PN_JABATAN', NULL, ['ID_PN' => $pn], 'COUNT(ID) AS JML')[0];
                $htjab1 = $this->CI->mglobal->get_data_all('T_PN_JABATAN', NULL, ['ID_PN' => $pn, 'IS_CURRENT' => 0], 'COUNT(ID) AS JML')[0];
                if ($htjab1 == $htngJab) {
                    $where = ['ID_PN' => $pn, 'IS_CURRENT' => 0];
                    $order = ['ID', 'DESC'];
                    $limit = '1';
                } else {
                    $where = ['ID_PN' => $pn, 'IS_CURRENT' => 1, 'ID_STATUS_AKHIR_JABAT' => '0'];
                    $order = NULL;
                    $limit = NULL;
                }
                $jab = $this->CI->mglobal->get_data_all('T_PN_JABATAN', NULL, $where, '*', NULL, $order, NULL, $limit);


                foreach ($jab as $row) {

                    $this->CI->db->like('UK_NAMA', $row->UNIT_KERJA);
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
                    $value['STATUS'] = '0';
                    $value['IS_ACTIVE'] = 1;
                    $value['IS_COPY'] = '1';
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

                        $detail['JABATAN'] = $this->getdatajabatan($id);
                        foreach ($detail['JABATAN'] as $key => $value) {
                            unset($value['ID']);
                            $value['ID_LHKPN'] = $result['id'];
                            $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                            $value['CREATED_BY'] = $this->username;
                            $value['CREATED_IP'] = $this->CI->input->ip_address();
                            $value['UPDATED_TIME'] = '';
                            $value['UPDATED_BY'] = '';
                            $value['UPDATED_IP'] = '';

                            $this->CI->mglobal->insert('T_LHKPN_JABATAN', $value);
                        }

                        $this->CI->db->trans_commit();
                        return $IDBARU;
                    } else {
                        $id = $value['ID_LHKPN'];

                        $this->CI->db->trans_rollback();
                        return false;
                    }
                }
            }
        }
    }

    public function get_lhkpn($id_lhkpn)
    {
        $result = false;
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('IS_ACTIVE', 1);
        $lhkpn = $this->db->get('t_lhkpn')->row();
        $result = $lhkpn ?: false;

        return $result;
    }

    public function is_exists_penerimaan1($id_lhkpn, $kode_jenis = null)
    {
        $result = false;
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('IS_ACTIVE', 1);
        if ($kode_jenis) {
            $this->db->like('NILAI_PENERIMAAN_KAS_PN', '{"'.$kode_jenis.'"'); // where like '%{"kode_jenis:"%'
        }
        $penerimaan1 = $this->db->get('t_lhkpn_penerimaan_kas')->result();

        if ($penerimaan1) {
            $result = $penerimaan1;
        }

        return $result;
    }

    public function is_exists_penerimaan2($id_lhkpn, $kode_jenis = null)
    {
        $result = false;
        $this->db->where('ID_LHKPN', $id_lhkpn);
        if ($kode_jenis) {
            $this->db->where('KODE_JENIS', $kode_jenis);
        }
        $pengeluaran2 = $this->db->get('t_lhkpn_penerimaan_kas2')->result();
        
        if ($pengeluaran2) {
            $result = $pengeluaran2;
        }

        return $result;
    }

    public function is_exists($id_lhkpn)
    {
        $result = false;
        $kas = $this->is_exists_penerimaan1($id_lhkpn);
        $kas2 = $this->is_exists_penerimaan2($id_lhkpn);

        if ($kas && $kas2) {
            $result = [
                'kas' => $kas,
                'kas2' => $kas2
            ];
        }

        return $result;
    }

    public function get_penerimaan($id_lhkpn)
    {
        $this->db->join(
            'm_jenis_penerimaan_kas jenis_kas',
            'jenis_kas.nama = kas2.jenis_penerimaan',
            'left'
        );
        $this->db->join(
            't_lhkpn lhkpn',
            "lhkpn.ID_LHKPN = kas2.ID_LHKPN and lhkpn.ID_PN = '".$this->session->userdata('ID_PN')."'"
        );
        $this->db->where('jenis_kas.IS_ACTIVE', '1');
        $this->db->where('kas2.ID_LHKPN', $id_lhkpn);
        $data = $this->db->get('t_lhkpn_penerimaan_kas2 kas2')->result();

        //get lhkpn
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $lhkpn = $this->db->get('t_lhkpn')->row();

        //sum harta tidak bergerak
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual1 = $this->db->get('t_lhkpn_pelepasan_harta_tidak_bergerak')->row();

        //sum harta bergerak
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual2 = $this->db->get('t_lhkpn_pelepasan_harta_bergerak')->row();

        //sum harta bergerak lainnya
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual3 = $this->db->get('t_lhkpn_pelepasan_harta_bergerak_lain')->row();

        //sum harta surat berharga
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual4 = $this->db->get('t_lhkpn_pelepasan_harta_surat_berharga')->row();

        //sum harta lainnya
        $this->db->select_sum('NILAI_PELEPASAN');
        $this->db->where('ID_LHKPN', $id_lhkpn);
        $this->db->where('JENIS_PELEPASAN_HARTA', 4);
        $this->db->like('TANGGAL_TRANSAKSI', date('Y', strtotime($lhkpn->tgl_lapor)));
        $sum_jual5 = $this->db->get('t_lhkpn_pelepasan_harta_lainnya')->row();

        // total jual
        $total_jual = $sum_jual1->NILAI_PELEPASAN;
        $total_jual += $sum_jual2->NILAI_PELEPASAN;
        $total_jual += $sum_jual3->NILAI_PELEPASAN;
        $total_jual += $sum_jual4->NILAI_PELEPASAN;
        $total_jual += $sum_jual5->NILAI_PELEPASAN;
        
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $temp = $this->db->get('m_jenis_penerimaan_kas')->result();

        $jml = array();
        $total = 0;
        $i = 1;
        foreach ($temp as $t) {
            $this->db->select('SUM(PN+PASANGAN) AS JML', false);
            $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $id_lhkpn);
            $this->db->where('GROUP_JENIS', $this->golongan($t->GOLONGAN));
            $temp2 = $this->db->get('t_lhkpn_penerimaan_kas2')->row();

            if ($temp2->JML) {
                $jml['SUM_' . $this->golongan($t->GOLONGAN)] = $temp2->JML;
            } else {
                $jml['SUM_' . $this->golongan($t->GOLONGAN)] = 0;
            }

            $i++;
        }
        $jml['SUM_A'] = $jml['SUM_A'] ?: 0;
        $jml['SUM_B'] = $jml['SUM_B'] ? $jml['SUM_B'] : ($total_jual ?: 0);
        $jml['SUM_C'] = $jml['SUM_C'] ?: 0;

        $jml['SUM_ALL'] = $jml['SUM_A'] + $jml['SUM_B'] + $jml['SUM_C'];
        $jml['SUM_B3'] = $total_jual;
        $perolehan = [
            'htb' => $sum_jual1->NILAI_PELEPASAN,
            'hb' => $sum_jual2->NILAI_PELEPASAN,
            'hbl' => $sum_jual3->NILAI_PELEPASAN,
            'hsb' => $sum_jual4->NILAI_PELEPASAN,
            'hl' => $sum_jual5->NILAI_PELEPASAN,
        ];
        $result_data = [
            'list' => $data,
            'sum' => $jml,
            'perolehan' => $perolehan
        ];

		return $result_data;
    }

    /**
     * create data ke t_lhkpn_penerimaan_kas
     */
    public function create_kas($id_lhkpn)
    {
        $result = false;

        $this->db->select('GOLONGAN');
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $data = $this->db->get('m_jenis_penerimaan_kas')->result();

        $PN = [];
        $PASANGAN = [];
        foreach ($data as $row) {
            $this->db->where('GOLONGAN', $row->GOLONGAN);
            $this->db->where('IS_ACTIVE','1');
            $value = $this->db->get('m_jenis_penerimaan_kas')->result();
            $i = 0;
            $index = $this->golongan($row->GOLONGAN);
            foreach ($value as $rs) {
                $PN[$index][] = [
                    $this->golongan($rs->GOLONGAN) . '' . $i => 0
                ];
                if ($rs->GOLONGAN == 1) {
                    $PASANGAN[] = [
                        'P' . $this->golongan($rs->GOLONGAN) . '' . $i => 0
                    ];
                }
                $i++;
            }
        }

        $data_kas = array(
            'ID_LHKPN' => $id_lhkpn,
            'NILAI_PENERIMAAN_KAS_PN' => json_encode($PN),
            'NILAI_PENERIMAAN_KAS_PASANGAN' => json_encode($PASANGAN),
            'IS_ACTIVE' => 1,
            'CREATED_TIME' => date("Y-m-d H:i:s"),
            'CREATED_BY' => $this->session->userdata('NAMA'),
            'CREATED_IP' => get_client_ip(),
            'UPDATED_TIME' => date("Y-m-d H:i:s"),
            'UPDATED_BY' => $this->session->userdata('NAMA'),
            'UPDATED_IP' => get_client_ip(),
        );
        
        $save_kas = $this->db->insert('t_lhkpn_penerimaan_kas', $data_kas);
        
        if ($save_kas) {
            $result = true;
        }

        return $result;
    }

    /**
     * create data ke t_lhkpn_penerimaan_kas2
     */
    public function create_kas2($id_lhkpn)
    {
        $result = false;

        $this->db->select('GOLONGAN');
        $this->db->where('IS_ACTIVE','1');
        $this->db->group_by('GOLONGAN');
        $this->db->order_by('GOLONGAN');
        $data = $this->db->get('m_jenis_penerimaan_kas')->result();

        $PN = [];
        $PASANGAN = [];
        $data_kas2 = [];
        foreach ($data as $row) {
            $this->db->where('GOLONGAN', $row->GOLONGAN);
            $this->db->where('IS_ACTIVE','1');
            $value = $this->db->get('m_jenis_penerimaan_kas')->result();
            $i = 0;
            $index = $this->golongan($row->GOLONGAN);
            foreach ($value as $rs) {
                $PN[$index][] = [
                    $this->golongan($rs->GOLONGAN) . '' . $i => 0
                ];
                if ($rs->GOLONGAN == 1) {
                    $PASANGAN[] = [
                        'P' . $this->golongan($rs->GOLONGAN) . '' . $i => 0
                    ];
                }
                $data_kas2[] = [
                    'ID_LHKPN' => $id_lhkpn,
                    'GROUP_JENIS' => $this->golongan($rs->GOLONGAN),
                    'KODE_JENIS' => $this->golongan($rs->GOLONGAN) . '' . $i,
                    'JENIS_PENERIMAAN' => $rs->NAMA,
                    'PN' => 0,
                    'PASANGAN' => 0
                ];
                $i++;
            }
        }
        
        $save_kas2 = $this->db->insert_batch('t_lhkpn_penerimaan_kas2', $data_kas2);
        
        if ($save_kas2) {
            $result = true;
        }

        return $result;
    }

    /**
     * akumulasi ulang Penerimaan PN (kode_jenis = B3)
     * dengan kondisi (Status Harta = Status Baru
     * Tahun perolehan = Tahun pelaporan
     * Asal-usul = Hasil Sendiri)
     */
    public function re_acumulate($id_lhkpn)
    {
        $result = false;
        $lhkpn = $this->get_lhkpn($id_lhkpn);
        $is_exists = $this->is_exists($id_lhkpn);

        if ($lhkpn && $is_exists) {
            $penerimaan = $this->get_penerimaan($id_lhkpn);
            $sum_b3 = $penerimaan['sum']['SUM_B3'];
            $nilai_penerimaan_kas2 = [];

            foreach ($penerimaan['list'] as $list) {
                $penerimaan_pn = $list->KODE_JENIS === 'B3'
                    ? $sum_b3
                    : $list->PN;
                $nilai_penerimaan_kas2[$list->GROUP_JENIS][] = [
                    $list->KODE_JENIS => number_format($penerimaan_pn,0,",",".")
                ];
            }
            
            // update penerimaan kas
            $this->db->where([
                'ID_LHKPN' => $id_lhkpn,
                'IS_ACTIVE' => 1
            ]);
            $update_kas1 = $this->db->update(
                't_lhkpn_penerimaan_kas',
                [
                    'NILAI_PENERIMAAN_KAS_PN' => json_encode($nilai_penerimaan_kas2),
                    'UPDATED_BY' => $this->session->userdata('NAMA'),
                    'UPDATED_TIME' => date('Y-m-d H:i:s'),
                    'UPDATED_IP' => $this->input->ip_address()
                ]
            );

            // update penerimaan kas2
            $this->db->where([
                'ID_LHKPN' => $id_lhkpn,
                'KODE_JENIS' => 'B3'
            ]);
            $update_kas2 = $this->db->update(
                't_lhkpn_penerimaan_kas2',
                [
                    'PN' => $sum_b3
                ]
            );
            
            if ($update_kas1 && $update_kas2) {
                $result = $penerimaan;
            } else {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * create kas && kas2 if not exists
     * and
     * do re_acumulate
     */
    public function create_or_update($id_lhkpn)
    {
        $result = true;
        $is_exists_kas = $this->is_exists_penerimaan1($id_lhkpn);
        $is_exists_kas2 = $this->is_exists_penerimaan2($id_lhkpn);

        //do create kas && kas 2 if not exists
        if (!$is_exists_kas || !$is_exists_kas2) {
            if (!$is_exists_kas) {
                $create_kas = $this->create_kas($id_lhkpn);
                if (!$create_kas) {
                    $result = false;
                }
            }
            if (!$is_exists_kas2) {
                $create_kas2 = $this->create_kas2($id_lhkpn);
                if (!$create_kas2) {
                    $result = false;
                }
            }
        }

        // do re_acumulate
        $acumulate = $this->re_acumulate($id_lhkpn);
        if (!$acumulate) {
            $result = false;
        }

        return $result;
    }

    public function golongan($index){
		$data = array();
		$data['1'] = 'A';
		$data['2'] = 'B';
		$data['3'] = 'C';
		$data['4'] = 'D';
		$data['5'] = 'E';
		$data['6'] = 'F';
		$data['7'] = 'G';
		$data['8'] = 'H';
		$data['9'] = 'I';
		$data['10'] = 'J';
		$data['11'] = 'K';
		$data['12'] = 'L';
		$data['13'] = 'M';
		$data['14'] = 'N';
		$data['15'] = 'O';
		$data['16'] = 'P';
		$data['17'] = 'Q';
		$data['18'] = 'R';
		$data['19'] = 'S';
		$data['20'] = 'T';
		$data['21'] = 'U';
		$data['22'] = 'V';
		$data['23'] = 'W';
		$data['24'] = 'X';
		$data['25'] = 'Y';
		$data['26'] = 'Z';
		return $data[$index];exit;
	}
}
