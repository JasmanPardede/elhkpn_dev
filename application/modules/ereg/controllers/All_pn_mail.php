<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class All_pn_mail extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('mglobal');
        $this->load->model('muser');
        $this->load->model('mpn');
    }

    function send_update($id, $idj, $content) {
        $password = $content;

        if ($password) {
            echo "1";

            list($data_pn, $user_uk) = $this->get_data_pn_dan_uk($id, $idj);


            if ($data_pn && $user_uk) {
                $data_pn = current($data_pn);

                $data_update = array(
                        'EMAIL' => $data_pn->EMAIL,
                        'password' => sha1(md5($password)),
                        'NAMA' => $data_pn->NAMA,
                        'IS_FIRST' => '1',
                        'IS_ACTIVE' => 0,
                        'ID_ROLE' => '5'
                    );
                
                $cUsername = ($user_uk->USERNAME == "") ? $data_pn->NIK : $user_uk->USERNAME;

                $this->muser->kirim_info_akun($user_uk->EMAIL, $cUsername, $password, $user_uk->NAMA, $user_uk->INST_NAMA, $user_uk->ID_USER, $user_uk->PASSWORD);
            }
        }
        echo "0";
    }

    private function get_data_pn_dan_uk($id, $idj) {
        $this->db->trans_begin();
        $user_uk = FALSE;
        $data_pn = $this->mglobal->get_data_by_id('t_pn', 'ID_PN', $id);

        if ($data_pn) {
            $this->db->join('m_inst_satker', 'm_inst_satker.INST_SATKERKD = t_user.INST_SATKERKD', 'LEFT');
            $user_uk = $this->mglobal->get_data_by_id('t_user', 'USERNAME', $data_pn[0]->NIK, 1, TRUE);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return array($data_pn, $user_uk);
    }

    function send_approve($id, $idj, $content) {

        $password = $content;

        if ($password) {
            echo "1";

            list($data_pn, $user_uk) = $this->get_data_pn_dan_uk($id, $idj);

            if ($data_pn && $user_uk) {
                $data_pn = current($data_pn);
                $cUsername = ($user_uk->USERNAME == "") ? $data_pn->NIK : $user_uk->USERNAME;
                $this->muser->kirim_info_akun($user_uk->EMAIL, $cUsername, $password, $user_uk->NAMA, $user_uk->INST_NAMA, $user_uk->ID_USER, $user_uk->PASSWORD);
            }
        }
        echo "0";
    }

}