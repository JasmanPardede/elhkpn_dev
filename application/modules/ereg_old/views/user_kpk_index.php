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
 * @package Views/user
*/

$INSATNSI = array();
foreach ($instansis as $instansi) {
    $INSATNSI[$instansi->INST_SATKERKD]['INST_NAMA'] = $instansi->INST_NAMA;
}
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Daftar User KPK
            <small><?php echo $this->minstansi->get_nama_instansi($this->session->userdata('INST_SATKERKD')); ?></small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  &nbsp;
                <?php
                if ( $this->makses->is_write )
                    echo '<button class="btn btn-sm btn-default" id="btn-add" href="index.php/ereg/user_kpk/adduser"><i class="fa fa-plus"></i> Tambah Data</button>';
                ?>
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="index.php/ereg/user_kpk/index">
                        <div class="input-group">
                            <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" value="<?php echo $cari;?>" id="cari"/>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            &nbsp;
                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                        </div>
                    </form>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
							<th>Username</th>
                            <th>Nama</th>
                            <!-- <th>Instansi</th> -->
							<th>Last Login</th>
                            <th>Email / Handphone</th>
                            <?php
                            if ( $this->makses->is_write ) {
                               ?>
                                <th>Password</th>
                                <?php
                            } else {
                                ?>
                                <!-- <th>Active</th> -->
                                <?php
                            }
                            ?>

                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = count($items) > 0 ? $i + 1 : 0;
                            $end   = 0;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><?php echo ++$i; ?>.</td>
                            <td><?php echo $item->USERNAME; ?></td>
                            <td><?php echo $item->NAMA; ?></td>
                            <td><?php echo !empty($item->LAST_LOGIN) ? date('d-m-Y H:i:s', $item->LAST_LOGIN) : '-'; ?></td>
                            <td>
                            <?php
                            if($item->EMAIL){
                                ?>
                                <a href="mailto:<?php echo $item->EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo $item->EMAIL; ?></a><br>
                                <?php
                            }
                            ?>
                            <?php
                            if($item->HANDPHONE){
                                ?>
                                <a href="tel:<?php echo $item->HANDPHONE; ?>"><i class="fa fa-phone"></i> <?php echo $item->HANDPHONE; ?></a>
                                <?php
                            }
                            ?>
                            </td>
                            <?php
                            if ( $this->makses->is_write ) {
                                ?>
                                <td style="text-align: center;"><a href="index.php/ereg/user_kpk/reset_password/<?php echo $item->ID_USER ?>"
                                                                   class="btn-reset">Reset Password</a> </td>
                                <?php
                            } else {
                                ?>
                                <!-- <td><?php echo $item->IS_ACTIVE == '1' ? 'active' : '<font color="red">inactive</font>'; ?></td> -->
                                <?php
                            }
                            ?>
                            <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/ereg/user_kpk/detailuser/<?php echo $item->ID_USER; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <?php
                                if ( $this->makses->is_write )
                                    echo '<button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/ereg/user_kpk/edituser/'.$item->ID_USER.'" title="Edit"><i
                                          class="fa fa-pencil"></i></button>';
                                ?>
                                <?php
                                if ( $this->makses->is_write ) {
                                    echo '<button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/user_kpk/deleteuser/'.$item->ID_USER.'" title="Delete"><i
                                          class="fa fa-trash" style="color:red;"></i></button>';

                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                                $end++;
                            }
                        ?>
                    </tbody>
                </table>

                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php
                        if($total_rows){
                    ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
                            of <?php echo $total_rows; ?> entries
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="col-sm-6 text-right">
                        <div class="dataTables_paginate paging_bootstrap">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

<script language="javascript">
$(document).ready(function () {
    $(".pagination").find("a").click(function () {
        var url = $(this).attr('href');
        window.location.hash = url;
        ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
        return false;
    });  

    $("#ajaxFormCari").submit(function (e) {
        var url = $(this).attr('action');
        ng.LoadAjaxContentPost(url, $(this));
        return false;            
    });        

    $('#btn-clear').click(function(event) {
        $('#ajaxFormCari').find('input:text').val('');
        $('#ajaxFormCari').trigger('submit');
    });

    $(".btn-detail").click(function () {
       url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Detail User', html, '');
        });            
        return false;
    })

    $("#btn-add").click(function () {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Tambah User', html, '');
            ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/user_pokja');
        });            
        return false;              
    });

    $('.btn-edit').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Edit User', html, '');
            ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/user_pokja');
        });            
        return false;
    });
    $('.btn-reset').click(function (e) {
        url = $(this).attr('href');
        document.location = url;
    });
    $('.btn-delete').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Delete User', html, '');
            ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/user_pokja');
        });            
        return false;
    });

});
function cek_user(username){
    // alert(username);
    var url = "index.php/ereg/user_kpk/cek_user/"+username;
    // alert(url);
        console.log(url);
        $.post(url,function(data){
            if (data == '1')
            {
                 $("#username_ada").show();
                 document.getElementById('USERNAME').value = "";
            }
            else
            {
                $("#username_ada").hide();
            };
        });
};

function cek_email(email){
var url    = "index.php/ereg/user_kpk/cek_email/"+encodeURIComponent(email);
    console.log(url);
    $.post(url,function(data){
        if (data == '1')
        {
             $("#email_ada").show();
             document.getElementById('EMAIL').value = "";
        }
        else
        {
            $("#email_ada").hide();
        };
    });
};
</script>