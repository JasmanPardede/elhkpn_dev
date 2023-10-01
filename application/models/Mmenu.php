<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Model Menu
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Models
 */
?>
<?php

class Mmenu extends CI_Model {

    private $table = 'T_MENU';
    private $t_menu = 'T_MENU';
    private $t_user_akses = 'T_USER_AKSES';
    private $t_role = 'T_ROLE';
    private $t_user = 'T_USER';
    var $permission = array();
    
    public $dpg = FALSE;

    function __construct() {
        parent::__construct();
        $id_user = $this->session->userdata('ID_USER');
        $this->permission = $this->makses->get_permission_by_user($id_user);
    }

    function get_list_menu($id_parent = 0) {
        $id_user_role = $this->session->userdata('ID_ROLE');
        $this->db->where('IS_SHOWED <> 0');
        $this->db->from($this->t_menu)
                ->where('PARENT', $id_parent)->where('IS_ACTIVE', 1)->order_by('WEIGHT');
        $execute = $this->db->get();
        $output = ($id_parent == 0) ? '<ul class="sidebar-menu ">' : '<ul class="treeview-menu" >';
        $link = ($id_parent == 0) ? '#' : '<ul class="treeview-menu">';
//        $output .= '<li class="active treeview">';
//        // $output .= '<a class="ajax-link" href="'.base_url().'"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>';
//        $output .= '</li>';
        if ($execute->num_rows() > 0) {
            $data = $execute->result();
            
            foreach ($data as $menu) {
                // if($menu->IS_ACTIVE!=1){continue;}
                $link = ($id_parent == 0) ? 'href=""' : 'href="' . 'index.php/' . @$menu->MODULE . '/' . $menu->CONTROLLER . '/' . $menu->METHOD . '"';
//                $link  = 'href="'.'index.php/'.@$menu->MODULE.'/'.$menu->CONTROLLER.'/'.$menu->METHOD.'"';
                $check_hak_akses = $this->check_akses_menu($menu->ID_MENU);
                if ($check_hak_akses) {
                    $this->db->where('IS_SHOWED <> 0');
                    $this->db->from($this->t_menu)->where('PARENT', $menu->ID_MENU);
                    $check_submenu = $this->db->count_all_results();
                    $class_li = ($check_submenu) ? 'class="treeview"' : NULL;

                    if($this->dpg && !$id_parent && !is_null($class_li) && make_secure_text(strtolower(trim($menu->MENU))) == strtolower(trim($this->dpg))){
                        $class_li = 'class="treeview active"';
                    }
                    $dropdown = ($check_submenu) ? '<i class="fa fa-angle-left pull-right"></i>' : NULL;
                    $output .= '<li ' . $class_li . '>';
                    $output .= '<a class="ajax-link" ' . $link . '><i class="fa ' . ($menu->ICON ? $menu->ICON : '') . '"></i>'
                            . '<span>' . $menu->MENU . '</span>'
                            . $dropdown . '</a>';

                    if ($check_submenu) {
                        $output .= $this->get_list_menu($menu->ID_MENU);
                    } else {
                        $output .= '</li>';
                    }
                }
            }
        }
        $output .= '</ul>';
        return $output;
    }

    function get_edit_menu($id_parent, $data_permission, &$lvl = 0) {
        $output = '';
        $id_user_role = $this->session->userdata('ID_ROLE');
        $id_parent = !isset($id_parent) ? 0 : $id_parent;

        $this->db->from($this->t_menu)
                ->where('PARENT', $id_parent)->order_by('WEIGHT');
        $execute = $this->db->get();
        if ($execute->num_rows() > 0) {
            $data = $execute->result();
            foreach ($data as $menu) {
                $id_menu = $menu->ID_MENU;
                $this->db->from($this->t_menu)->where('PARENT', $menu->ID_MENU);
                $check_submenu = $this->db->count_all_results();

                $checked_read = array_key_exists($menu->ID_MENU, $data_permission) ? 'checked' : NULL;

                $checked_write = '';
                if (!empty($checked_read) && array_key_exists('IS_WRITE', $data_permission[$id_menu])) {
                    if ($data_permission[$id_menu]['IS_WRITE'] == 1)
                        $checked_write = 'checked';
                }
                $output .= '<tr>';
                $output .= '<td>' . str_repeat('__', $lvl) . '<i class="fa ' . $menu->ICON . '"></i> ' . $menu->MENU . '</td>';
                $value = $menu->ID_MENU;
                $output .= '<td style="text-align: center;">';
                $output .= '<input name="PERMISSION_READ[]" value="' . $value . '"'
                        . ' type="checkbox" id="read_' . $menu->ID_MENU . '" onclick="select_write(this)" ' . $checked_read . '>';
                $output .= '</td>';
                $output .= '<td style="text-align: center;">';
                $output .= '<input name="PERMISSION_WRITE[]" value="' . $menu->ID_MENU . '"'
                        . ' type="checkbox" id="' . $menu->ID_MENU . '" onclick="select_read(this)" ' . $checked_write . '>';
                $output .= '</td>';
                $output .= '</tr>';

                if ($check_submenu) {
                    ++$lvl;
                    $output .= $this->get_edit_menu($menu->ID_MENU, $data_permission, $lvl);
                }
            }
            $lvl = 0;
        }
        return $output;
    }

    function get_data_menu($id_menu, $select, $output = 'object') {
        $this->db->select($select)
                ->from($this->t_menu)
                ->where('ID_MENU', $id_menu);
        $execute = $this->db->get();
        if ($execute->num_rows() > 0) {
            if ($output == 'object')
                return $execute->row();
            else if ($output == 'array')
                return $execute->row_array();
        }
        return false;
    }

    function get_id_submenu($id_parent = 0) {
        $result = array();
        if (!empty($id_parent)) {
            $this->db->select('ID_MENU')->from($this->t_menu)->where('PARENT', $id_parent);
            $get = $this->db->get();
            if ($get->num_rows() > 0) {
                $data = $get->result();
                foreach ($data as $dt) {
                    $result[] = $dt->ID_MENU;
                }
            }
        }
        return $result;
    }

    function check_akses_menu($id_menu) {
        $id_role = $this->session->userdata('ID_ROLE');
        if (array_key_exists($id_menu, $this->permission) || $id_role == ID_ROLE_ADMAPP)
            return true;
        else
            return false;
    }

    function list_all() {
        $this->db->order_by('ID_MENU', 'asc');
        return $this->db->get($this->table);
    }

    function count_all($filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        return $this->db->get($this->table)->num_rows();
        // return $this->db->count_all($this->table);
    }

    function get_paged_list($limit = 10, $offset = 0, $filter = '') {
        if (is_array($filter)) {
            $useLike['NAMA'] = 'both';
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $useLike)) {
                    $this->db->or_like($key, $value, $useLike[$key]);
                } else {
                    $this->db->or_where($key, $value);
                }
            }
        }
        $this->db->order_by('ID_MENU', 'asc');
        return $this->db->get($this->table, $limit, $offset);
    }

    function get_by_id($id) {
        $this->db->where('ID_MENU', $id);
        return $this->db->get($this->table);
    }

    function save($MENU) {
        $this->db->insert($this->table, $MENU);
        return $this->db->insert_id();
    }

    function update($id, $MENU) {
        $this->db->where('ID_MENU', $id);
        $this->db->update($this->table, $MENU);
    }

    function delete($id) {
        $this->db->where('ID_MENU', $id);
        $this->db->delete($this->table);
    }

}

?>