<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller Masterdata
 * 
 * @author Gunaones - SKELETON-2015 - PT.Mitreka Solusi Indonesia 
 * @package Admin/Controllers/Masterdata
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Masterdata extends Nglibs {

    protected $jabatan_called = FALSE;
    protected $agama_called = FALSE;
    protected $pendidikan_called = FALSE;
    protected $provinsi_called = FALSE;
    protected $matauang_called = FALSE;
    protected $bantuan_called = FALSE;
    protected $petunjuk_called = FALSE;
    protected $kabupaten_called = FALSE;
    protected $instansi_called = FALSE;
    protected $unitkerja_called = FALSE;
    protected $subunitkerja_called = FALSE;
    protected $asal_usul_called = FALSE;
    protected $pemanfaatan_called = FALSE;
    protected $golonganharta_called = FALSE;
    protected $jenis_bukti_called = FALSE;
    protected $jenis_pelepasanharta_called = FALSE;
    protected $jenispenerimaankas_called = FALSE;
    protected $jenispengeluarankas_called = FALSE;
    protected $eselon_called = FALSE;
    private $__skip_function_when = [
        "jabatan_called",
        "agama_called",
        "pendidikan_called",
        "provinsi_called",
        "matauang_called",
        "bantuan_called",
        "petunjuk_called",
        "kabupaten_called",
        "instansi_called",
        "unitkerja_called",
        "subunitkerja_called",
        "asal_usul_called",
        "pemanfaatan_called",
        "golonganharta_called",
        "jenis_bukti_called",
        "jenis_pelepasanharta_called",
        "jenispenerimaankas_called",
        "jenispengeluarankas_called",
        "eselon_called",
    ];

    public function __construct() {
        parent::__construct();
        $this->makses->initialize();
        $this->makses->check_is_read();
        $this->load->model('mglobal');
        $this->load->model('mjabatan');
        $this->load->model('magama');
        $this->load->model('mpendidikan');
        $this->load->model('mprovinsi');
        $this->load->model('mmatauang');
        $this->load->model('mbantuan');
        $this->load->model('mpetunjuk');
        $this->load->model('mkabupaten');
        $this->load->model('minstansi');
        $this->load->model('munitkerja');
        $this->load->model('msubunitkerja');
        $this->load->model('mrasalusul');
        $this->load->model('mrpemanfaatan');
        $this->load->model('mgolonganharta');
        $this->load->model('mjenisbukti');
        $this->load->model('mjenispelepasanharta');
        $this->load->model('mjenispenerimaankas');
        $this->load->model('mjenispengeluarankas');
        $this->load->model('meselon');
    }

    /**
     * Masterdata Agama
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function agama($type = '', $id = '') {
        $this->agama_called = TRUE;
        $this->data['tbl'] = 'M_AGAMA';
        $this->data['pk'] = 'ID_AGAMA';
        if ($type == 'list') {

//            $this->db->select('*');
//            $this->db->from('M_AGAMA');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_AGAMA.AGAMA', $this->CARI['TEXT']);
//            }
//
//            if (@$this->CARI['IS_ACTIVE'] == 99) {
//                // all
//            } else if (@$this->CARI['IS_ACTIVE'] == -1) {
//                // deleted
//                $this->db->where('IS_ACTIVE', -1);
//            } else if (@$this->CARI['IS_ACTIVE']) {
//                // by status
//                $this->db->where('IS_ACTIVE', $this->CARI['IS_ACTIVE']);
//            } else {
//                // active
//                $this->db->where('IS_ACTIVE', 1);
//            }
//
//            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
//            $filterCetakItem['Tanggal'] = @$this->CARI['TEXT'];
//            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            // $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'AGAMA' => $this->input->post('AGAMA', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Data Agama, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'AGAMA' => $this->input->post('AGAMA', TRUE),
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Agama = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Agama, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Agama, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Asal Usul
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function asal_usul($type = '', $id = '') {
        $this->asal_usul_called = TRUE;
        $this->data['tbl'] = 'M_ASAL_USUL';
        $this->data['pk'] = 'ID_ASAL_USUL';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_ASAL_USUL');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_ASAL_USUL.ASAL_USUL', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'ASAL_USUL' => $this->input->post('ASAL_USUL', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Asal Usul, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'ASAL_USUL' => $this->input->post('ASAL_USUL', TRUE),
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Asal Usul, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Asal Usul, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Asal Usul Harta, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Golongan Harta
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function golonganharta($type = '', $id = '') {
        $this->load->model('mgolonganharta');
        $this->data['tbl'] = 'M_GOLONGAN_HARTA';
        $this->data['pk'] = 'ID_GOLONGAN_HARTA';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_GOLONGAN_HARTA');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_GOLONGAN_HARTA.NAMA_GOLONGAN', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'NAMA_GOLONGAN' => $this->input->post('NAMA_GOLONGAN', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Golongan Harta, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'NAMA_GOLONGAN' => $this->input->post('NAMA_GOLONGAN', TRUE),
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Golongan Harta, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Golongan Harta, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Golongan Harta, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Bidang
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function bidang($type = '', $id = '') {
        $this->data['tbl'] = 'M_BIDANG';
        $this->data['pk'] = 'BDG_ID';

        if ($type == 'list') {
            $this->db->select('*');
            $this->db->from('M_BIDANG');
            //$this->db->where('1=1', null, false);

            if (@$this->CARI['TEXT']) {
                $this->db->like('M_BIDANG.BDG_NAMA', $this->CARI['TEXT']);
            }

            if (@$this->CARI['BDG_STATUS'] == '') {
                // nonactive
            } else if (@$this->CARI['BDG_STATUS'] == '0') {
                // active
                $this->db->where('BDG_STATUS', '0');
            } else {
                $this->db->where('BDG_STATUS', '1');
            }

            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
            $filterCetakItem['Tanggal'] = @$this->CARI['TEXT'];
            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'BDG_KODE' => $this->input->post('BDG_KODE', TRUE),
                    'BDG_NAMA' => $this->input->post('BDG_NAMA', TRUE),
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Agama, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'BDG_KODE' => $this->input->post('BDG_KODE', TRUE),
                    'BDG_NAMA' => $this->input->post('BDG_NAMA', TRUE),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Agama, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {

                $data = array(
                    'IS_ACTIVE' => -1,
//                    'UPDATED_TIME' => date('Y-m-d H:i:s'),/time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);


                $this->db->update($this->data['tbl'], $data);
                ng::logActivity('Hapus Data Agama, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Instansi
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function instansi($type = '', $id = '') {
        $this->instansi_called = TRUE;
        $this->data['tbl'] = 'M_INST_SATKER';
        $this->data['pk'] = 'INST_SATKERKD';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_INST_SATKER');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_INST_SATKER.INST_NAMA', $this->CARI['TEXT']);
//                $this->db->or_like('M_INST_SATKER.INST_AKRONIM', $this->CARI['TEXT']);
//            }
//
//
////            if (@$this->CARI['IS_ACTIVE'] == '') {
////                // nonactive
////            } else if (@$this->CARI['IS_ACTIVE'] == '0') {
////                // active
////                $this->db->where('IS_ACTIVE', '0');
////            } else {
////                $this->db->where('IS_ACTIVE', '1');
////            }
//
//            $this->configure_pencarian_active();
//            
//            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
//            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
        } else if ($type == 'edit') {

            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
            //$this->data['prov'] = $id ? (object) $this->mglobal->get_data_all_array('m_inst_satker_area', NULL, [$this->data['pk'] => $id])[0] : '';
// if($id==0){
            // 	$this->data['item'] = $id==0?(object)$this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0]:'';
            // }
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {

            //tik
            //$this->load->model('minstansi', '', TRUE);
            if ($this->input->post('INST_LEVEL', TRUE) == 1) {
                $prov = null;
                $kab = null;
            } else if ($this->input->post('INST_LEVEL', TRUE) == 2) {
                $prov = $this->input->post('ID_PROV', TRUE);
                $kab = null;
            } else if ($this->input->post('INST_LEVEL', TRUE) == 3) {
                $prov = $this->input->post('ID_PROV', TRUE);
                $kab = $this->input->post('ID_KAB', TRUE);
            }

            if ($this->act == 'doinsert') {
                $data = array(
                    'INST_SATKERKD' => $this->input->post('INST_SATKERKD', TRUE),
                    'INST_BDG_ID' => $this->input->post('INST_BDG_ID', TRUE),
                    'INST_NAMA' => $this->input->post('INST_NAMA', TRUE),
                    'INST_AKRONIM' => $this->input->post('INST_AKRONIM', TRUE),
                    'INST_LEVEL' => $this->input->post('INST_LEVEL', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                    'ID_PROV' => $prov, //$this->input->post('ID_PROV', TRUE),
                    'ID_KAB' => $kab, //$this->input->post('ID_KAB', TRUE),
                );
                //tik
                //$id = $this->minstansi->save($instansi);
                $this->db->insert($this->data['tbl'], $data);
                //tik
                //ng::logActivity('Tambah Data Instansi, id = ' . $id);
                ng::logActivity('Tambah Data Instansi, id = ' . $this->db->insert_id());

//                tik
//                if ($instansi['INST_LEVEL'] == 2) {
//                    $dt_add = array('INST_SATKERKD' => $id,
//                        'ID_PROV' => $this->input->post('ID_PROV'));
//                    $this->mglobal->save_area($dt_add);
//                }
            } else if ($this->act == 'doupdate') {

                $data = array(
                    'INST_SATKERKD' => $this->input->post('INST_SATKERKD', TRUE),
                    'INST_BDG_ID' => $this->input->post('INST_BDG_ID', TRUE),
                    'INST_NAMA' => $this->input->post('INST_NAMA', TRUE),
                    'INST_AKRONIM' => $this->input->post('INST_AKRONIM', TRUE),
                    'INST_LEVEL' => $this->input->post('INST_LEVEL', TRUE),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    'ID_PROV' => $prov, // $this->input->post('ID_PROV', TRUE),
                    'ID_KAB' => $kab, //$this->input->post('ID_KAB', TRUE),
                );

                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);
                ng::logActivity('Edit Data Instansi = ' . $data[$this->data['pk']]);

//                tik
//                $instansi['INST_SATKERKD'] = $this->input->post('INST_SATKERKD', TRUE);
//                $this->minstansi->update($instansi['INST_SATKERKD'], $instansi);
//                ng::logActivity('Edit Data Instansi, id = ' . $instansi['INST_SATKERKD']);
                //Jika INST_LEVEL == 2
//                tik
//                if ($instansi['INST_LEVEL'] == 2) {
//                    //Cek tabel m_inst_satker_area
//                    $cek = $this->mglobal->cek_inst_satkerkd($instansi['INST_SATKERKD']);
//                    // jika belum maka add
//                    if ($cek == false) {
//                        $dt_add = array('INST_SATKERKD' => $instansi['INST_SATKERKD'],
//                            'ID_PROV' => $this->input->post('ID_PROV'));
//                        $this->mglobal->save_area($dt_add);
//                    } else {
//                        //jika sudah ada, maka update
//                        $dt_edit = array('INST_SATKERKD' => $instansi['INST_SATKERKD'],
//                            'ID_PROV' => $this->input->post('ID_PROV'));
//                        $this->mglobal->update_area($dt_edit['INST_SATKERKD'], $dt_edit);
//                    }
//                }
//                tik
//                if ($instansi['INST_LEVEL'] == 1) {
//                    $cek = $this->mglobal->cek_inst_satkerkd($instansi['INST_SATKERKD']);
//                    if ($cek != false) {
//                        $this->mglobal->delete_area($instansi['INST_SATKERKD']);
//                    }
//                }
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Instansi, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    // 'CREATED_TIME'     => time(),
                    // 'CREATED_BY'     => $this->session->userdata('USR'),
                    // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );

                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Instansi, id = ' . $data[$this->data['pk']]);


//                tik
//                $instansi['INST_SATKERKD'] = $this->input->post('INST_SATKERKD', TRUE);
//                $this->minstansi->update($instansi['INST_SATKERKD'], $instansi);
//
//                ng::logActivity('Hapus Data Instansi, id = ' . $instansi['INST_SATKERKD']);
            }
        } else if ($type == 'reff') {
            $this->data['bidang'] = $this->mglobal->get_data_all('M_BIDANG');
        }
    }

    /**
     * Masterdata Unit Kerja
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function unitkerja($type = '', $id = '') {
        $this->unitkerja_called = TRUE;
        $this->data['tbl'] = 'M_UNIT_KERJA';
        $this->data['pk'] = 'UK_ID';
        if ($type == 'list') {
            $types_of_admin = $this->check_type_of_admin();
			$this->data = array_merge($this->data, $types_of_admin);
			$data_js = $this->data;
			$this->data['on_another_module'] = TRUE;
			$this->data['showUnitKerja'] = FALSE;
			$this->data['v_cb_instansi'] = $this->load->view('v_daftar_pnwl_xl/cb_instansi', $data_js, TRUE, 'ereg');
			$this->data['js_page'][] = $this->load->view('v_daftar_pnwl_xl/js/cb_instansi_js', $data_js, TRUE, 'ereg');
            
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') { 
            if ($this->input->post('INST_LEVEL', TRUE) == 1) {
                $prov = null;
                $kab = null;
            } else if ($this->input->post('INST_LEVEL', TRUE) == 2) {
                $prov = $this->input->post('ID_PROV', TRUE);
                $kab = null;
            } else if ($this->input->post('INST_LEVEL', TRUE) == 3) {
                $prov = $this->input->post('ID_PROV', TRUE);
                $kab = $this->input->post('ID_KAB', TRUE);
            }

            if ($this->act == 'doinsert') {
                $data = array(
                    //'UK_BIDANG_ID' 	 => $this->input->post('UK_BIDANG_ID', TRUE),
                    'UK_LEMBAGA_ID' => $this->input->post('UK_LEMBAGA_ID', TRUE),
                    'UK_NAMA' => $this->input->post('UK_NAMA', TRUE),
                    'UK_STATUS' => 1,
                    'UK_CREATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UK_CREATED_BY' => $this->session->userdata('USR'),
                    'LEVEL' => $this->input->post('INST_LEVEL', TRUE),
                    'ID_PROV' => $prov, //$this->input->post('propinsi', TRUE),
                    'ID_KAB' => $kab, //$this->input->post('kab', TRUE),
                );

//                tik
//                $id = $this->munitkerja->save($unitkerja);
                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Data Instansi, id = ' . $this->db->insert_id());
//                ng::logActivity('Tambah Data Unit Kerja, id = ' . $id);
            } else if ($this->act == 'doupdate') {
                //echo "aws".$this->input->post('PROPINSI', TRUE)."--".$this->input->post('KAB', TRUE)."--".$this->input->post('LEVEL', TRUE);
                $data = array(
                    //'UK_BIDANG_ID' 	=> $this->input->post('UK_BIDANG_ID', TRUE),
                    'UK_LEMBAGA_ID' => $this->input->post('UK_LEMBAGA_ID', TRUE),
                    'UK_NAMA' => $this->input->post('UK_NAMA', TRUE),
//                    'UK_STATUS' => $this->input->post('UK_STATUS', TRUE),
                    'UK_STATUS' => 1,
                    'UK_UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UK_UPDATED_BY' => $this->session->userdata('USR'),
                    'LEVEL' => $this->input->post('INST_LEVEL', TRUE),
                    'ID_PROV' => $prov, //$this->input->post('propinsi', TRUE),
                    'ID_KAB' => $kab, //$this->input->post('kab', TRUE),
                );

//                tik
//                $unitkerja['UK_ID'] = $this->input->post('UK_ID', TRUE);
//                $this->munitkerja->update($unitkerja['UK_ID'], $unitkerja);
//
//                ng::logActivity('Edit Data munitkerja, id = ' . $munitkerja['UK_ID']);

                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);
                ng::logActivity('Edit Data Unit Kerja = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'UK_STATUS' => '0',
                    'UK_UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UK_UPDATED_BY' => $this->session->userdata('USR'),
                );
//                echo "id: ".$this->input->post($this->data['pk'], TRUE);
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Unit Kerja, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'UK_STATUS' => 1,
                    'UK_UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UK_UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Unit Kerja, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata sub unit kerja
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function subunitkerja($type = '', $id = '') {
        $this->subunitkerja_called = TRUE;
        $this->data['tbl'] = 'M_SUB_UNIT_KERJA';
        $this->data['pk'] = 'SUK_ID';
        if ($type == 'list') {
			$types_of_admin = $this->check_type_of_admin();
			$this->data = array_merge($this->data, $types_of_admin);
			$data_js = $this->data;
			$this->data['on_another_module'] = TRUE;
			$this->data['showUnitKerja'] = TRUE;
			$this->data['v_cb_instansi'] = $this->load->view('v_daftar_pnwl_xl/cb_instansi', $data_js, TRUE, 'ereg');
			$this->data['js_page'][] = $this->load->view('v_daftar_pnwl_xl/js/cb_instansi_js', $data_js, TRUE, 'ereg');
            
        } 
		else if ($type == 'edit') {
            $join = [
                //['table' => 'M_INST_SATKER', 'on' => 'M_SUB_UNIT_KERJA.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],
                ['table' => 'M_UNIT_KERJA', 'on' => 'M_SUB_UNIT_KERJA.UK_ID = M_UNIT_KERJA.UK_ID'],
            ];
            $select = 'M_SUB_UNIT_KERJA.*, M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_NAMA, M_UNIT_KERJA.UK_LEMBAGA_ID, M_SUB_UNIT_KERJA.UK_BKDID ';
//            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], $join, [$this->data['pk'] => $id])[0] : '';
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], $join, [$this->data['pk'] => $id], $select)[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {

            if ($this->input->post('LEVEL', TRUE) == 1) {
                $prov = null;
                $kab = null;
            } else if ($this->input->post('LEVEL', TRUE) == 2) {
                $prov = $this->input->post('ID_PROV', TRUE);
                $kab = null;
            } else if ($this->input->post('LEVEL', TRUE) == 3) {
                $prov = $this->input->post('ID_PROV', TRUE);
                $kab = $this->input->post('ID_KAB', TRUE);
            }
//            $this->load->model('msubunitkerja', '', TRUE);

            if ($this->act == 'doinsert') {

                $data = array(
                    // 'SUK_ID' 	=> $this->input->post('SUK_ID', TRUE),
                    'SUK_NAMA' => $this->input->post('SUK_NAMA', TRUE),
                    'UK_ID' => $this->input->post('UNIT_KERJA', TRUE),
                    'UK_STATUS' => 1,
                    'UK_CREATED_ON' => date('Y-m-d'),
                    'UK_CREATED_BY' => $this->session->userdata('USR'),
                    //'CREATED_IP'     	=> $_SERVER["REMOTE_ADDR"],
                    'LEVEL' => $this->input->post('LEVEL', TRUE),
                    'ID_PROV' => $prov, //$this->input->post('propinsi', TRUE),
                    'ID_KAB' => $kab, //$this->input->post('kab', TRUE),
                );
//                $id = $this->msubunitkerja->save($subunitkerja);

                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Data Sub Unit Kerja, id = ' . $this->db->insert_id());
//                ng::logActivity('Tambah Data Sub Unit Kerja, id = ' . $id);
            } else if ($this->act == 'doupdate') {
                $subunitkerja = array(
                    'SUK_ID' => $this->input->post('SUK_ID', TRUE),
                    'SUK_NAMA' => $this->input->post('SUK_NAMA', TRUE),
                    'UK_ID' => $this->input->post('UNIT_KERJA', TRUE),
                    'UK_UPDATED_ON' => date('Y-m-d'),
                    'UK_UPDATED_BY' => $this->session->userdata('USR'),
                    //'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
                    'LEVEL' => $this->input->post('LEVEL', TRUE),
                    'ID_PROV' => $prov, //$this->input->post('propinsi', TRUE),
                    'ID_KAB' => $kab, //$this->input->post('kab', TRUE),
                );
                $instansi['SUK_ID'] = $this->input->post('SUK_ID', TRUE);
                $this->msubunitkerja->update($subunitkerja['SUK_ID'], $subunitkerja);

                ng::logActivity('Edit Data Sub Unit Kerja, id = ' . $subunitkerja['SUK_ID']);
            } else if ($this->act == 'dodelete') {
                $subunitkerja = array(
                    'UK_STATUS' => '0',
                    // 'CREATED_TIME'     => time(),
                    // 'CREATED_BY'     => $this->session->userdata('USR'),
                    // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                    'UK_UPDATED_ON' => date('Y-m-d'),
                    'UK_UPDATED_BY' => $this->session->userdata('USR'),
                        //'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"], 
                );
                $subunitkerja['SUK_ID'] = $this->input->post('SUK_ID', TRUE);
                $this->msubunitkerja->update($subunitkerja['SUK_ID'], $subunitkerja);
                
                ng::logActivity('Hapus Data Sub Unit Kerja, id = ' . $subunitkerja['SUK_ID']);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'UK_STATUS' => 1,
                    'UK_UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UK_UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Sub Unit Kerja, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Jabatan
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    protected function get_param_load_paging_default($load_paging_area = FALSE) {
        $load_paging_area = $load_paging_area ? $load_paging_area : 'default';
        return $this->_param_load_paging_default($load_paging_area);
    }

    function load_data_daftar_master_jabatan($page_mode = NULL) {

        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mjabatan');

        $response = $this->mjabatan->get_daftar_master_jabatan($this->instansi, $currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_golonganharta($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mgolonganharta');

        $response = $this->mgolonganharta->get_daftar_master_golonganharta($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_jenisbukti($page_mode = NULL) {

        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mjenisbukti');

        $response = $this->mjenisbukti->get_daftar_master_jenisbukti($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_jenispelepasanharta($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mjenispelepasanharta');

        $response = $this->mjenispelepasanharta->get_daftar_master_jenispelepasanharta($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_jenispenerimaankas($page_mode = NULL) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');

        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mjenispenerimaankas');

        $response = $this->mjenispenerimaankas->get_daftar_master_jenispenerimaankas($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_eselon($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('meselon');

        $response = $this->meselon->get_daftar_master_eselon($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_agama($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('magama');

        $response = $this->magama->get_daftar_master_agama($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );


        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_asalusul($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mrasalusul');

        $response = $this->mrasalusul->get_daftar_master_asalusul($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );


        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_jenispengeluarankas($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mjenispengeluarankas');

        $response = $this->mjenispengeluarankas->get_daftar_master_jenispengeluarankas($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_pemanfaatan($page_mode = NULL) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mrpemanfaatan');

        $response = $this->mrpemanfaatan->get_daftar_master_pemanfaatan($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_instansi($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('minstansi');

        $response = $this->minstansi->get_daftar_master_instansi($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_kabupaten($page_mode = NULL) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mkabupaten');

        $response = $this->mkabupaten->get_daftar_master_kabupaten($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_unitkerja($page_mode = NULL) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('munitkerja');

        $response = $this->munitkerja->get_daftar_master_unitkerja($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_subunitkerja($page_mode = NULL) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('msubunitkerja');

        $response = $this->msubunitkerja->get_daftar_master_subunitkerja($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_petunjuk($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mpetunjuk');

        $response = $this->mpetunjuk->get_daftar_master_petunjuk($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_bantuan($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mbantuan');

        $response = $this->mbantuan->get_daftar_master_bantuan($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_matauang($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mmatauang');

        $response = $this->mmatauang->get_daftar_master_matauang($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_provinsi($page_mode = NULL) {


        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');


        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mprovinsi');

        $response = $this->mprovinsi->get_daftar_master_provinsi($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    function load_data_daftar_master_pendidikan($page_mode = NULL) {
        list($currentpage, $rowperpage, $keyword, $state_active, $sort) = $this->get_param_load_paging_default();

        if (!$page_mode)
            $page_mode = $this->input->get('page_mode');

        foreach ((array) @$this->input->get('CARI') as $k => $v) {
            $this->CARI["{$k}"] = $this->input->get('CARI')["{$k}"];
        }

        if (!property_exists($this, 'CARI')) {
            $this->CARI = NULL;
        }

        $this->load->model('mpendidikan');
        $response = $this->mpendidikan->get_daftar_master_pendidikan($currentpage, $keyword, $rowperpage, $this->CARI);

        $dtable_output = array(
            "sEcho" => intval($this->input->get("sEcho")),
            "iTotalRecords" => intval($response->total_rows),
            "iTotalDisplayRecords" => intval($response->total_rows),
            "aaData" => $response->result
        );

        $this->to_json($dtable_output);
    }

    protected function beforeQuery($method) {
        $panggil_function = FALSE;
        $method_called = $this->called_class . "_called";

        if (!in_array($method_called, $this->__skip_function_when)) {
            parent::beforeQuery($method);
        }
    }

    protected function afterQuery($sql_feeder = FALSE) {
        $panggil_function = FALSE;
        $method_called = $this->called_class . "_called";

        if (!in_array($method_called, $this->__skip_function_when)) {
            parent::afterQuery();
        }
    }

    protected function is_not_cetak($cetak, $method) {

        $panggil_function = FALSE;
        $method_called = $this->called_class . "_called";

        if (!in_array($method_called, $this->__skip_function_when)) {
            parent::is_not_cetak($cetak, $method);
        }
    }

    public function jabatan($type = '', $id = '') {
        $this->jabatan_called = TRUE;

        $this->data['tbl'] = 'M_JABATAN';
        $this->data['pk'] = 'ID_JABATAN';
        $this->data['eselon'] = $this->mglobal->get_data_all('M_ESELON',NULL,'IS_ACTIVE = 1');
        if ($type == 'list') {
            // prepare query
        } else if ($type == 'edit') {
            $join = [
                // ['table' => 'M_UNIT_KERJA','M_SUB_UNIT_KERJA', 'M_INST_SATKER','on' => 'M_JABATAN.UK_ID = M_UNIT_KERJA.UK_ID', 'M_JABATAN.SUK_ID = M_SUB_UNIT_KERJA.SUK_ID','M_JABATAN.INST_SATKERKD = M_INST_SATKER.INST_SATKERKD'],

                ['table' => 'M_UNIT_KERJA', 'on' => 'M_JABATAN.UK_ID = M_UNIT_KERJA.UK_ID'],
                ['table' => 'M_SUB_UNIT_KERJA', 'on' => 'M_JABATAN.SUK_ID = M_SUB_UNIT_KERJA.SUK_ID']
            ];
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array_new($this->data['tbl'], $join, [$this->data['pk'] => $id])[0] : '';
            $this->data['eselon'] = $this->mglobal->get_data_all('M_ESELON',NULL,'IS_ACTIVE = 1');
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            //$this->load->model('mjabatan', '');

            if ($this->act == 'doinsert') {
                if ($this->input->post('SUB_UNIT_KERJA') == 0) {
                    $jabatan = array(
                        'NAMA_JABATAN' => $this->input->post('NAMA_JABATAN', TRUE),
                        'IS_UU' => $this->input->post('UU', TRUE),
                        'IS_ACTIVE' => 1,
                        'UK_ID' => $this->input->post('UNIT_KERJA', TRUE),
//                        'UK_ID' => $this->input->post('UK_ID', TRUE),
                        'SUK_ID' => NULL,
                        'KODE_ESELON' => $this->input->post('KODE_ESELON', TRUE),
                        'CREATED_TIME' => date('Y-m-d H:i:s'),
                        'CREATED_BY' => $this->session->userdata('USR'),
                        'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                        'INST_SATKERKD' => $this->input->post('INST_TUJUAN', TRUE),
                            // 'UPDATED_TIME'     => time(),
                            // 'UPDATED_BY'     => $this->session->userdata('USR'),
                            // 'UPDATED_IP'     => $_SERVER["REMOTE_ADDR"],                                   
                    );
//                    var_dump("array: ".$jabatan);
                    $this->db->insert($this->data['tbl'], $jabatan);
                    //ng::logActivity('Tambah Data Jabatan, id = '.$id);
                } else if ($this->input->post('SUB_UNIT_KERJA') != 0) {
                    $jabatan = array(
                        'NAMA_JABATAN' => $this->input->post('NAMA_JABATAN', TRUE),
                        'IS_UU' => $this->input->post('UU', TRUE),
                        'IS_ACTIVE' => 1,
                        'UK_ID' => $this->input->post('UNIT_KERJA', TRUE),
//                        'UK_ID' => $this->input->post('UK_ID', TRUE),
                        'SUK_ID' => $this->input->post('SUB_UNIT_KERJA', TRUE),
//                        'SUK_ID' => $this->input->post('SUK_ID', TRUE),
                        'KODE_ESELON' => $this->input->post('KODE_ESELON', TRUE),
                        'CREATED_TIME' => date('Y-m-d H:i:s'),
                        'CREATED_BY' => $this->session->userdata('USR'),
                        'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                        'INST_SATKERKD' => $this->input->post('INST_TUJUAN', TRUE),
                    );
                    $this->db->insert($this->data['tbl'], $jabatan);
                    // ng::logActivity('Tambah Data Jabatan id = '.$this->db->insert_id());
                    //ng::logActivity('Tambah Data Jabatan, id = '.$id);
                }
            } else if ($this->act == 'doupdate') {

                $jabatan = array(
                    'NAMA_JABATAN' => $this->input->post('NAMA_JABATAN', TRUE),
                    'IS_UU' => $this->input->post('UU', TRUE),
//                    'UK_ID' => $this->input->post('UNIT_KERJA', TRUE),
//                    'SUK_ID' => $this->input->post('SUB_UNIT_KERJA', TRUE),
//                    'UK_ID' => $this->input->post('UK_ID', TRUE),
//                    'SUK_ID' => $this->input->post('SUK_ID', TRUE),
                    'KODE_ESELON' => $this->input->post('KODE_ESELON', TRUE),
                    // 'IS_ACTIVE' => $this->input->post('IS_ACTIVE', TRUE),
                    // 'CREATED_TIME'     => time(),
                    // 'CREATED_BY'     => $this->session->userdata('USR'),
                    // 'CREATED_IP'     => $_SERVER["REMOTE_ADDR"],
                    'UPDATED_TIME' => date('Y-m-d H:i:s'),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                    'INST_SATKERKD' => $this->input->post('INST_TUJUAN', TRUE),
                );
                $jabatan['ID_JABATAN'] = $this->input->post('ID_JABATAN', TRUE);
                $this->mjabatan->update($jabatan['ID_JABATAN'], $jabatan);

                ng::logActivity('Edit Data Jabatan, id = ' . $jabatan['ID_JABATAN']);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);

                $this->db->update($this->data['tbl'], $data);
                ng::logActivity('Hapus Data Jabatan, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Jabatan, id = ' . $data[$this->data['pk']]);
            } else if ($type == 'reff') {
//                $this->data['eselon'] = $this->mglobal->get_data_all('M_ESELON');
            }
        }
    }

    /**
     * Masterdata Jenis Bukti
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function jenis_bukti($type = '', $id = '') {
        $this->jenis_bukti_called = TRUE;
        $this->data['tbl'] = 'M_JENIS_BUKTI';
        $this->data['pk'] = 'ID_JENIS_BUKTI';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_JENIS_BUKTI');
//            $this->db->join('M_GOLONGAN_HARTA', 'M_GOLONGAN_HARTA.ID_GOLONGAN_HARTA = M_JENIS_BUKTI.GOLONGAN_HARTA');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_JENIS_BUKTI.JENIS_BUKTI', $this->CARI['TEXT']);
//            }
//
//            if (@$this->CARI['IS_ACTIVE'] == 99) {
//                // all
//            } else if (@$this->CARI['IS_ACTIVE'] == -1) {
//                // deleted
//                $this->db->where('M_JENIS_BUKTI.IS_ACTIVE', -1);
//            } else if (@$this->CARI['IS_ACTIVE']) {
//                // by status
//                $this->db->where('M_JENIS_BUKTI.IS_ACTIVE', $this->CARI['IS_ACTIVE']);
//            } else {
//                // active
//                $this->db->where('M_JENIS_BUKTI.IS_ACTIVE', 1);
//            }
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'edit') {
            $join = [
                ['table' => 'M_GOLONGAN_HARTA', 'on' => 'm_jenis_bukti.GOLONGAN_HARTA = m_golongan_harta.ID_GOLONGAN_HARTA']
            ];
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array_new($this->data['tbl'], $join, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'GOLONGAN_HARTA' => $this->input->post('GOLONGAN_HARTA', TRUE),
                    'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Jenis Bukti, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'GOLONGAN_HARTA' => $this->input->post('GOLONGAN_HARTA', TRUE),
                    'JENIS_BUKTI' => $this->input->post('JENIS_BUKTI', TRUE),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Jenis Bukti, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Jenis Bukti, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Jenis Bukti, id = ' . $data[$this->data['pk']]);
            }
        } else if ($type == 'reff') {
            $this->data['golta'] = $this->mglobal->get_data_all('M_GOLONGAN_HARTA');
        }
    }

    /**
     * Masterdata Wilayah
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function wilayah($type = '', $id = '') {
        $this->data['tbl'] = 'M_AREA';
        $this->data['pk'] = 'ID_AREA';

        if ($type == 'list') {
            // prepare query
            $this->db->select('*');
            $this->db->from('M_AREA');
            //$this->db->where('1=1', null, false);

            if (@$this->CARI['PROV']) {
                $this->db->like('M_AREA.IDPROV', $this->CARI['PROV']);
            }

            if (@$this->CARI['KOTA']) {
                $this->db->like('M_AREA.IDKOT', $this->CARI['KOTA']);
            }

            if (@$this->CARI['KEC']) {
                $this->db->like('M_AREA.IDKEC', $this->CARI['KEC']);
            }

            if (@$this->CARI['TEXT']) {
                $this->db->like('M_AREA.NAME', $this->CARI['TEXT']);
            }

            $this->configure_pencarian_active();
        } else if ($type == 'edit') {
            $select = "ID_AREA, NAME, IDPROV, IDKOT, IDKEC, IDKEL, LEVEL";

            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], null, [$this->data['pk'] => $id], $select)[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'IDPROV' => $this->input->post('IDPROV', TRUE),
                    'IDKOT' => $this->input->post('IDKOT', TRUE),
                    'IDKEC' => $this->input->post('IDKEC', TRUE),
                    'IDKEL' => $this->input->post('IDKEL', TRUE),
                    'NAME' => $this->input->post('NAME', TRUE),
                    'LEVEL' => $this->input->post('LEVEL', TRUE),
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Wilayah, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'IDPROV' => $this->input->post('IDPROV', TRUE),
                    'IDKOT' => $this->input->post('IDKOT', TRUE),
                    'IDKEC' => $this->input->post('IDKEC', TRUE),
                    'IDKEL' => $this->input->post('IDKEL', TRUE),
                    'NAME' => $this->input->post('NAME', TRUE),
                    'LEVEL' => $this->input->post('LEVEL', TRUE),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Wilayah, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {

                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);

                $this->db->update($this->data['tbl'], $data);
                ng::logActivity('Hapus Data Wilayah, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * ini digunakan di setiap method masterdata
     * sehingga jika ada kesalahan di ci_session, bisa dinonaktifkan disini
     */
    public function configure_pencarian_active() {

        $this_function_is_active = FALSE;

        if ($this_function_is_active) {
            if (@$this->CARI['IS_ACTIVE'] == 99) {
                // all
            } else if (@$this->CARI['IS_ACTIVE'] == -1) {
                // deleted
                $this->db->where('IS_ACTIVE', -1);
            } else if (@$this->CARI['IS_ACTIVE']) {
                // by status
                $this->db->where('IS_ACTIVE', $this->CARI['IS_ACTIVE']);
            } else {
                // active
                $this->db->where('IS_ACTIVE', 1);
            }
        }
    }

    /**
     * Masterdata Pendidikan
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function pendidikan($type = '', $id = '') {
        $this->pendidikan_called = TRUE;
        $this->data['tbl'] = 'M_PENDIDIKAN';
        $this->data['pk'] = 'ID_PENDIDIKAN';

        if ($type == 'list') {
            // prepare query
//            $this->db->select('*');
//
//            $this->db->from('M_PENDIDIKAN');
////            //$this->db->where('1=1', null, false);
////            $this->CARI['TEXT'] = 'abc';
//            if (@$this->CARI['TEXT']) {
//
//                $this->db->like('M_PENDIDIKAN.PENDIDIKAN', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
//            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'PENDIDIKAN' => $this->input->post('PENDIDIKAN', TRUE),
                    'IS_ACTIVE' => 1,
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Pendidikan, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'PENDIDIKAN' => $this->input->post('PENDIDIKAN', TRUE),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Pendidikan, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Pendidikan, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Pendidikan, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Pendidikan
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function eselon($type = '', $id = '') {
        $this->eselon_called = TRUE;
        $this->data['tbl'] = 'M_ESELON';
        $this->data['pk'] = 'ID_ESELON';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//
//            $this->db->from('M_ESELON');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//
//                $this->db->like('M_ESELON.KODE_ESELON', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'KODE_ESELON' => $this->input->post('KODE_ESELON', TRUE),
                    'ESELON' => $this->input->post('ESELON', TRUE),
                    'IS_ACTIVE' => 1,
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Eselon, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'KODE_ESELON' => $this->input->post('KODE_ESELON', TRUE),
                    'ESELON' => $this->input->post('ESELON', TRUE),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Eselon, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Eselon, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Eselon, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Pemanfaatan
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function pemanfaatan($type = '', $id = '') {
        $this->pemanfaatan_called = TRUE;
        $this->data['tbl'] = 'M_PEMANFAATAN';
        $this->data['pk'] = 'ID_PEMANFAATAN';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//
//            $this->db->from('M_PEMANFAATAN');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//
//                $this->db->like('M_PEMANFAATAN.PEMANFAATAN', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'PEMANFAATAN' => $this->input->post('PEMANFAATAN', TRUE),
                    'GOLONGAN_HARTA' => $this->input->post('GOLONGAN_HARTA', TRUE),
                    'IS_ACTIVE' => 1,
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Pemanfaatan, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'PEMANFAATAN' => $this->input->post('PEMANFAATAN', TRUE),
                    'GOLONGAN_HARTA' => $this->input->post('GOLONGAN_HARTA', TRUE),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Pemanfaatan, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Pemanfaatan, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Pemanfaatan, id = ' . $data[$this->data['pk']]);
            }
        } else if ($type == 'reff') {
            $this->data['golta'] = $this->mglobal->get_data_all('M_GOLONGAN_HARTA');
        }
    }

    /**
     * Masterdata Jenis Penerimaan Kas
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function jenispenerimaankas($type = '', $id = '') {
        $this->jenispenerimaankas_called = TRUE;
        $this->data['tbl'] = 'M_JENIS_PENERIMAAN_KAS';
        $this->data['pk'] = 'ID_JENIS_PENERIMAAN_KAS';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_JENIS_PENERIMAAN_KAS');
//            $this->db->join('M_GOLONGAN_PENERIMAAN_KAS', 'M_GOLONGAN_PENERIMAAN_KAS.ID_GOLONGAN_PENERIMAAN_KAS = M_JENIS_PENERIMAAN_KAS.GOLONGAN');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_JENIS_PENERIMAAN_KAS.NAMA', $this->CARI['TEXT']);
//                $this->db->or_like('M_GOLONGAN_PENERIMAAN_KAS.NAMA_GOLONGAN', $this->CARI['TEXT']);
//            }
//
////            tik
////            if (@$this->CARI['GOLONGAN'] == 99) {
////                // all
////            } else if (@$this->CARI['GOLONGAN']) {
////                $this->db->where('M_GOLONGAN_PENERIMAAN_KAS.ID_GOLONGAN_PENERIMAAN_KAS', $this->CARI['GOLONGAN']);
////            }
//
//
//            $this->configure_pencarian_active();
//
//            $this->data['title'] = 'Jenis Penerimaan Kas';
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'GOLONGAN' => $this->input->post('GOLONGAN', TRUE),
                    'NAMA' => $this->input->post('NAMA', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Jenis Penerimaan Kas, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'GOLONGAN' => $this->input->post('GOLONGAN', TRUE),
                    'NAMA' => $this->input->post('NAMA', TRUE),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Jenis Penerimaan Kas, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Jenis Penerimaan Kas, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Jenis Penerimaan Kas, id = ' . $data[$this->data['pk']]);
            }
        } else if ($type == 'reff') {
            $this->data['golongan'] = $this->mglobal->get_data_all('M_GOLONGAN_PENERIMAAN_KAS');
        }
    }

    /**
     * Masterdata Jenis Pengeluaran Kas
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function jenispengeluarankas($type = '', $id = '') {
        $this->jenispengeluarankas_called = TRUE;
        $this->data['tbl'] = 'M_JENIS_PENGELUARAN_KAS';
        $this->data['pk'] = 'ID_JENIS_PENGELUARAN_KAS';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_JENIS_PENGELUARAN_KAS');
//            $this->db->join('M_GOLONGAN_PENGELUARAN_KAS', 'M_GOLONGAN_PENGELUARAN_KAS.ID_GOLONGAN_PENGELUARAN_KAS = M_JENIS_PENGELUARAN_KAS.GOLONGAN');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_JENIS_PENGELUARAN_KAS.NAMA', $this->CARI['TEXT']);
//                $this->db->or_like('M_GOLONGAN_PENGELUARAN_KAS.NAMA_GOLONGAN', $this->CARI['TEXT']);
//            }
//
//            if (@$this->CARI['GOLONGAN'] == 99) {
//                // all
//            } else if (@$this->CARI['GOLONGAN']) {
//                $this->db->where('M_GOLONGAN_PENGELUARAN_KAS.ID_GOLONGAN_PENGELUARAN_KAS', $this->CARI['GOLONGAN']);
//            }
//
//
//            $this->configure_pencarian_active();
//
//            $this->data['title'] = 'Jenis Pengeluaran Kas';
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'GOLONGAN' => $this->input->post('GOLONGAN', TRUE),
                    'NAMA' => $this->input->post('NAMA', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Jenis Pengeluaran Kas, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'GOLONGAN' => $this->input->post('GOLONGAN', TRUE),
                    'NAMA' => $this->input->post('NAMA', TRUE),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Jenis Pengeluaran Kas, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Jenis Pengeluaran Kas, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Jenis Pengeluaran Kas, id = ' . $data[$this->data['pk']]);
            }
        } else if ($type == 'reff') {
            $this->data['glg'] = $this->mglobal->get_data_all('M_GOLONGAN_PENGELUARAN_KAS');
        }
    }

    /**
     * Masterdata Jenis Pelepasan Harta
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function jenis_pelepasanharta($type = '', $id = '') {
        $this->jenis_pelepasanharta_called = TRUE;
        $this->data['tbl'] = 'M_JENIS_PELEPASAN_HARTA';
        $this->data['pk'] = 'ID';

        if ($type == 'list') {
//            // prepare query
//            $this->db->select('*');
//
//            $this->db->from('M_JENIS_PELEPASAN_HARTA');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//
//                $this->db->like('M_JENIS_PELEPASAN_HARTA.JENIS_PELEPASAN_HARTA', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS_PELEPASAN_HARTA', TRUE),
                    'IS_ACTIVE' => 1,
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Jenis Pelepasan Harta, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
//                tik
//                $data = array(
//                    'PEMANFAATAN' => $this->input->post('PEMANFAATAN', TRUE),
//                );

                $data = array(
                    'JENIS_PELEPASAN_HARTA' => $this->input->post('JENIS_PELEPASAN_HARTA', TRUE),
                    'IS_ACTIVE' => 1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Jenis Pelepasan Harta, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Pelepasan Harta, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Pelepasan Harta, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Mata Uang
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function matauang($type = '', $id = '') {
        $this->matauang_called = TRUE;
        $this->data['tbl'] = 'M_MATA_UANG';
        $this->data['pk'] = 'ID_MATA_UANG';

        if ($type == 'list') {
            // prepare query
//            $this->db->select('*');
//            $this->db->from('M_MATA_UANG');
//            //$this->db->where('1=1', null, false);
//            $this->configure_pencarian_active();
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_MATA_UANG.NAMA_MATA_UANG', $this->CARI['TEXT']);
//                $this->db->or_like('M_MATA_UANG.NEGARA', $this->CARI['TEXT']);
//            }
//
//            $this->data['title'] = 'Mata Uang';
        } else if ($type == 'reff') {
            
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'NAMA_MATA_UANG' => $this->input->post('NAMA_MATA_UANG', TRUE),
                    'SINGKATAN' => $this->input->post('SINGKATAN', TRUE),
                    'SIMBOL' => $this->input->post('SIMBOL', TRUE),
                    'NEGARA' => $this->input->post('NEGARA', TRUE),
                );
                $this->db->insert($this->data['tbl'], $data);

                ng::logActivity('Tambah Data Mata Uang, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'NAMA_MATA_UANG' => $this->input->post('NAMA_MATA_UANG', TRUE),
                    'SINGKATAN' => $this->input->post('SINGKATAN', TRUE),
                    'SIMBOL' => $this->input->post('SIMBOL', TRUE),
                    'NEGARA' => $this->input->post('NEGARA', TRUE),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Mata Uang, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array();
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);

                $d_update = array('IS_ACTIVE' => -1);
                $this->db->update($this->data['tbl'], $d_update);

                ng::logActivity('Hapus Data Mata Uang, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Mata Uang, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Form Bantuan
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function bantuan($type = '', $id = '') {
        $this->bantuan_called = TRUE;
        $this->data['tbl'] = 'M_BANTUAN';
        $this->data['pk'] = 'ID';

        if ($type == 'list') {
//            $this->db->select('*');
//            $this->db->from('M_BANTUAN');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_BANTUAN.NAME_FORM', $this->CARI['TEXT']);
//                $this->db->or_like('M_BANTUAN.FORM_DESC', $this->CARI['TEXT']);
//            }
//
//
//            $this->configure_pencarian_active();
//
//
//
//            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
//            $filterCetakItem['Tanggal'] = @$this->CARI['TEXT'];
//            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
//        
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
            
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'NAME_FORM' => $this->input->post('NAME_FORM', TRUE),
                    'FORM_TITLE' => $this->input->post('FORM_TITLE', TRUE),
                    'FORM_DESC' => $this->input->post('FORM_DESC', TRUE),
                    'FORM_HELP' => $this->input->post('FORM_HELP', TRUE),
                    'FORM_TOOLTIP' => $this->input->post('FORM_TOOLTIP', TRUE),
                    'CREATED_ON' => date('Y-m-d'),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'IS_ACTIVE' => 1,
                        // 'UPDATE_ON'	=> time(),
                        // 'UPDATED_BY'	=> $this->session->userdata('USR'),
                );
                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Form Bantuan, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'NAME_FORM' => $this->input->post('NAME_FORM', TRUE),
                    'FORM_TITLE' => $this->input->post('FORM_TITLE', TRUE),
                    'FORM_DESC' => $this->input->post('FORM_DESC', TRUE),
                    'FORM_HELP' => $this->input->post('FORM_HELP', TRUE),
                    'FORM_TOOLTIP' => $this->input->post('FORM_TOOLTIP', TRUE),
                    'UPDATED_ON' => date('Y-m-d'),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Bantuan = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'UPDATED_ON' => date('Y-m-d'),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'IS_ACTIVE' => -1,
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Form Bantuan, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Agama, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Form Petunjuk
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function petunjuk($type = '', $id = '') {
        $this->petunjuk_called = TRUE;
        $this->data['tbl'] = 'M_PETUNJUK_EFILING';
        $this->data['pk'] = 'ID';

        if ($type == 'list') {
//            $this->db->select('*');
//            $this->db->from('M_PETUNJUK_EFILING');
//            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_PETUNJUK_EFILING.PETUNJUK_NAME', $this->CARI['TEXT']);
//                $this->db->or_like('M_PETUNJUK_EFILING.PETUNJUK_DESC', $this->CARI['TEXT']);
//            }
//
//            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
//            $filterCetakItem['Tanggal'] = @$this->CARI['TEXT'];
//            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
//        
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'kembalikan') {
//            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'PETUNJUK_NAME' => $this->input->post('PETUNJUK_NAME', TRUE),
                    'PETUNJUK_TITLE' => $this->input->post('PETUNJUK_TITLE', TRUE),
                    'PETUNJUK_DESC' => $this->input->post('txtPetunjuk', TRUE),
                    'CREATED_ON' => date('Y-m-d H:i:s'), // time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'IS_ACTIVE' => 1,
                        // 'UPDATE_ON'	=> time(),
                        // 'UPDATED_BY'	=> $this->session->userdata('USR'),
                );
                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Petunjuk, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'PETUNJUK_NAME' => $this->input->post('PETUNJUK_NAME', TRUE),
                    'PETUNJUK_TITLE' => $this->input->post('PETUNJUK_TITLE', TRUE),
                    'PETUNJUK_DESC' => $this->input->post('txtPetunjuk', TRUE),
                    'UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Petunjuk = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Petunjuk, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_ON' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Petunjuk, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Provinsi
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function provinsi($type = '', $id = '') {
        $this->provinsi_called = TRUE;
        $this->data['tbl'] = 'M_AREA_PROV';
        $this->data['pk'] = 'ID_PROV';

        if ($type == 'list') {
//            $this->db->select('*');
//            $this->db->from('M_AREA_PROV');
////            //$this->db->where('1=1', null, false);
//
//            if (@$this->CARI['TEXT']) {
//                $this->db->like('M_AREA_PROV.NAME', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
//
//
//            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
//            $filterCetakItem['Tanggal'] = @$this->CARI['TEXT'];
//
//            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
        } else if ($type == 'edit') {
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], NULL, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'NAME' => $this->input->post('NAME', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Form Provinsi, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'NAME' => $this->input->post('NAME', TRUE),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Provinsi = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Form Provinsi, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Provinsi, id = ' . $data[$this->data['pk']]);
            }
        }
    }

    /**
     * Masterdata Kabupaten
     * 
     * @return html : list, form, formprocess, pdf, excel, word
     */
    public function kabupaten($type = '', $id = '') {
        $this->kabupaten_called = TRUE;
        $this->data['tbl'] = 'M_AREA_KAB';
        $this->data['pk'] = 'ID_KAB';

        if ($type == 'list') {
//            // prepare query
//            // $this->db->select('M_INST_SATKER.INST_SATKERKD, M_INST_SATKER.INST_NAMA, M_BIDANG.BDG_ID, M_BIDANG.BDG_NAMA, M_UNIT_KERJA.UK_ID, M_UNIT_KERJA.UK_LEMBAGA_ID,M_UNIT_KERJA.UK_NAMA,M_UNIT_KERJA.UK_STATUS');
//            // $this->db->select('M_AREA_PROV.ID_PROV, M_AREA_PROV.NAME, M_AREA_KAB.ID_KAB, M_AREA_KAB.ID_PROV,M_AREA_KAB.NAME,M_AREA_KAB.IS_ACTIVE');
//            $this->db->select('M_AREA_PROV.ID_PROV, M_AREA_PROV.NAME , M_AREA_KAB.ID_KAB, M_AREA_KAB.ID_PROV,M_AREA_KAB.NAME_KAB,M_AREA_KAB.IS_ACTIVE');
//            $this->db->from('M_AREA_KAB');
//            //$this->db->join('M_BIDANG', 'M_UNIT_KERJA.UK_BIDANG_ID = M_BIDANG.BDG_ID', 'left');
//            $this->db->join('M_AREA_PROV', 'M_AREA_KAB.ID_PROV = M_AREA_PROV.ID_PROV', 'left');
//            //$this->db->where('1=1', null, false);
//
//
//
//            if (@$this->CARI['PROV']) {
//                $this->db->like('M_AREA.PROV.ID_PROV', $this->CARI['PROV']);
//            }
//
//            if (@$this->CARI['TEXT']) {
//
//                $this->db->like('M_AREA_KAB.NAME_KAB', $this->CARI['TEXT']);
//                $this->db->or_like('M_AREA_PROV.NAME', $this->CARI['TEXT']);
//            }
//
//            $this->configure_pencarian_active();
//            
//
//            $filterCetakItem['Text'] = @$this->CARI['TEXT'];
//            $this->data['filterCetak'] = $this->prepareFilterCetak($filterCetakItem);
        } else if ($type == 'edit') {
            $join = [
                //['table' => 'M_BIDANG', 'on' => 'M_UNIT_KERJA.UK_BIDANG_ID = M_BIDANG.BDG_ID'],
                ['table' => 'M_AREA_PROV', 'on' => 'M_AREA_KAB.ID_PROV = M_AREA_PROV.ID_PROV']
            ];
            $this->data['item'] = $id ? (object) $this->mglobal->get_data_all_array($this->data['tbl'], $join, [$this->data['pk'] => $id])[0] : '';
        } else if ($type == 'save') {
            if ($this->act == 'doinsert') {
                $data = array(
                    'NAME_KAB' => $this->input->post('NAME_KAB', TRUE),
                    'ID_PROV' => $this->input->post('ID_PROV', TRUE),
                    'IS_ACTIVE' => 1,
                    'CREATED_TIME' => time(),
                    'CREATED_BY' => $this->session->userdata('USR'),
                    'CREATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $this->db->insert($this->data['tbl'], $data);
                ng::logActivity('Tambah Data Kabupaten, id = ' . $this->db->insert_id());
            } else if ($this->act == 'doupdate') {
                $data = array(
                    'NAME_KAB' => $this->input->post('NAME_KAB', TRUE),
                    'ID_PROV' => $this->input->post('ID_PROV', TRUE),
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Edit Data Kabupaten/Kota = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dodelete') {
                $data = array(
                    'IS_ACTIVE' => -1,
                    'UPDATED_TIME' => time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Hapus Data Kabupaten/Kota, id = ' . $data[$this->data['pk']]);
            } else if ($this->act == 'dokembalikan') {
                $data = array(
                    'IS_ACTIVE' => 1,
                    'UPDATED_TIME' => date('Y-m-d H:i:s'), //time(),
                    'UPDATED_BY' => $this->session->userdata('USR'),
                    'UPDATED_IP' => $_SERVER["REMOTE_ADDR"],
                );
                $data[$this->data['pk']] = $this->input->post($this->data['pk'], TRUE);
                $this->db->where($this->data['pk'], $data[$this->data['pk']]);
                $this->db->update($this->data['tbl'], $data);

                ng::logActivity('Kembalikan Data Kabupaten/Kota, id = ' . $data[$this->data['pk']]);
            }
        }
        //else if($type == 'reff'){
        // 	$this->data['bidang'] 	= $this->mglobal->get_data_all('M_BIDANG');
        // }
    }

    public function rekap_pnwl_to_kepatuhan() {
        $data = array(
            'breadcrumb' => call_user_func('ng::genBreadcrumb', array(
                'Dashboard' => 'index.php/welcome/dashboard',
                'Masterdata' => 'index.php/admin/masterdata',
                'Rekap Riwayat Jabatan PN/WL Tahunan' => '#'
        )));
        $this->load->view('masterdata/masterdata_rekap_pnwl', $data);
    }

    public function rekap_pnwl_proses() {
        //echo "masuk delete";
        $this->mglobal->rekap_pnwl_delete();
        //echo "masuk rekap";
        //$pindah = 
        $this->mglobal->rekap_pnwl_entry();
        return true;
    }

    public function rekap_pnwl_popup() {
        $this->load->view('masterdata/masterdata_rekap_pnwl_popup');
    }

}
