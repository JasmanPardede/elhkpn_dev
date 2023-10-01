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
            Daftar User Instansi (Unit Kerja)
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
                  &nbsp;
                <?php
                if ( $this->makses->is_write )
                    echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/all_user_instansi/adduser"><i class="fa fa-plus"></i> Tambah Data</button>';
                ?>
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="index.php/ereg/all_user_instansi/index">
                        <div class="input-group">
                            <?php if($inst == false){ ?>
                                <input type='text' class="input-sm select" name='INST' style="border:none;width:300px;" id='CARI_INST' value='<?=@$inst_satkerkd;?>' placeholder="-- Pilih Instansi --">
                            <?php } ?>
                            &nbsp;
                            <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search by Username or Nama or Email" value="<?php echo $cari;?>" id="cari"/>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-sm btn-default" id="btn-clear">Clear</button>
                            </div>
                            &nbsp;
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
                            <th>Instansi (Unit Kerja)</th>
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
                         <?php
                            if($item->IS_ACTIVE ==1||$item->IS_ACTIVE==0){
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
                            <td><small>
                                <?php
                                     echo $this->minstansi->get_nama_instansi($item->INST_SATKERKD); 
                                     if($item->UK_ID){
                                        echo " ( <b>".ucfirst(strtolower($item->UK_NAMA))."</b> )";
                                     }
                                ?>
                            </small></td>
                            <td width="120" nowrap="" style="pull-left"><small>
                                <!-- <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/ereg/all_user_instansi/detailuser/<?php echo $item->ID_USER; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button> -->
                                <?php
                                if ( $this->makses->is_write )
                                    echo '<button type="button" class="btn btn-success btn-sm btn-edit" href="index.php/ereg/all_user_instansi/edituser/'.$item->ID_USER.'" title="Edit"><i
                                          class="fa fa-pencil"></i></button>';
                                ?>
                                <?php
                                if ( $this->makses->is_write ) {
                                    echo '<button type="button" class="btn btn-danger btn-sm btn-delete" href="index.php/ereg/all_user_instansi/deleteuser/'.$item->ID_USER.'" title="Delete"><i
                                          class="fa fa-trash" style="color:white;"></i></button>';
                                }
                                ?>
                                <?php
                                if ( $this->makses->is_write ) {
                                    echo '<button type="button" class="btn btn-sm btn-primary btn-reset" href="index.php/ereg/all_user_instansi/reset_password/'.$item->ID_USER.'" title="reset password"><i class="fa fa-reddit-square"></i></button>';
                                }
                                ?>
                            </small></td>
                        </tr>
                        <?php
              }
         ?>            
                        <?php
                                $end++;
                            }
                        ?>
                        <?php
//                        echo (count($items) == 0 ? '<tr><td colspan="8" class="items-null">Data tidak ditemukan!</td></tr>' : '');
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

    $('#CARI_INST').select2({
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

//    $("#INST").select2();
    $(".pagination").find("a").click(function () {
        var url = $(this).attr('href');
        url = url.replace('<?=base_url();?>', '');
        window.location.hash = url;
        ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
        return false;
    });  

    $("#ajaxFormCari").submit(function (e) {
        var url = $(this).attr('action');
        ng.LoadAjaxContentPost(url, $(this));
        return false;            
    });        
    $("#ajaxFormCari").find("#CARI_INST").change(function () {
        $("#ajaxFormCari").submit();
    });
    $('#btn-clear').click(function(event) {
        $('#ajaxFormCari').find('input:text').val('');
        $('#INST').select2('val', '99');
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
            OpenModalBox('Reset Password User Instansi/Unit Kerja', html, '');
        });
        return false;
    });
    $('.btn-delete').click(function (e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Hapus User instansi/Unit Kerja', html, '');
        });            
        return false;
    });

});
function cek_user(username,id){
    var div = $('#div-username');
    var loading = $('#loading', div);
    $('img', div).hide();
    username = username.replace(/^\s+|\s+$/g,'');
    if(username == ''){
        $('#fail', div).show();
        return;
    }
    loading.show();
    var url = "index.php/ereg/all_user_instansi/cek_user/"+username+"/"+id;
        $.post(url,function(data){
            loading.hide();
            if (data == '1')
            {
                $('#fail', div).show();
                 $("#username_ada").show();
                 document.getElementById('USERNAME').value = "";
                document.getElementById('check_uname_add').innerHTML = username;
            }
            else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            };
        });
};
function cek_user_edit(username, current_username){
    // alert(username);
    var url = "index.php/ereg/all_user_instansi/cek_user_edit/"+username+"/"+current_username;
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