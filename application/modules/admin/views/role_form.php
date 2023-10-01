<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/role
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <form method="post" id="ajaxFormAdd" action="index.php/admin/role/saverole">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Is Active <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <label><input type="radio" name="IS_ACTIVE" value="1" class="ubahCalon" required> Active</label>
                        <label><input type="radio" name="IS_ACTIVE" value="0" class="ubahCalon" required> inActive</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Role <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input type='text' class="form-control" placeholder="Nama Role" name="ROLE" required> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Deskripsi <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <textarea name="DESCRIPTION" class="form-control"></textarea>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS PN :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" name="IDENTIFIER[]" value="IS_PN">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS INSTANSI :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" name="IDENTIFIER[]" value="IS_INSTANSI">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS USER INSTANSI :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" name="IDENTIFIER[]" value="IS_USER_INSTANSI">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS KPK :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" name="IDENTIFIER[]" value="IS_KPK">
                    </div>
                </div>                
				<div class="form-group">
                    <label class="col-sm-4 control-label">Colour :</label>
                    <div class="col-sm-5">
                        <select name="color">
						<option style="background-color:white; color:#FFF" >Select Color</option>
						<option style="background-color:red; color:#FFF" value="red">RED</option>
						<option style="background-color:green; color:#FFF" value="green">GREEN</option>
						<option style="background-color:olive; color:#FFF" value="olive">OLIVE</option>
						<option style="background-color:blue; color:#FFF" value="blue">BLUE</option>
						</select>
                    </div>
                </div> 
			</div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form method="post" id="ajaxFormEdit" action="index.php/admin/role/saverole">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Is Active <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <label><input type="radio" name="IS_ACTIVE" value="1" <?php echo $item->IS_ACTIVE==1?'checked':'';?>> Active</label>
                        <label><input type="radio" name="IS_ACTIVE" value="0" <?php echo $item->IS_ACTIVE==0?'checked':'';?>> inActive</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Role <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input type='text' class="form-control" placeholder="Nama Role" name="ROLE" value="<?php echo $item->ROLE;?>" required> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Deskripsi <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <textarea name="DESCRIPTION" class="form-control"><?php echo $item->DESCRIPTION;?></textarea>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS PN :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" class="isCheck" name="IS_PN" value="<?=$item->IS_PN?>" <?php echo $item->IS_PN==1?'checked':'';?>>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS INSTANSI :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" class="isCheck" name="IS_INSTANSI" value="<?=$item->IS_INSTANSI?>" <?php echo $item->IS_INSTANSI==1?'checked':'';?>>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS USER INSTANSI :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" class="isCheck" name="IS_USER_INSTANSI" value="<?=$item->IS_USER_INSTANSI?>" <?php echo $item->IS_USER_INSTANSI==1?'checked':'';?>>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">IS KPK :</label>
                    <div class="col-sm-5">
                        <input type="checkbox" class="isCheck" name="IS_KPK" value="<?=$item->IS_KPK?>" <?php echo $item->IS_KPK==1?'checked':'';?>>
                    </div>
                </div>   
				<div class="form-group">
                    <label class="col-sm-4 control-label">Colour :</label>
                    <div class="col-sm-5">
                        <select name="color">
						<option style="background-color:white; color:#FFF" >Select Color</option>
						<option style="background-color:red; color:#FFF" value="red">RED</option>
						<option style="background-color:green; color:#FFF" value="green">GREEN</option>
						<option style="background-color:olive; color:#FFF" value="olive">OLIVE</option>
						<option style="background-color:blue; color:#FFF" value="blue">BLUE</option>
						</select>
                    </div>
                </div> 				
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doupdate">
            <input type="hidden" name="ID_ROLE" value="<?php echo $item->ID_ROLE;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.isCheck').change(function(){
            var cek = $(this).prop('checked');
            if(cek == true){
                $(this).val('1');
            }else{
                $(this).val('0');
            }
        });
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Role dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/admin/role/saverole">
        <table>
            <tr>
                <td valign="top">Nama Role</td>
                <td>:</td>
                <td><?php echo $item->ROLE;?></td>
            </tr>
            <tr>
                <td>Is Active</td>
                <td>:</td>
                <td>
                <?php echo $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                </td>
            </tr>
        </table>
        <div class="pull-right">
            <input type="hidden" name="act" value="dodelete">
            <input type="hidden" name="ID_ROLE" value="<?php echo $item->ID_ROLE;?>">
            <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-save"></i> Hapus</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<?php
}
?>
<?php
if($form=='editrolesetting'){
?>

ROLE : <?php echo $role->ROLE;?><br>
<form method="post" action="index.php/admin/role/saverole" id="ajaxFormRoleSetting">

<?php
/** View Tree Menu Role
 * 
 * @todo Marge with other location different parent and subtree view
 * @package Views
 */
class mytreemenu
{
    
    function __construct($ID_ROLE)
    {
        $CI = get_instance();
        $cari = $CI->input->post('cari', TRUE)?$CI->input->post('cari', TRUE):'';
        $sql = "SELECT 0 AS parent, GROUP_CONCAT( c.ID_MENU ORDER BY c.WEIGHT) AS children
         FROM T_MENU AS c 
         WHERE c.PARENT = 0
         GROUP BY c.PARENT 
        UNION
        SELECT p.ID_MENU AS parent, GROUP_CONCAT( c.ID_MENU ORDER BY c.WEIGHT) AS children
         FROM T_MENU AS p
         JOIN T_MENU AS c ON c.PARENT = p.ID_MENU
         WHERE p.MENU LIKE '%$cari%' OR p.SYSNAME LIKE '%$cari%' OR c.MENU LIKE '%$cari%' OR c.SYSNAME LIKE '%$cari%'
         GROUP BY p.ID_MENU
        ";
        // echo $sql;
        $res = $CI->db->query($sql);
        $parents = Array();
        foreach ($res->result() as $row){
            $parents[$row->parent] = explode(',',$row->children);   
        }
        $this->parents = $parents;

        $sql = "SELECT * FROM T_MENU WHERE IS_ACTIVE = 1";
        $res2 = $CI->db->query($sql);
        $rows = Array();
        foreach ($res2->result() as $row){
            $rows[$row->ID_MENU] = $row;
        }  
        $this->rows = $rows;    


        $this->ID_ROLE = $ID_ROLE;
        $sql = "SELECT * FROM T_USER_AKSES WHERE ID_ROLE = '".$ID_ROLE."'";
        $rs = $CI->db->query($sql);

        $this->ROLE_AKSES = array();
        foreach ($rs->result() as $row){
            $this->ROLE_AKSES[$row->ID_ROLE][$row->ID_MENU]['IS_READ']= $row->IS_READ;
            $this->ROLE_AKSES[$row->ID_ROLE][$row->ID_MENU]['IS_WRITE']= $row->IS_WRITE;
        }
        // echo '<pre>';
        // print_r($ROLE_AKSES);
        // echo '</pre>';
    }


    function setSettings($settings){
        $this->settings = $settings;
    }

    function subtree($id, $n=0) { 
        $parents = $this->parents;
        // print_r($this->rows);
        $rows = $this->rows;
        $ulclass = $liclass = $aclass = '';
        if($id>0){
            if(!isset($rows[$id])){return;}
            $row = $rows[$id];
            if (isset($parents[$id])){$ulclass='dropdown-menu';$liclass='dropdown';$aclass='dropdown-toggle';}else{$aclass='ajax-link';}
            // echo '<li class="'.$liclass.'">';
            // echo '<a href="'.(isset($row->URL)?$row->URL:'#')
            // .'" class="'.$aclass.'"><i class="fa '
            // .(isset($row->ICON)?$row->ICON:'')
            // .'" style="color:'.$row->ICON_COLOR.'"></i> '
            // .$row->MENU.'</a>';
            ?>
            <tr>
                <td><?php echo str_repeat('__', $n);?> <i class="fa <?php echo $row->ICON; ?>" style="color:<?php echo $row->ICON_COLOR; ?>;"></i> <?php echo $row->MENU; ?></td>
                <td width="100">
<!--                     <label><input type="radio" class="yes" name="<?php echo $row->SYSNAME; ?>"
                                                          value="1" <?php if (getUserSetting($row->SYSNAME, $this->settings->result()) == 1) {
                                                    echo 'checked';
                                                } ?>>Yes</label>
                    <label><input type="radio" class="no" name="<?php echo $row->SYSNAME; ?>"
                                                          value="0" <?php if (getUserSetting($row->SYSNAME, $this->settings->result()) == 0) {
                                                    echo 'checked';
                                                } ?>>No</label> -->
                    <input type="checkbox" name="MENU[<?php echo $row->ID_MENU; ?>][IS_READ]" value="1" <?php if(isset($this->ROLE_AKSES[$this->ID_ROLE][$row->ID_MENU]['IS_READ']) && $this->ROLE_AKSES[$this->ID_ROLE][$row->ID_MENU]['IS_READ']==1){echo 'checked';}?>>
                </td>                
                <td width="100">
<!--                     <label><input type="radio" class="yes" name="<?php echo $row->SYSNAME; ?>"
                                                          value="1" <?php if (getUserSetting($row->SYSNAME, $this->settings->result()) == 1) {
                                                    echo 'checked';
                                                } ?>>Yes</label>
                    <label><input type="radio" class="no" name="<?php echo $row->SYSNAME; ?>"
                                                          value="0" <?php if (getUserSetting($row->SYSNAME, $this->settings->result()) == 0) {
                                                    echo 'checked';
                                                } ?>>No</label> -->
                    <input type="checkbox" name="MENU[<?php echo $row->ID_MENU; ?>][IS_WRITE]" value="1" <?php if(isset($this->ROLE_AKSES[$this->ID_ROLE][$row->ID_MENU]['IS_WRITE']) && $this->ROLE_AKSES[$this->ID_ROLE][$row->ID_MENU]['IS_WRITE']==1){echo 'checked';}?>>
                </td>
            </tr>
            <?php

            if (isset($parents[$id])){
                $n++;
                // echo '<ul class="'.$ulclass.'">';
                foreach ($parents[$id] as $child) 
                {
                    // echo str_repeat('--', $n-1);
                    $this->subtree($child, $n);
                }
                // echo '</ul>';
            } 
            // echo '</li>';
        }else{
            foreach ($parents[$id] as $child) 
            {
                // echo str_repeat('--', $n-1);
                $this->subtree($child, $n);
            }   
        }
    }
}

$mytreemenu = new mytreemenu($role->ID_ROLE);
$mytreemenu->setSettings($settings);
// echo '<ul class="nav main-menu">';
// $mytreemenu->subtree(0);
// echo '</ul>';

?>     

                <table class="table table-striped table-bordered table-hover table-heading">
                    <thead>
                    <tr>
                        <td>Menu</td>
                        <td>
                            Is Read
<!--                             <label><input type="radio" name="all" id="yesall">Yes</label> 
                            <label><input type="radio" name="all" id="noall">No</label> -->
                        </td>                        
                        <td>
                            Is Write
<!--                             <label><input type="radio" name="all" id="yesall2">Yes</label> 
                            <label><input type="radio" name="all" id="noall2">No</label> -->
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $mytreemenu->subtree(0); ?>
                    </tbody>
                </table>

                <?php
                /*

    <table
        class="table table-striped table-bordered table-hover table-heading no-border-bottom">
        <thead>
        <tr>
            <td width="30">No.</td>
            <!-- <td>Parent</td> -->
            <td>Menu</td>
            <td>SYSNAME</td>
            <td width="100"><label><input type="radio" name="all" id="yesall">Yes</label> <label><input
                        type="radio" name="all" id="noall">No</label></td>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        foreach ($menu as $item) {
            ?>
            <tr>
                <td><?php echo ++$i; ?>.</td>
                <!-- <td><?php echo $item->PARENT; ?></td> -->
                <td><?php echo $item->MENU; ?></td>
                <td><?php echo $item->SYSNAME; ?></td>
                <td>
                    <label><input type="radio" class="yes" name="<?php echo $item->SYSNAME; ?>"
                                  value="1" <?php if (getUserSetting($item->SYSNAME, $settings->result()) == 1) {
                            echo 'checked';
                        } ?>>Yes</label>
                    <label><input type="radio" class="no" name="<?php echo $item->SYSNAME; ?>"
                                  value="0" <?php if (getUserSetting($item->SYSNAME, $settings->result()) == 0) {
                            echo 'checked';
                        } ?>>No</label>                                        
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    */
    ?>
    <div class="pull-right">
        <input type="hidden" name="act" value="dosavesetting">
        <input type="hidden" name="ID_ROLE" value="<?php echo $role->ID_ROLE; ?>">
        <input type="submit" class="btn btn-sm btn-primary" value="Simpan"><i class="fa fa-close"></i> 
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#yesall").click(function () {
            alert('yes');
            $(".yes").prop("checked", true);
        });
        $("#noall").click(function () {
            alert('no');
            $(".no").prop("checked", true);
        });
        ng.formProcess($("#ajaxFormRoleSetting"), 'update', 'index.php/admin/role/akses');
    });

</script>
<style type="text/css">
    #modalbox > .table > thead > tr > th, 
    #modalbox > .table > tbody > tr > th, 
    #modalbox > .table > tfoot > tr > th, 
    #modalbox > .table > thead > tr > td, 
    #modalbox > .table > tbody > tr > td, 
    #modalbox > .table > tfoot > tr > td {
        padding: 1px !important;
    }
</style>
<?php
}
?>