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
            Daftar Admin Unit Kerja
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
                    echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/user_instansi/adduser"><i class="fa fa-plus"></i> Tambah Data</button>';
                ?>
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="index.php/ereg/user_instansi/index">
                        <div class="form-group">
                            <!-- <div class="input-group" style="float: left">
                                <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" value="<?php echo $cari;?>" id="cari"/>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                                <button style="float: right; margin-left: 5px;" type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                            </div> -->

                            <div class="input-group">
                            <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" value="<?php echo $cari;?>" id="cari"/>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                            </div>
                            </div>

                        </div>
                    </form>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table id="dt_completeNEW" class="table">
                    <thead>
                        <tr>
                            <th align="center" width="30">No.</th>
							<th>Username</th>
                            <th>Nama</th>
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
                            <td align="center"><small><?php echo ++$i; ?>.</small></td>
                            <td><small><?php echo $item->USERNAME; ?></small></td>
                            <td><small><?php echo $item->NAMA; ?></small></td>
                            <td><small><?php echo !empty($item->LAST_LOGIN) ? date('d-m-Y H:i:s', $item->LAST_LOGIN) : '-'; ?></small></td>
                            <td><small>
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
                            </small></td>
                            <?php
                            if ( $this->makses->is_write ) {
                                ?>
                                <td style="text-align: center;"><a href="index.php/ereg/user_instansi/reset_password/<?php echo $item->ID_USER ?>"
                                                                   class="btn-reset" id="reset_password">Reset Password</a> </td>
                                <?php
                            } else {
                                ?>
                                <!-- <td><?php echo $item->IS_ACTIVE == '1' ? 'active' : '<font color="red">inactive</font>'; ?></td> -->
                                <?php
                            }
                            ?>
                            <td width="120" nowrap="" style="pull-left"><small>
                                <button type="button" class="btn btn-sm btn-primary btn-detail"
                                href="index.php/ereg/user_instansi/detailuser/<?php echo $item->ID_USER; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <?php
                                if ( $this->makses->is_write )
                                    echo '<button type="button" class="btn btn-sm btn-primary btn-edit" href="index.php/ereg/user_instansi/edituser/'.$item->ID_USER.'" title="Edit"><i
                                          class="fa fa-pencil"></i></button>';
                                ?>
                                <?php
                                if ( $this->makses->is_write ) {
                                    echo '<button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/user_instansi/deleteuser/'.$item->ID_USER.'" title="Delete"><i
                                          class="fa fa-trash" style="color:red;"></i></button>';

                                }
                                ?>
                            </small></td>
                        </tr>
                        <?php
                                $end++;
                            }
                        ?>
                        <?php
                        // echo (count($items) == 0 ? '<tr><td colspan="8" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                        // echo (count($items) == 0 ? '<tr><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr>' : '');
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
        $('#loader_area').show();
        $.post(url, function (html) {
            OpenModalBox('Detail User', html, '');
        });            
        return false;
    })

    $("#btn-add").click(function () {
        url = $(this).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            OpenModalBox('Tambah User', html, '');
        });            
        return false;              
    });

    $('.btn-edit').click(function (e) {
        url = $(this).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            OpenModalBox('Edit User', html, '');
        });            
        return false;
    });
    $('.btn-reset').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Reset Password', html, '');
        });
        return false;
    });
    $('.btn-delete').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Delete User', html, '');
        });            
        return false;
    });
});
function cek_user(username){
    // alert(username);
    var url = "index.php/ereg/user_instansi/cek_user/"+username;
    // alert(url);
        console.log(url);
        $.post(url,function(data){
            if (data == '1')
            {
                 $("#username_ada").show();
                 document.getElementById('USERNAME').value = "";
                document.getElementById('check_uname_add').innerHTML = username;
            }
            else
            {
                $("#username_ada").hide();
            };
        });
};
function cek_user_edit(username, current_username){
    // alert(username);
    var url = "index.php/ereg/user_instansi/cek_user_edit/"+username+"/"+current_username;
    // alert(url);
    console.log(url);
    $.post(url,function(data){
        if (data == '1')
        {
            $("#username_ada").show();
            document.getElementById('USERNAME').value = current_username;
            document.getElementById('check_uname_edit').innerHTML = username;
        }
        else
        {
            $("#username_ada").hide();
        };
    });
};

function cek_email(email, id){
    var div = $('#div-email');
    var loading = $('#loading', div);
    $('img', div).hide();
    loading.show();
    var url    = "index.php/ereg/all_user_instansi/cek_email/"+encodeURIComponent(email)+'/'+id;
    $.post(url,function(data){
        loading.hide();
        if(data=='0'){
            $('#fail', div).show();
            $('#email_ada').hide();
            $('#email_salah').show();
        }else if(data=='1'){
            $('#fail', div).show();
            $('#email_ada').show();
            $('#email_salah').hide();
        }else{
            $('#success', div).show();
            $('#email_ada').hide();
            $('#email_salah').hide();
        }
    });
};

//DataTables
    $(function() {
        $('#dt_completeNEW').dataTable({
            "bPaginate": false,
            "bLengthChange": true,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
            "scrollY": '50vh',
            "scrollCollapse": true,
        });
    });
</script>