<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_rpt_lhkpn extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
        $this->load->model('mglobal');
		$this->load->model('mlhkpn');
		$this->username    = $this->session->userdata('USERNAME');
		$this->makses->initialize();
		$this->makses->check_is_read();        
		$this->uri_segment = 5;
		$this->offset      = $this->uri->segment($this->uri_segment);
		$this->limit       = 10;
        
        // prepare search
        foreach ((array)@$this->input->post('CARI') as $k => $v)
            $this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"];        
        
        $this->act = $this->input->post('act', TRUE);
        // prepare search
		foreach ((array)@$this->input->post('CARI') as $k => $v)
		$this->CARI["{$k}"] = $this->input->post('CARI')["{$k}"]; 
    }

	public function index()
	{
		$data = '';
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}
	
	public function harta_kekayaan()
	{
		

		//$items = $this->mglobal->get_data_all('T_LHKPN', null, ['IS_ACTIVE' => '1', 'STATUS' => '3']);
		$CARI = '';
		$jenis = '';
		$nama_pn = '';
		$nik = '';
		$thn = '';
		$cari = $this->input->post('CARI');
		if(isset($cari)){
			$thn = $cari['TAHUN'];
			$nama = $cari['NAMA'];
			$qNama = $this->mglobal->get_data_all('T_PN', null, ['IS_ACTIVE' => '1', 'NAMA like ' => "%$nama%"]);			
			if($qNama!=NULL){
				$nama_pn = $qNama[0]->NAMA;
				$nik = $qNama[0]->NIK;
			}
			if(isset($cari['JENIS'])){
				$jenis = $cari['JENIS'];
			}
		}
		$data = array(
				//'items' => $items,
				
				'thn' => $thn,
				'CARI' => $cari,
				'nama_pn' => $nama_pn,
			);
			
		$data['hartirak_1']                 = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HARTA_TIDAK_BERGERAK','NILAI_PELAPORAN','sum_hartirak');
		$data['hartirak_2']                 = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HARTA_TIDAK_BERGERAK','NILAI_PELAPORAN','sum_hartirak');
		
		$data['harger_1']                   = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HARTA_BERGERAK','NILAI_PELAPORAN','sum_harger');
		$data['harger_2']                   = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HARTA_BERGERAK','NILAI_PELAPORAN','sum_harger');
		
		$data['harger2_1']                  = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HARTA_BERGERAK_LAIN',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harger2');
		$data['harger2_2']                  = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HARTA_BERGERAK_LAIN',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harger2');
		
		$data['suberga_1']                  = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HARTA_SURAT_BERHARGA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_suberga');
		$data['suberga_2']                  = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HARTA_SURAT_BERHARGA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_suberga');
		
		$data['kaseka_1']                   = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HARTA_KAS',"REPLACE(NILAI_EQUIVALEN,'.','')",'sum_kaseka');
		$data['kaseka_2']                   = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HARTA_KAS',"REPLACE(NILAI_EQUIVALEN,'.','')",'sum_kaseka');
		
		$data['harlin_1']                   = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HARTA_LAINNYA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harlin');
		$data['harlin_2']                   = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HARTA_LAINNYA',"REPLACE(NILAI_PELAPORAN,'.','')",'sum_harlin');
		
		$data['_hutang_1']                  = $this->mlhkpn->summaryHartaReport($nik, $thn-1, $jenis, 'T_LHKPN_HUTANG','SALDO_HUTANG','sum_hutang');
		$data['_hutang_2']                  = $this->mlhkpn->summaryHartaReport($nik, $thn, $jenis, 'T_LHKPN_HUTANG','SALDO_HUTANG','sum_hutang');
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
	}

	public function perubahan_harta()
	{
		$data = '';
		$data['CARI'] = @$this->CARI;

        if(isset($this->CARI)) {
            $lhkpn = $this->mglobal->get_data_all('T_LHKPN', NULL, ['ID_PN' => $this->CARI['PN'], 'JENIS_LAPORAN' => $this->CARI['JENIS']], 'ID_LHKPN', "YEAR(TGL_LAPOR) = '".$this->CARI['TAHUN']."'");
            if(!empty($lhkpn)){
                $id_lhkpn = $lhkpn[0]->ID_LHKPN;


            }

            $data['report'] = $this->load->view(strtolower(__CLASS__) . '/' . strtolower(__CLASS__) . '_' . strtolower(__FUNCTION__) . '_form', $data, TRUE);
        }
		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__).'_index', $data);
	}

	public function penerimaan_pengeluaran(){
		$CARI = '';
		$jenis = '';
		$nama_pn = '';
		$id_pn = '';
		$thn = '';
		$cari = $this->input->post('CARI');
		if(isset($cari)){
			$thn = $cari['TAHUN'];
			$nama = $cari['NAMA'];
			$qNama = $this->mglobal->get_data_all('T_PN', null, ['IS_ACTIVE' => '1', 'NAMA like ' => "%$nama%"]);			
			if($qNama!=NULL){
				$nama_pn = $qNama[0]->NAMA;
				$id_pn = $qNama[0]->ID_PN;
			}
			if(isset($cari['JENIS'])){
				$jenis = $cari['JENIS'];
			}
		}
		$data = array(
				//'items' => $items,
				
				'thn' => $thn,
				'jenis' => $jenis,
				'id_pn' => $id_pn,	
				'CARI' => $cari,
				'nama_pn' => $nama_pn,
			);

		$this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);	
	}

	public function tracking_lhkpn($action = NULL, $id = NULL){
        switch($action){
            case 'printitem':
                $this->load->model('mglobal');
                $iniJoin = [
                    ['table'=>'T_PN', 'on'=>'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join'=>'left'],
                    ['table'=>'M_INST_SATKER', 'on'=>'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join'=>'left'],
                    ['table'=>'M_JABATAN', 'on'=>'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join'=>'left'],
                    ['table'=>'M_BIDANG', 'on'=>'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join'=>'left'],
                    ['table'=>'M_UNIT_KERJA', 'on'=>'M_UNIT_KERJA.UK_ID= T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'join'=>'left']
                ];

                $joinHist = [
                    ['table'=>'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS' ],
                    ['table'=>'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT' ],
                    ['table'=>'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                ];

                $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA';

                $this->data['item']    = $this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id])[0];
                $this->data['subitem'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id], $select, NULL, ['DATE_INSERT', 'asc']);
                $this->data['barcode'] = $this->data['item']->TAHUN_PELAPORAN.'/'.($this->data['item']->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$this->data['item']->NIK.'/'.$this->data['item']->ID_LHKPN;
                $html                  = $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_tracking_print', $this->data, true);

                $this->load->library('pdf');
                $pdf = $this->pdf->load();
                $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->WriteHTML($html); // write the HTML into the PDF
                // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
                $pdf->Output();
                break;
            case 'show':
                $iniJoin = [
                    ['table'=>'T_PN', 'on'=>'T_PN.ID_PN = T_LHKPNOFFLINE_PENERIMAAN.ID_PN', 'join'=>'left'],
                    ['table'=>'M_INST_SATKER', 'on'=>'M_INST_SATKER.INST_SATKERKD = T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA', 'join'=>'left'],
                    ['table'=>'M_JABATAN', 'on'=>'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'join'=>'left'],
                    ['table'=>'M_BIDANG', 'on'=>'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'join'=>'left'],
                    ['table'=>'M_UNIT_KERJA', 'on'=>'M_UNIT_KERJA.UK_ID= T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'join'=>'left'],
                    ['table'=>'T_LHKPN', 'on'=>'T_LHKPN.ID_LHKPN= T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'join'=>'left']
                ];

                $item   = @$this->mglobal->get_data_all('T_LHKPNOFFLINE_PENERIMAAN', $iniJoin, ['SUBSTRING(md5(T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN), 6, 8) =' => $id], 'T_LHKPNOFFLINE_PENERIMAAN.*, T_PN.*, M_INST_SATKER.*, M_JABATAN.*, M_BIDANG.*, M_UNIT_KERJA.*, T_LHKPN.ID_LHKPN, T_LHKPN.JENIS_LAPORAN, YEAR(T_LHKPN.TGL_LAPOR) AS TAHUN_PELAPORAN')[0];
                if (!empty($item)) {
                	$barcode    = $item->TAHUN_PELAPORAN.'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN;
                } else {
                	$newitem = $this->mglobal->get_data_all('T_LHKPN', [['table' => 'T_PN', 'on' => 'T_PN.ID_PN = T_LHKPN.ID_PN']], ['SUBSTRING(md5(ID_LHKPN), 6, 8) =' => $id, 'T_LHKPN.IS_ACTIVE' => '1'], 'TGL_LAPOR, JENIS_LAPORAN, NIK, ID_LHKPN')[0];
                	$barcode = date('Y', strtotime($newitem->TGL_LAPOR)).'/'.($newitem->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$newitem->NIK.'/'.$newitem->ID_LHKPN;
                }

                $this->base_url = site_url('ereport/'.strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');

                $this->data = [];

                $this->db->start_cache();
                $this->db->select('*');
                $this->db->from('T_LHKPN');
                $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'left');
                $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
                $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'left');
                $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'left');

                $xdata = $barcode;
                $exp = explode('/', $xdata);
                $this->db->where('T_LHKPN.ID_LHKPN', $exp[3]);
                $this->db->where('T_PN.NIK', $exp[2]);

                if (!empty($item)) {
	                $this->db->where('YEAR(T_LHKPN.TGL_LAPOR)', $exp[0]);
	                if($exp[1] == 'R' || $exp[1] == 'r'){
	                    $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN', '4');
	                }else{
	                    $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN <>', '4');
	                }
	            } else {
	            	$this->db->where('T_LHKPN.TGL_LAPOR LIKE', $exp[0].'%');
	                if($exp[1] == 'R' || $exp[1] == 'r'){
	                    $this->db->where('T_LHKPN.JENIS_LAPORAN', '4');
	                }else{
	                    $this->db->where('T_LHKPN.JENIS_LAPORAN <>', '4');
	                }
	            }

                $this->total_rows = $this->db->get('')->num_rows();

                $query            = $this->db->get();
                $this->items      = $query->result();
                $this->end        = $query->num_rows();
                // echo $this->db->last_query();
                $this->db->flush_cache();


                $this->data['items']    = $this->items;
                $this->data['id']       = $barcode;

                $this->load->model('mglobal');
                $joinHist                 = [
                    ['table'=>'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS' ],
                    ['table'=>'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT' ],
                    ['table'=>'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                ];
                $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA';
                $this->data['getHistory'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['ID_LHKPN' => @$this->items[0]->ID_LHKPN], $select, NULL, ['DATE_INSERT', 'asc']);

                $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_tracking_show', $this->data);
                break;
            default:
                $this->base_url = site_url('ereport/'.strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/');

                $this->data = [];
                $this->data['title']        = 'Tracking';
                $this->data['icon']         = 'fa-list';
                $this->data['thisPageUrl']  = $this->base_url;

                $this->db->start_cache();
                $this->db->select('*');
                $this->db->from('T_LHKPN');
                $this->db->join('T_LHKPNOFFLINE_PENERIMAAN', 'T_LHKPN.ID_LHKPN = T_LHKPNOFFLINE_PENERIMAAN.ID_LHKPN', 'left');
                $this->db->join('T_PN', 'T_LHKPN.ID_PN = T_PN.ID_PN', 'left');
                $this->db->join('M_INST_SATKER', 'T_LHKPNOFFLINE_PENERIMAAN.LEMBAGA = M_INST_SATKER.INST_SATKERKD', 'left');
                $this->db->join('M_BIDANG', 'M_BIDANG.BDG_ID = M_INST_SATKER.INST_BDG_ID', 'left');
                $this->db->join('M_JABATAN', 'M_JABATAN.ID_JABATAN = T_LHKPNOFFLINE_PENERIMAAN.JABATAN', 'left');
                $this->db->join('M_UNIT_KERJA', 'M_UNIT_KERJA.UK_ID = T_LHKPNOFFLINE_PENERIMAAN.UNIT_KERJA', 'left');

                if(empty($this->CARI['KODE'])){
                    $this->db->where('T_LHKPN.ID_LHKPN', null);
                    $this->total_rows = $this->db->get('')->num_rows();
                }else{
                    $xdata = $this->CARI['KODE'];
                    $exp = explode('/', $xdata);
                    $this->db->where('T_LHKPN.ID_LHKPN', $exp[3]);
                    $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.TAHUN_PELAPORAN', $exp[0]);
                    $this->db->where('T_PN.NIK', $exp[2]);
                    if($exp[1] == 'R' || $exp[1] == 'r'){
                        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN', '4');
                    }else{
                        $this->db->where('T_LHKPNOFFLINE_PENERIMAAN.JENIS_LAPORAN <>', '4');
                    }
                    $this->total_rows = $this->db->get('')->num_rows();

                }

                $query            = $this->db->get();
                $this->items      = $query->result();
                $this->end        = $query->num_rows();
                // echo $this->db->last_query();
                $this->db->flush_cache();

                $this->data['breadcrumb'] = call_user_func('ng::genBreadcrumb',
                    [
                        'Dashboard' => 'index.php/welcome/dashboard',
                        'E filling' => 'index.php/dashboard/efilling/',
                        'Tracking'  => $this->base_url,
                    ]
                );

                $this->data['thisPageUrl'] = $this->base_url;
                $this->data['CARI']        = @$this->CARI;
                $this->data['total_rows']  = @$this->total_rows;
                $this->data['offset']      = @$this->offset;
                $this->data['items']       = @$this->items;
                $this->data['start']       = @$this->offset + 1;
                $this->data['end']         = @$this->offset + @$this->end;
                $this->data['pagination']  = call_user_func('ng::genPagination');

                $this->load->model('mglobal');
                $joinHist                 = [
                    ['table'=>'M_STATUS_LHKPN', 'on' => 'T_LHKPN_STATUS_HISTORY.ID_STATUS = M_STATUS_LHKPN.ID_STATUS' ],
                    ['table'=>'T_USER A', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENGIRIM = A.USERNAME', 'join' => 'LEFT' ],
                    ['table'=>'T_USER B', 'on' => 'T_LHKPN_STATUS_HISTORY.USERNAME_PENERIMA = B.USERNAME', 'join' => 'LEFT']
                ];
                $select = 'T_LHKPN_STATUS_HISTORY.DATE_INSERT, M_STATUS_LHKPN.ID_STATUS, M_STATUS_LHKPN.STATUS, A.NAMA as PENGIRIM, B.NAMA as PENERIMA';
                $this->data['getHistory'] = $this->mglobal->get_data_all('T_LHKPN_STATUS_HISTORY', $joinHist, ['ID_LHKPN' => @$this->items[0]->ID_LHKPN], $select, NULL, ['DATE_INSERT', 'asc']);

                $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_tracking_index', $this->data);
                break;
        }
    }

}

/* End of file all_rpt_lhkpn.php */
/* Location: ./application/modules/ereport/controllers/all_rpt_lhkpn.php */