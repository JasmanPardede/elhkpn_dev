<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller LHKPN
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Efill/Controllers/Lhkpn_copy
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lhkpn_copy extends CI_Controller {
	// num of records per page
	public $limit = 10;
    public $username;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mglobal');
        call_user_func('ng::islogin');
        $this->username = $this->session->userdata('USERNAME');
    }

	public function index()
	{

	}

    public function copylhkpn($pn = '0', $tahun = '2015'){
        $lhkpn      = $this->check_tahun_terakhir($pn, $tahun);

        foreach ($lhkpn['data'] as $key => $value) {
            $id = $value['ID_LHKPN'];
            unset($value['ID_LHKPN']);
            $value['TGL_LAPOR']    = date('Y-m-d');
            $value['CREATED_TIME'] = date('Y-m-d H:i:s');
            $value['CREATED_BY']   = $this->username;
            $value['CREATED_IP']   = $this->input->ip_address();
            $value['UPDATED_TIME'] = '';
            $value['UPDATED_BY']   = '';
            $value['UPDATED_IP']   = '';

            $result     = $this->mglobal->insert('T_LHKPN', $value);
            if($result['status']){
                $detail    = [];

                $detail['KELUARGA']      = $this->getdatakeluarga($id);
                foreach ($detail['KELUARGA'] as $key => $value) {
                    unset($value['ID_KELUARGA']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_KELUARGA', $value);
                }

                $detail['HUTANG']        = $this->getdatahutang($id);
                foreach ($detail['HUTANG'] as $key => $value) {
                    unset($value['ID_HUTANG']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HUTANG', $value);
                }

                $detail['HARTA']                     = [];
                $detail['HARTA']['BERGERAK']         = $this->getdatahartabergerak($id);
                foreach ($detail['HARTA']['BERGERAK'] as $key => $value) {
                    unset($value['ID']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HARTA_BERGERAK', $value);
                }

                $detail['HARTA']['BERGERAK_LAIN']    = $this->getdatahartabergeraklain($id);
                foreach ($detail['HARTA']['BERGERAK_LAIN'] as $key => $value) {
                    unset($value['ID']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HARTA_BERGERAK_LAIN', $value);
                }

                $detail['HARTA']['KAS']              = $this->getdatahartakas($id);
                foreach ($detail['HARTA']['KAS'] as $key => $value) {
                    unset($value['ID']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HARTA_KAS', $value);
                }

                $detail['HARTA']['LAINNYA']          = $this->getdatahartalainnya($id);
                foreach ($detail['HARTA']['LAINNYA'] as $key => $value) {
                    unset($value['ID']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HARTA_LAINNYA', $value);
                }

                $detail['HARTA']['SURAT_BERHARHA']   = $this->getdatahartasuratberharga($id);
                foreach ($detail['HARTA']['SURAT_BERHARHA'] as $key => $value) {
                    unset($value['ID']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HARTA_SURAT_BERHARHA', $value);
                }

                $detail['HARTA']['TIDAK_BERGERAK']   = $this->getdatahartatidakbergerak($id);
                foreach ($detail['HARTA']['TIDAK_BERGERAK'] as $key => $value) {
                    unset($value['ID']);
                    $value['ID_LHKPN']     = $result['id'];
                    $value['CREATED_TIME'] = date('Y-m-d H:i:s');
                    $value['CREATED_BY']   = $this->username;
                    $value['CREATED_IP']   = $this->input->ip_address();
                    $value['UPDATED_TIME'] = '';
                    $value['UPDATED_BY']   = '';
                    $value['UPDATED_IP']   = '';

                    $this->mglobal->insert('T_LHKPN_HARTA_TIDAK_BERGERAK', $value);
                }

            }else{
                return false;
            }


        }

        // display($lhkpn);
    }

    private function check_tahun_terakhir($pn, $tahun){
        $tahun        = $tahun-1;
        // $where      = ['ID_PN' => $pn, 'year(TGL_LAPOR)' => $tahun, 'IS_ACTIVE' => '1', 'IS_VERIFIED' => '1'];
        $where      = ['ID_PN' => $pn, 'year(TGL_LAPOR)' => $tahun, 'IS_ACTIVE' => '1'];

        $data = $this->mglobal->get_data_all_array('T_LHKPN', NULL, $where, '*');

        if(empty($data)){
            return $this->check_tahun_terakhir($pn, $tahun);
        }else{
            $res['data']    = $data;
            $res['tahun']   = $tahun;

            return $res;
        }
    }

    private function getdatakeluarga($id){
        $data   = $this->mglobal->get_data_all_array('T_LHKPN_KELUARGA', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahutang($id){
        $data   = $this->mglobal->get_data_all_array('T_LHKPN_HUTANG', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahartabergerak($id){
        $data   = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahartabergeraklain($id){
        $data   = $this->mglobal->get_data_all('T_LHKPN_HARTA_BERGERAK_LAIN', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahartakas($id){
        $data   = $this->mglobal->get_data_all('T_LHKPN_HARTA_KAS', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahartalainnya($id){
        $data   = $this->mglobal->get_data_all('T_LHKPN_HARTA_LAINNYA', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahartasuratberharga($id){
        $data   = $this->mglobal->get_data_all('T_LHKPN_HARTA_SURAT_BERHARGA', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }

    private function getdatahartatidakbergerak($id){
        $data   = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK', NULL, ['ID_LHKPN' => $id, 'IS_ACTIVE' => '1']);
        return $data;
    }
}
