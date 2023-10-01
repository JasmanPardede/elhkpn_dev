<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ikthisar extends CI_Controller {

    function __Construct() {
        parent::__Construct();
        call_user_func('ng::islogin');
    }

    function cetak($ID_LHKPN = FALSE) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = array();
            $data['LHKPN'] = $cek_id_user;
            $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
            $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
            $data['JABATAN'] = $this->jabatan($ID_LHKPN);
            $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);

            $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
            $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
            $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
            $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
            $data['HUTANG'] = $this->hutang($ID_LHKPN);

            $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
            $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
            $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);

            /* print_r($data['HARTA_TDK_BEGERAK']); */
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), TRUE);
//            var_dump($data['PRIBADI']->NHK);exit;
//            echo $html;exit;
            try {
//                var_dump(file_exists(APPPATH . 'third_party/mpdf6.0/src/Mpdf.php'));exit;
                include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetFont('dejavusans', '', 9);
                $pdf->AddPage();
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->lastPage();
                $pdf->Output('ikhtisar.pdf', 'I');
//                $pdf = new mPDF('c', 'A4-L');
//                $pdf->WriteHTML($html);
//                $pdf->Output();
            } catch (Exception $e) {
                
            }
        } else {
            redirect('portal/filing');
        }
    }

    function preview($ID_LHKPN, $KETENTUAN = 0, $OPTION = 0) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = array();
            $data['LHKPN'] = $cek_id_user;
            $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
            $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
            $data['JABATAN'] = $this->jabatan($ID_LHKPN);
            $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
            $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
            $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
            $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
            $data['HUTANG'] = $this->hutang($ID_LHKPN);
            $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
            $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
            $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);
            $data['KETENTUAN'] = $KETENTUAN;
            $data['OPTION'] = $OPTION;
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), true);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('SURAT_PERNYATAAN' => $html, 'STATUS_SURAT_PERNYATAAN' => '' . $OPTION));
            //$this->db->update('t_lhkpn',array('SURAT_PERNYATAAN'=>$html,'STATUS_SURAT_PERNYATAAN'=>''));
            echo $html;
            /* include_once APPPATH.'/third_party/mpdf/mpdf.php';
              $pdf = new mPDF('A4-L');
              $pdf->WriteHTML($html);
              $pdf->Output(); */
        } else {
            redirect('portal/filing');
        }
    }

    function priview_cetak($ID_LHKPN, $KETENTUAN = 0, $OPTION = 0) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = array();
            $data['LHKPN'] = $cek_id_user;
            $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
            $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
            $data['JABATAN'] = $this->jabatan($ID_LHKPN);
            $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
            $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
            $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
            $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
            $data['HUTANG'] = $this->hutang($ID_LHKPN);
            $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
            $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
            $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);
            $data['KETENTUAN'] = $KETENTUAN;
            $data['OPTION'] = $OPTION;
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), true);
            $this->db->where('ID_LHKPN', $ID_LHKPN);
            $this->db->update('t_lhkpn', array('SURAT_PERNYATAAN' => $html, 'STATUS_SURAT_PERNYATAAN' => '' . $OPTION));
            include_once APPPATH . '/third_party/mpdf/mpdf.php';
            $pdf = new mPDF('A4-L');
            $pdf->WriteHTML($html);
            $pdf->Output();
        } else {
            redirect('portal/filing');
        }
    }

    function cetakAnnoun($ID_LHKPN) {
        $cek_id_user = $this->lhkpn($ID_LHKPN);
        if ($ID_LHKPN && $cek_id_user) {
            $data = array();
            $data['LHKPN'] = $cek_id_user;
            $data['PRIBADI'] = $this->pribadi($ID_LHKPN);
            $data['KELUARGA'] = $this->keluarga($ID_LHKPN);
            $data['JABATAN'] = $this->jabatan($ID_LHKPN);
            $data['HARTA_TDK_BEGERAK'] = $this->harta_tidak_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK'] = $this->harta_bergerak($ID_LHKPN);
            $data['HARTA_BERGERAK_LAIN'] = $this->harta_bergerak_lain($ID_LHKPN);
            $data['HARTA_SURAT_BERHARGA'] = $this->harta_surat_berharga($ID_LHKPN);
            $data['HARTA_LAINNYA'] = $this->harta_lainnya($ID_LHKPN);
            $data['HARTA_KAS'] = $this->harta_kas($ID_LHKPN);
            $data['HUTANG'] = $this->hutang($ID_LHKPN);
            $data['PENERIMAAN'] = $this->penerimaan($ID_LHKPN);
            $data['PENGELUARAN'] = $this->pengeluaran($ID_LHKPN);
            $data['FASILITAS'] = $this->fasilitas($ID_LHKPN);
            /* print_r($data['HARTA_TDK_BEGERAK']); */
            $html = $this->load->view('portal/filing/ikthisar', array('data' => $data), TRUE);
            include_once APPPATH . '/third_party/mpdf/mpdf.php';
            $pdf = new mPDF('c', 'A4-L');
            $pdf->WriteHTML($html);
            $pdf->Output();
        } else {
            redirect('portal/filing');
        }
    }

    function lhkpn($ID_LHKPN) {
        $this->db->where('t_lhkpn.ID_LHKPN', $ID_LHKPN);
        $this->db->where('t_lhkpn.ID_PN', $this->session->userdata('ID_PN'));
        $data = $this->db->get('t_lhkpn')->row();
        return $data;
    }

    function pribadi($ID_LHKPN) {
        $this->db->select('t_lhkpn_data_pribadi.*,m_negara.KODE_ISO3,.m_negara.NAMA_NEGARA,m_area_prov.*,m_area_kab.*,t_pn.NHK');
        $this->db->where('t_lhkpn_data_pribadi.ID_LHKPN', $ID_LHKPN);
        $this->db->group_by('t_lhkpn_data_pribadi.ID');
        $this->db->order_by('t_lhkpn_data_pribadi.ID', 'DESC');
        $this->db->where('t_lhkpn_data_pribadi.IS_ACTIVE', '1');
        $this->db->join('m_negara', 'm_negara.KODE_ISO3 = t_lhkpn_data_pribadi.KD_ISO3_NEGARA', 'LEFT');
        $this->db->join('m_area_prov', 'm_area_prov.NAME = t_lhkpn_data_pribadi.PROVINSI', 'LEFT');
        $this->db->join('m_area_kab', 'm_area_kab.NAME_KAB = t_lhkpn_data_pribadi.KABKOT', 'LEFT');
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = t_lhkpn_data_pribadi.ID_LHKPN');
        $this->db->join('t_pn', 't_pn.ID_PN = t_lhkpn.ID_PN');
        $data = $this->db->get('t_lhkpn_data_pribadi')->row();
        return $data;
    }

    function keluarga($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_keluarga')->result();
        return $data;
    }

    function jabatan($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jabatan', 'm_jabatan.ID_JABATAN = t_lhkpn_jabatan.ID_JABATAN', 'left');
        $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_lhkpn_jabatan.LEMBAGA', 'left');
        $this->db->join('m_unit_kerja', 'm_unit_kerja.UK_ID = t_lhkpn_jabatan.UNIT_KERJA', 'left');
        $this->db->join('m_sub_unit_kerja', 'm_sub_unit_kerja.SUK_ID = t_lhkpn_jabatan.SUB_UNIT_KERJA', 'left');
        $this->db->join('m_bidang', ',m_bidang.BDG_ID = m_inst_satker.INST_BDG_ID');
        $this->db->group_by('t_lhkpn_jabatan.ID');
        $data = $this->db->get('t_lhkpn_jabatan')->result();
        return $data;
    }

    function harta_tidak_bergerak($ID_LHKPN) {
        $this->db->select('
        	m_jenis_bukti.*,
        	m_mata_uang.*,
        	m_negara.NAMA_NEGARA,
        	t_lhkpn_harta_tidak_bergerak.*,
        	t_lhkpn_harta_tidak_bergerak.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_tidak_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_tidak_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_tidak_bergerak AS OLD WHERE OLD.ID = t_lhkpn_harta_tidak_bergerak.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_tidak_bergerak.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (t_lhkpn_harta_tidak_bergerak.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
        	m_jenis_bukti.JENIS_BUKTI AS JENIS_BUKTI_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_tidak_bergerak WHERE t_lhkpn_pelepasan_harta_tidak_bergerak.ID_HARTA = t_lhkpn_harta_tidak_bergerak.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');
        $TABLE = 't_lhkpn_harta_tidak_bergerak';
        $PK = 'ID';
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $this->db->where($TABLE . '.IS_ACTIVE', '1');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ', 'left');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG ', 'left');
        $this->db->join('m_negara', 'm_negara.ID = t_lhkpn_harta_tidak_bergerak.ID_NEGARA', 'left');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_tidak_bergerak')->result();
        return $data;
    }

    function harta_bergerak($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_bergerak';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	m_jenis_bukti.*,
        	m_jenis_bukti.JENIS_BUKTI AS N_JENIS_BUKTI,
        	t_lhkpn_harta_bergerak.*,
        	m_jenis_harta.NAMA AS JENIS_HARTA,
        	t_lhkpn_harta_bergerak.STATUS as STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_bergerak`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_bergerak`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_bergerak AS OLD WHERE OLD.ID = t_lhkpn_harta_bergerak.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_bergerak.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (t_lhkpn_harta_bergerak.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_bergerak WHERE t_lhkpn_pelepasan_harta_bergerak.ID_HARTA = t_lhkpn_harta_bergerak.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN
        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_jenis_bukti', 'm_jenis_bukti.ID_JENIS_BUKTI = ' . $TABLE . '.JENIS_BUKTI ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_bergerak')->result();
        return $data;
    }

    function harta_bergerak_lain($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_bergerak_lain';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_bergerak_lain.*,
        	t_lhkpn_harta_bergerak_lain.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_bergerak_lain`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_bergerak_lain`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_harta.NAMA as JENIS_HARTA,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_bergerak_lain AS OLD WHERE OLD.ID = t_lhkpn_harta_bergerak_lain.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_bergerak_lain.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT GROUP_CONCAT( DISTINCT PEMANFAATAN) FROM m_pemanfaatan WHERE ID_PEMANFAATAN IN (t_lhkpn_harta_bergerak_lain.PEMANFAATAN) ) AS PEMANFAATAN_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_bergerak_lain WHERE t_lhkpn_pelepasan_harta_bergerak_lain.ID_HARTA = t_lhkpn_harta_bergerak_lain.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_bergerak_lain')->result();
        return $data;
    }

    function harta_surat_berharga($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_surat_berharga';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_surat_berharga.*,
        	t_lhkpn_harta_surat_berharga.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_surat_berharga`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_surat_berharga`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_surat_berharga AS OLD WHERE OLD.ID = t_lhkpn_harta_surat_berharga.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_surat_berharga.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_surat_berharga WHERE t_lhkpn_pelepasan_harta_surat_berharga.ID_HARTA = t_lhkpn_harta_surat_berharga.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN LIMIT 1 ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_surat_berharga')->result();
        return $data;
    }

    function harta_lainnya($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_lainnya';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	t_lhkpn_harta_lainnya.*,
        	t_lhkpn_harta_lainnya.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_lainnya`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_lainnya`.`NILAI_PELAPORAN`
                END `NILAI_PELAPORAN`,
        	m_jenis_harta.NAMA AS NAMA_JENIS,
        	(SELECT NILAI_PELAPORAN FROM t_lhkpn_harta_lainnya AS OLD WHERE OLD.ID = t_lhkpn_harta_lainnya.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_lainnya.ASAL_USUL) ) AS ASAL_USUL_HARTA,
       		(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_lainnya WHERE t_lhkpn_pelepasan_harta_lainnya.ID_HARTA = t_lhkpn_harta_lainnya.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN
       	');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_lainnya')->result();
        return $data;
    }

    function harta_kas($ID_LHKPN) {
        $TABLE = 't_lhkpn_harta_kas';
        $PK = 'ID';
        $this->db->select('
        	m_jenis_harta.*,
        	m_mata_uang.*,
        	t_lhkpn_harta_kas.*,
        	t_lhkpn_harta_kas.STATUS AS STATUS_HARTA,
                CASE 
                      WHEN `t_lhkpn_harta_kas`.`IS_PELEPASAN` = \'1\' THEN
                         \'0\'
                      ELSE
                         `t_lhkpn_harta_kas`.`NILAI_SALDO`
                END `NILAI_SALDO`,
        	(SELECT NILAI_SALDO FROM t_lhkpn_harta_kas AS OLD WHERE OLD.ID = t_lhkpn_harta_kas.ID_HARTA LIMIT 1 ) AS NILAI_LAMA,
        	(SELECT GROUP_CONCAT( DISTINCT ASAL_USUL) FROM m_asal_usul WHERE ID_ASAL_USUL IN (t_lhkpn_harta_kas.ASAL_USUL) ) AS ASAL_USUL_HARTA,
        	(SELECT JENIS_PELEPASAN_HARTA FROM t_lhkpn_pelepasan_harta_kas WHERE t_lhkpn_pelepasan_harta_kas.ID_HARTA = t_lhkpn_harta_kas.ID LIMIT 1 ) AS ID_JENIS_PELEPASAN,
        	(SELECT JENIS_PELEPASAN_HARTA FROM m_jenis_pelepasan_harta WHERE m_jenis_pelepasan_harta.ID = ID_JENIS_PELEPASAN ) AS JENIS_PELEPASAN

        ');
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_harta', 'm_jenis_harta.ID_JENIS_HARTA = ' . $TABLE . '.KODE_JENIS ');
        $this->db->join('m_mata_uang', 'm_mata_uang.ID_MATA_UANG = ' . $TABLE . '.MATA_UANG');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_harta_kas')->result();
        return $data;
    }

    function hutang($ID_LHKPN) {
        $TABLE = 't_lhkpn_hutang';
        $PK = 'ID_HUTANG';
        $this->db->where($TABLE . '.ID_LHKPN', $ID_LHKPN);
        $this->db->join('m_jenis_hutang', 'm_jenis_hutang.ID_JENIS_HUTANG = ' . $TABLE . '.KODE_JENIS');
        $this->db->join('t_lhkpn', 't_lhkpn.ID_LHKPN = ' . $TABLE . '.ID_LHKPN');
        $this->db->group_by($TABLE . '.' . $PK);
        $this->db->order_by($PK, 'DESC');
        $data = $this->db->get('t_lhkpn_hutang')->result();
        return $data;
    }

    function penerimaan($ID_LHKPN) {
        $this->db->join('m_jenis_penerimaan_kas', 'm_jenis_penerimaan_kas.nama = t_lhkpn_penerimaan_kas2.jenis_penerimaan', 'left');
        $this->db->where('m_jenis_penerimaan_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_penerimaan_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_penerimaan_kas2')->result();
        return $data;
    }

    function pengeluaran($ID_LHKPN) {
        $this->db->join('m_jenis_pengeluaran_kas', 'm_jenis_pengeluaran_kas.nama = t_lhkpn_pengeluaran_kas2.jenis_pengeluaran', 'left');
        $this->db->where('m_jenis_pengeluaran_kas.IS_ACTIVE', '1');
        $this->db->where('t_lhkpn_pengeluaran_kas2.ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_pengeluaran_kas2')->result();
        return $data;
    }

    function fasilitas($ID_LHKPN) {
        $this->db->where('ID_LHKPN', $ID_LHKPN);
        $data = $this->db->get('t_lhkpn_fasilitas')->result();
        return $data;
    }

}
