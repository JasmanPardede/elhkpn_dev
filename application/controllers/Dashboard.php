<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Controller Dashboard
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia 
 * @package Controllers
 */
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        call_user_func('ng::islogin');
    }

    /** Dashboard Administrator
     * 
     * @return html dashboard Administrator
     */
    public function administrator(){

        $USER = $this->db->query('SELECT COUNT(*) AS JML FROM T_USER')->row();
        $INSTANSI = $this->db->query('SELECT COUNT(*) AS JML FROM M_INST_SATKER')->row();
        $JABATAN = $this->db->query('SELECT COUNT(*) AS JML FROM M_JABATAN')->row();

        $sql = "SELECT ID_ROLE, COUNT(*) AS JML FROM T_USER GROUP BY ID_ROLE";
        $USERPERROLE = $this->db->query($sql)->result();
        
        $data = array(
            'USER'      => $USER,
            'INSTANSI'  => $INSTANSI,
            'JABATAN'   => $JABATAN,
            'USERPERROLE' => $USERPERROLE,
        );
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }
    
    /** Dashboard ERegistration
     * 
     * @return html dashboard ERegistration
     */    
    public function eregistration(){
        $this->load->model('mglobal', '', TRUE);

        $check      = $this->mglobal->get_data_all('T_USER_ROLE', null, ['ID_ROLE' => $this->session->userdata('ID_ROLE')], 'IS_INSTANSI, IS_KPK, IS_USER_INSTANSI');
        $kpk        = $this->db->query('SELECT `inst`.`INST_NAMA`, ( SELECT COUNT(*) FROM T_USER AS USER WHERE USER .IS_ACTIVE = "1" AND `USER`.ID_ROLE != "5" AND `USER`.INST_SATKERKD = inst.INST_SATKERKD ) AS jumlah, ( SELECT COUNT(*) FROM T_USER AS USER WHERE USER .IS_ACTIVE = "1" AND USER .ID_ROLE IN (1, 2, 3) AND USER .INST_SATKERKD = inst.INST_SATKERKD ) AS `admin`, ( SELECT COUNT(*) FROM T_USER AS USER WHERE USER .IS_ACTIVE = "1" AND `USER`.ID_ROLE = "4" AND `USER`.INST_SATKERKD = inst.INST_SATKERKD ) AS user FROM `M_INST_SATKER` AS `inst` WHERE `IS_ACTIVE` = "1" ORDER BY `jumlah` DESC LIMIT 10');
        $inst       = $this->db->query('SELECT `inst`.`INST_NAMA`, ( SELECT COUNT(*) FROM T_USER AS USER WHERE USER .IS_ACTIVE = "1" AND `USER`.ID_ROLE != "5" AND `USER`.INST_SATKERKD = inst.INST_SATKERKD ) AS jumlah, ( SELECT COUNT(*) FROM T_USER AS USER WHERE USER .IS_ACTIVE = "1" AND USER .ID_ROLE IN (1, 2, 3) AND USER .INST_SATKERKD = inst.INST_SATKERKD ) AS `admin`, ( SELECT COUNT(*) FROM T_USER AS USER WHERE USER .IS_ACTIVE = "1" AND `USER`.ID_ROLE = "4" AND `USER`.INST_SATKERKD = inst.INST_SATKERKD ) AS user FROM `M_INST_SATKER` AS `inst` WHERE INST_NAMA = "'.$this->session->USERdata('INST_NAMA').'" AND `IS_ACTIVE` = "1" ORDER BY `jumlah` DESC LIMIT 10');
        $admin_inst = $this->db->query('SELECT USERNAME, ( SELECT COUNT(*) FROM T_USER_LOG WHERE NIP = USERNAME ) AS jumlah FROM T_USER WHERE ID_ROLE = "3" AND INST_SATKERKD = "'.$this->session->userdata('INST_SATKERKD').'" AND IS_ACTIVE = "1" ORDER BY jumlah DESC');
        $user_inst  = $this->db->query('SELECT USERNAME, ( SELECT COUNT(*) FROM T_USER_LOG WHERE NIP = USERNAME ) AS jumlah FROM T_USER WHERE ID_ROLE = "4" AND INST_SATKERKD = "'.$this->session->userdata('INST_SATKERKD').'" AND IS_ACTIVE = "1" ORDER BY jumlah DESC');

        $data = array(
            'data_iskpk'      => $kpk->result(),
            'data_instansi'   => $inst->result(),
            'admin_inst'      => $admin_inst->result(),
            'user_inst'       => $user_inst->result(),
            'check'           => $check,
        );
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data );

    }

    /** Dashboard EFilling
     * 
     * @return html dashboard EFilling
     */
    public function efilling(){
        $data = array(
            
        );
        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }    

    /** Dashboard EVerification
     * 
     * @return html dashboard EVerification
     */
    public function everification(){
        $data = array(

        );
        $this->load->model('mglobal');

        $data['JML_LHKPN'] = $this->mglobal->get_data_all('T_LHKPN', NULL, NULL, 'COUNT(*) JML')[0];


        $this->load->view(strtolower(__CLASS__).'_'.strtolower(__FUNCTION__), $data);
    }
    
    /** Dashboard ELetter
     * 
     * @return html dashboard ELetter
     */    
    public function eletter(){

    }
}