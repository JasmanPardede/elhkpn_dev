<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/API_Controller.php';

class Api extends API_Controller { 

      public function __construct() {
          
         parent::__construct();

         header("Access-Control-Allow-Origin: *");

        // API Configuration
        $this->_apiConfig([
            /**
             * By Default Request Method `GET`
             */
            'methods' => ['POST'], // 'GET', 'OPTIONS'

            /**
             * Number limit, type limit, time limit (last minute)
             */
            // 'limit' => [5, 'ip', 'everyday'],

            /**
             * type :: ['header', 'get', 'post']
             * key  :: ['table : Check Key in Database', 'key']
             */
            'key' => ['header', $this->key() ], // type, {key}|table (by default)
        ]);

        $this->load->library('form_validation');
        $this->load->model("Mglobal", "mglobal");
  
      }
      
      
      /**
       * Check API Key
       *
       * @return key|string
       */
      private function key()
      {
        $CI = & get_instance();
        return $CI->config->item('api_key_elo_lhkpn');
      }

      public function server_error(){
        $this->api_return(
          [
              'status' => false,
              'message' => "Internal Server Error"
          ],
        500);
      }

      public function updatesk_elo(){
        $this->form_validation->set_rules('nik_pn','nik_pn','required', array('required' => 'field %s wajib diisi'));
        $nik_pn = $this->input->post('nik_pn');
        $nama_keluarga = $this->input->post('nama_keluarga');

        $data_pribadi = null;

        if($this->form_validation->run() == TRUE){

          $this->db->select('ID_PN');
          $this->db->from('t_pn');
          $this->db->where('NIK', $nik_pn);
          $this->db->where('IS_ACTIVE', 1);
          $query = $this->db->get();
          $data_pn = $query->row();

          if($data_pn){

              $this->db->select('ID_LHKPN');
              $this->db->from('t_lhkpn');
              $this->db->where('ID_PN', $data_pn->ID_PN);
              $this->db->where('IS_ACTIVE', 1);
              $this->db->order_by("ID_LHKPN", "desc");
              $query = $this->db->get();
              $data_lhkpn = $query->row();

              if($data_lhkpn){

                  $this->db->select('*');
                  $this->db->from('t_lhkpn_data_pribadi');
                  $this->db->where('ID_LHKPN', $data_lhkpn->ID_LHKPN);
                  $this->db->where('IS_ACTIVE', 1);
                  $this->db->order_by("ID", "desc");
                  $this->db->limit(0, 1);
                  $query = $this->db->get();
                  $data_pribadi  = $query->row();

              }

          }
  
          if($data_pribadi){

            if(!is_null($nama_keluarga) && $nama_keluarga != ''){
                $this->db->select('*');
                $this->db->from('t_lhkpn_keluarga');
                $this->db->where('IS_ACTIVE', 1);
                $this->db->where('ID_LHKPN', $data_pribadi->ID_LHKPN);
                $this->db->where('NAMA', $nama_keluarga);
                $query = $this->db->get();
                $data_keluarga = $query->row();

                if($data_keluarga){
                  $this->updateSkKeluarga($data_keluarga->ID_KELUARGA);
                }else{
                  $this->api_return(
                    [
                        'status' => false,
                        'message' => "Nama Keluarga tidak ditemukan"
                    ],
                  404);
                }

            }else{
                $this->updateSkPN($data_pribadi->ID_LHKPN);
            }
            

          }else{
            $this->api_return(
              [
                  'status' => false,
                  'message' => "NIK tidak ditemukan"
              ],
            404);
          } 

        }else {

            if(validation_errors() == ''){
              $this->api_return(
                [
                    'status' => false,
                    'message' => "Need nik_pn parameter"
                ],
                400);
            }else{
              $this->api_return(
                [
                    'status' => false,
                    'message' => str_replace("\n","", strip_tags(validation_errors())),
                ],
                400);
            }
          
        }
        
      }

      public function updateSkPN($id_lhkpn){
        try{
  
          $data['FLAG_SK'] = '1';
          $this->mglobal->update_data('t_lhkpn_data_pribadi', $data, 'id_lhkpn', $id_lhkpn);

          $this->api_return(
            [
                'status' => true,
                'message' => "Flag SK PN berhasil diupdate"
            ],
          200);

        }catch(Exception $e){

          $this->server_error();

        }

      }

      public function updateSkKeluarga($id_keluarga){
        try {

          $data['FLAG_SK'] = '1';
          $this->mglobal->update_data('t_lhkpn_keluarga', $data, 'ID_KELUARGA', $id_keluarga);

          $this->api_return(
            [
                'status' => true,
                'message' => "Flag SK Keluarga berhasil diupdate"
            ],
          200);

         }catch(Exception $e){

            $this->server_error();

         }

      }
}