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
 * @package Views/pn
*/

$INSTANSI = array();
foreach ($instansis as $instansi) {
    $INSTANSI[$instansi->INST_SATKERKD]['NAMA'] = $instansi->INST_NAMA;
}

?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Penyelenggara Negara
            <small><?php echo $INSTANSI[$this->session->userdata('INST_SATKERKD')]['NAMA']; ?></small>
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
                  <button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/pn/addpn"><i class="fa fa-plus"></i> Tambah Data</button>
                    <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                    <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
                    <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>                  
                  <div class="box-tools">
                    <form id="ajaxFormCari" method="post" action="index.php/ereg/pn/index">
					    <div class="input-group">
                            <select id="CARI_STATUS_PN" name="CARI[STATUS_PN]" class="select2" style="width: 150px;">
                                <option value="99"> -- All STATUS PN -- </option>
                                <option value="1" <?php echo @$CARI['STATUS_PN']==1?'selected':'';?>>PN/WL</option>
                                <option value="2" <?php echo @$CARI['STATUS_PN']==2?'selected':'';?>>Calon PN</option>
                            </select>
                            &nbsp;
					        <input type="text" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search PN" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
					        <div class="input-group-btn">
					          <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
					          <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#CARI_STATUS_PN').val('99'); $('#ajaxFormCari').trigger('submit');">Clear</button>
					        </div>
					    </div>
                    </form>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table id="dt_completeNEW" class="table table-striped">
                    <thead>
                        <tr>
                            <th align="center" width="30">No.</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            $end = 0;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td align="center"><small><?php echo ++$i; ?>.</small></td>
				            <td><small><?php echo $item->NIK; ?></small></td>
				            <td><small><?php echo $item->NAMA; ?></small></td>
                            <td><small>
                                <?php
                                if($item->NAMA_JABATAN){
                                    $j = explode(',',$item->NAMA_JABATAN);
                                    echo '<ul>';
                                    foreach ($j as $ja) {
                                        $jb = explode(':58:', $ja);
                                        $idjb = $jb[0];
                                        $statakhirjb = $jb[1];
                                        $statakhirjbtext = $jb[2];
                                        $statmutasijb = $jb[3];
                                        if($statmutasijb != null && $statmutasijb != '' && $statmutasijb != '0'){
                                            $linkMutasi = ' - <span class="badge">sedang proses mutasi</span>';
                                        }else{
                                            $linkMutasi = $jb[4] == $this->session->userdata('INST_SATKERKD') && ($statakhirjb == '' || $statakhirjb == '0')?' <a href="index.php/ereg/pn/mts/'.$idjb.'" class="btn-mutasi">[mutasi <i class="fa fa-share"></i>]</a> ':' - <span class="badge">'.$statakhirjbtext.'</span>';
                                            // $linkMutasi = $statakhirjb == '0' || $statakhirjb == '' || $statakhirjb == null?' <a href="index.php/ereg/all_pn/mts/'.$idjb.'" class="btn-mutasi">[mutasi]</a> ':' - '.$statakhirjbtext;
                                        }
                                        echo '<li>'.$jb[5].$linkMutasi.'</li>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </small></td>
                            <td width="120" nowrap="" style="text-align: center;"><small>
                                <button type="button" class="btn btn-sm btn-primary btn-detail" href="index.php/ereg/pn/detailpn/<?php echo $item->ID_PN; ?>" title="Preview"><i class="fa fa-search-plus"></i></button><?php if ( $this->makses->is_write ) { ?>
                                <button type="button" class="btn btn-sm btn-primary btn-edit" href="index.php/ereg/pn/editpn/<?php echo $item->ID_PN;?>" title="Edit"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/pn/deletepn/<?php echo $item->ID_PN;?>" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button>
                                <?php } ?>
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
        $('.select2').select2();
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
                OpenModalBox('Detail Penyelenggara Negara', html, '', 'large');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tambah Penyelenggara Negara', html, '');
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Edit Penyelenggara Negara', html, '', 'large');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Delete Penyelenggara Negara', html, '');
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

        $('.btn-mutasi').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Mutasi Penyelenggara Negara', html, '');
            });
            return false;
        });

        $('#btnPrintPDF').click(function () {
            var url = '<?php echo $linkCetak;?>/pdf';
            ng.exportTo('pdf', url, 'Cetak <?php echo $titleCetak;?>');
        });

        $('#btnPrintEXCEL').click(function () {
            var url = '<?php echo $linkCetak;?>/excel';
            ng.exportTo('excel', url);
        });

        $('#btnPrintWORD').click(function () {
            var url = '<?php echo $linkCetak;?>/word';
            ng.exportTo('word', url);
        }); 

    });

function cek_email(email){
var url    = "index.php/admin/user/cek_email/"+encodeURIComponent(email);
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

    function cek_user(username){
        // alert(username);
        var url = "index.php/ereg/all_pn/cek_user/"+username;
        // alert(url);
        console.log(url);
        $.post(url,function(data){
            var msg = JSON.parse(data);
            if (msg.success == '1')
            {
                if ( confirm('PN dengan NIK ' + username + ' telah tersedia. Apakah anda ingin merangkap jabatan?') ) {
                    $('#NAMA').val(msg.result.NAMA);
                    $('#NAMA').attr('readonly','readonly');
                    $('#JNS_KEL [value="'+msg.result.JNS_KEL+'"]').attr('selected', 'selected');
                    $('#JNS_KEL').attr('readonly','readonly');
                    $('#TEMPAT_LAHIR').val(msg.result.TEMPAT_LAHIR);
                    $('#TEMPAT_LAHIR').attr('readonly','readonly');
                    $('#TGL_LAHIR').val(msg.result.TGL_LAHIR);
                    $('#TGL_LAHIR').attr('readonly','readonly');
                    $('#ID_AGAMA [value="'+msg.result.ID_AGAMA+'"]').attr('selected', 'selected');
                    $('#ID_AGAMA').attr('readonly','readonly');
                    $('#ID_STATUS_NIKAH [value="'+msg.result.ID_STATUS_NIKAH+'"]').attr('selected', 'selected');
                    $('#ID_STATUS_NIKAH').attr('readonly','readonly');
                    $('#ID_PENDIDIKAN [value="'+msg.result.ID_PENDIDIKAN+'"]').attr('selected', 'selected');
                    $('#ID_PENDIDIKAN').attr('readonly','readonly');
                    $('#NPWP').val(msg.result.NPWP);
                    $('#NPWP').attr('readonly','readonly');
                    $('#ALAMAT_TINGGAL').val(msg.result.ALAMAT_TINGGAL);
                    $('#ALAMAT_TINGGAL').attr('readonly','readonly');
                    $('#EMAIL').val(msg.result.EMAIL);
                    $('#EMAIL').attr('readonly','readonly');
                    $('#NO_HP').val(msg.result.NO_HP);
                    $('#NO_HP').attr('readonly','readonly');
                    $('input [name="BIDANG"]').attr('readonly','readonly');
                    $('#FOTO').attr('readonly','readonly');
                    $('#TINGKAT').val(msg.result.TINGKAT);
                    $('#TINGKAT').attr('readonly','readonly');
                    $('#act').val('dorangkapjabatan');
                    $('#ID_PN').val(msg.result.ID_PN);
                }
            }
            else
            {
                $('#ajaxFormAdd input:text').val('');
                $('#ajaxFormAdd [type="email"]').val('');
                $('#ajaxFormAdd input:radio').removeAttr('checked');
                $('#ajaxFormAdd select').prop('selectedIndex', 0);
                $('#ajaxFormAdd select').removeAttr('readonly');
                $('#ajaxFormAdd input').removeAttr('readonly');
                $('#NIK').val(username);
                $("#username_ada").hide();
                $('#act').val('doinsert');
                $('#ID_PN').val('');
            };
        });
    };
    function cek_user_edit(username, current_username){
        // alert(username);
        var url = "index.php/ereg/pn/cek_user_edit/"+username+"/"+current_username;
        // alert(url);
        console.log(url);
        $.post(url,function(data){
            if (data == '1')
            {
                $("#username_ada").show();
                document.getElementById('NIK').value = current_username;
                document.getElementById('check_uname_edit').innerHTML = username;
            }
            else
            {
                $("#username_ada").hide();
            };
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

<style>
    td .btn {
        margin: 0px;
    }
</style>


