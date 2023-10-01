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
$ROLE = array();
foreach ($roles as $role) {
    $ROLE[$role->ID_ROLE]['ROLE'] = $role->ROLE;
    $ROLE[$role->ID_ROLE]['COLOR'] = $role->COLOR;
}
$INSATNSI = array();
foreach ($instansis as $instansi) {
    $INSATNSI[$instansi->INST_SATKERKD]['INST_NAMA'] = $instansi->INST_NAMA;
}
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Daftar User
            <small>daftar user</small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
               <div class="box box-primary">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  <button type="button" class="btn btn-sm btn-primary" id="btn-add" href="index.php/admin/user/adduser"><i class="fa fa-plus"></i> Tambah Data</button>
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="index.php/admin/user/index">
                    <div class="input-group">
                    <select id="CARI_ROLE" name="CARI[ROLE]" class="select" style="border:none;width:200px;">
                        <option value="-99">-- Pilih Role --</option>
                        <?php
                        foreach ($roles as $item) {
                        ?>
                        <option value="<?php echo $item->ID_ROLE;?>" <?php echo $item->ID_ROLE==@$CARI['ROLE']?'selected':'';?>><?php echo $item->ROLE;?></option>
                        <?php
                        }
                        ?>
                    </select>
                    &nbsp;
                      <input type="text" name="CARI[TEXT]" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search By Username" value="<?php echo@$CARI['TEXT'];?>" id="CARI_TEXT"/>
                      <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT').val('');$('#CARI_ROLE').select2('val', '-99'); $('#ajaxFormCari').trigger('submit');">Clear</button>
                      </div>
                      
                    </div>
                    </form>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <!-- <table class="table table-bordered table-hover"> -->
                <table class="table">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
							<th>Username</th>
							<th>Nama</th>
                            <th>Role</th>
                            <th>Nomor SK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><small><?php echo ++$i; ?>.</small></td>
							<td><small><?php echo @$item->USERNAME; ?></small></td>
                            <td><small><?php echo @$item->NAMA; ?></small></td>
                            <td><small><?php echo @printRole($item->ID_ROLE, $ROLE); ?></small></td>
                            <td><small><?php echo @$item->NOMOR_SK; ?></small></td>
                            <td width="120" nowrap=""><small>
                                <button type="button" class="btn btn-sm btn-info btn-detail"
                                href="index.php/admin/user/detailuser/<?php echo  $item->ID_USER; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/admin/user/edituser/<?php echo $item->ID_USER;?>" title="Edit"><i
                                class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/admin/user/deleteuser/<?php echo $item->ID_USER;?>" title="Delete"><i
                                class="fa fa-trash" ></i></button>
                                <button type="button" class="btn btn-sm btn-primary btn-reset" href="index.php/admin/user/resetpassword/<?php echo $item->ID_USER;?>" title="Reset Password">
                                    <i class="fa fa-reddit-square" ></i></button>
                            </small></td>
                        </tr>
                        <?php
                                $end = $i;
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
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo  $start; ?> to <?php echo  @$end; ?>
                            of <?php echo  $total_rows; ?> entries
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

    $("#ajaxFormCari").find("#CARI_ROLE").change(function () {
        $("#ajaxFormCari").submit();
    });    

    $("#ajaxFormCari").find("#SEL_INST").change(function () {
        $("#ajaxFormCari").submit();
    });

    $('#btn-clear').click(function(event) {
        $('#ajaxFormCari').find('input:text').val('');
        $('#ajaxFormCari').trigger('submit');
    });

    $(".btn-detail").click(function () {
       url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Detail User', html, '', 'large');
        });            
        return false;
    })

    function afterFormProcess(){
        $("#ajaxFormCari").submit();
    }

    $("#btn-add").click(function () {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Tambah User', html, '');
            ng.formProcess($("#ajaxFormAdd"), 'add', '', afterFormProcess);
        });            
        return false;              
    });

    $('.btn-edit').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Edit User', html, '', 'large');
            ng.formProcess($("#ajaxFormEdit"), 'edit', '', afterFormProcess);
        });            
        return false;
    });

    $('.btn-delete').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Delete User', html, '');
            ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/admin/user');
        });            
        return false;
    });    

    $('.btn-reset').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Reset Password User', html, '');
            ng.formProcess($("#ajaxFormResetPassword"), 'reset', 'index.php/admin/user');
        });            
        return false;
    });

    // $('.select').select2();
    $("#CARI_ROLE").select2(); 
    $("#SEL_INST").select2(); 

    $('input[name="CARI[INST]"]').select2({
        minimumInputLength: 0,
        ajax: {
            url: "<?=base_url('index.php/share/reff/getLembaga')?>",
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return { results: data.item };
            },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?=base_url('index.php/share/reff/getLembaga')?>/"+id, {
                    dataType: "json"
                }).done(function(data) { callback(data[0]); });
            }
        },
        formatResult: function (state) {
            return state.name;
        },
        formatSelection:  function (state) {
            return state.name;
        }
    });

});

function cek_user(username){
    var div = $('#div-user');
    var loading = $('#loading', div);
    $('img', div).hide();

    loading.show();
    var url = "index.php/admin/user/cek_user/"+username;
    $.post(url,function(data){
        loading.hide();
        if (data == '1')
        {
            $('#fail', div).show();
             $("#username_ada").show();
             document.getElementById('USERNAME').value = "";
        }
        else
        {
            $('#success', div).show();
            $("#username_ada").hide();
        };
    });
};
function cek_user_ldap(username, current_username){
	if ( username != '' ) {
    var div = $('#div-user');
    var loading = $('#loading', div);
    $('img', div).hide();

    loading.show();
    if ( current_username == undefined )
        current_username = '';
    var url = "index.php/admin/user/cek_user_ldap/?username="+username+"&current_username="+current_username;

    $.post(url,function(data){
        loading.hide();
        if (data == '1')
        {
            $('#fail', div).show();
            $("#username_ada").show();
            $("#username_tidaktersedia").hide();
            document.getElementById('USERNAME').value = "";
        } else if ( data == '2' ) {
            $('#fail', div).show();
            $("#username_tidaktersedia").show();
            $("#username_ada").hide();
            document.getElementById('USERNAME').value = "";
        }
        else
        {
            $('#success', div).show();
            $("#username_ada").hide();
            $("#username_tidaktersedia").hide();
        };
    });
	}
};
function cek_email(email){
    var div = $('#div-email');
    var loading = $('#loading', div);
    $('img', div).hide();

    loading.show();
    var url    = "index.php/admin/user/cek_email/"+encodeURIComponent(email);
    $.post(url,function(data){
        loading.hide();
        if (data == '1')
        {
            $('#fail', div).show();
             $("#email_ada").show();
             document.getElementById('EMAIL').value = "";
        }
        else
        {
            $('#success', div).show();
            $("#email_ada").hide();
        };
    });
};
</script>