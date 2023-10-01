
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        PN/WL Aktif
    </h1>
    <?php echo @$breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">

                    <div class="box-tools">
                        <form id="ajaxFormCari" method="post" action="index.php/ereg/all_pn/index">
                            <div class="input-group">
                                <?php
                                if ($IS_KPK == 1) {
                                    ?>
                                    <input type='text' class="input-sm select" name='CARI[INST]' style="border:none;width:300px;" id='CARI_INST' value='<?= @$CARI['INST']; ?>' placeholder="-- Pilih Instansi --">
                                    <?php
                                }
                                ?>
                                &nbsp;
                                <input type="text" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search by Nama or NIK" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT"/>
                                <div class="input-group-btn">
                                    <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val('');
                                            $('#CARI_IS_CALON').val('99');
                                            $('#CARI_INST').val('');
                                            $('#ajaxFormCari').trigger('submit');">Clear</button>
                                </div>
                            </div>
                        </form>                    
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body"> <br/>
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <!-- <table id="dt_completeNEW" class="table table-striped"> -->
                        <thead>
                            <tr>
                                <th width="30">No.</th>
                                <th>NIK</th>
                                <th width="150px">Nama</th>
                                <th>Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($items as $item) {
                                ?>
                                <tr>
                                    <td align="center"><?php echo ++$i; ?>.</td>
                                    <td><?php echo $item->NIK; ?></td>
                                    <td><?php echo @$item->GELAR_DEPAN . ' ' . ucwords($item->NAMA) . ' ' . @$item->GELAR_BELAKANG; ?></td>
                                    <td>
                                        <?php
                                        $JABATAN = $item->DESKRIPSI_JABATAN . ' - ' . $item->UNIT_KERJA . ' - ' . $item->SUB_UNIT_KERJA;
                                        echo $JABATAN;
                                        ?>
                                    </td>                            


                                    <td width="10%" nowrap="" style="text-align: left;">
                                        <button type="button" class="btn btn-sm btn-primary btn-detail" href="index.php/ereg/all_pn/detailpn/<?php echo $item->ID_PN; ?>" title="Preview"><i class="fa fa-search-plus"></i></button>
                                        <button type="button" class="btn btn-sm btn-primary btn-detail2" href="index.php/ereg/all_pn/mts/<?php echo $item->ID; ?>/1" title="Perubahan Jabatan"><i class="fa fa-archive"></i></button>
                                        <?php if ($this->makses->is_write) { ?>
                                            <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/all_pn/deletepn/<?php echo $item->ID_PN; ?>" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button>
                                            <?php
                                        }
                                        ?>&nbsp;&nbsp;&nbsp;
                                            <?php
                                            if ($item->ID_USER) {
                                                ?>
                                            <button type="button" class="btn btn-sm btn-primary btn-detail" href="index.php/ereg/all_pn/reset_password/<?php echo substr(md5($item->ID_USER), 5, 8); ?>" title="Reset Password"><i class="fa fa-reddit-square"></i></button>
                                            <?php
                                        } else {
                                            echo '';
                                        }
                                        ?>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <?php
                            // echo (count($items) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->

            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<style type="text/css">
    .listjabatan{
        margin: 0px;
        padding:0px;
    }
    .listjabatan li.item{
        list-style: none;
        border: 1px solid #cfcfcf;
        padding-left: 10px;
        padding-bottom: 12px;
        margin-top: -1px;
    }
    ul.dropdown-menu {
        background-color: #99E1F4;
    }
    .listjabatan .dropdown{
        padding-right: 10px;
    }
</style>

<script language="javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $(".pagination").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('#CARI_IS_CALON').change(function() {
            $("#ajaxFormCari").trigger('submit');
        });

        $('#CARI_INST').change(function() {
            $("#ajaxFormCari").trigger('submit');
        });

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#INST').select2('val', '99');
            $('#ajaxFormCari').trigger('submit');
        });

        $(".btn-detail").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Detail Penyelenggara Negara', html, '', 'large');
            });
            return false;
        })

        $(".btn-detail2").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Perubahan Jabatan', html, '', 'large');
            });
            return false;
        })

        $("#btn-add").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah Penyelenggara Negara', html, '', 'large');
            });
            return false;
        });
        $("#btn-add-exc").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah Penyelenggara Negara', html, '', 'large');
            });
            return false;
        });
        $("#btn-add-webs").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah Penyelenggara Negara', html, '', 'large');
            });
            return false;
        });

        $('.btn-edit').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Edit Penyelenggara Negara', html, '', 'large');
            });
            return false;
        });

        $('.btn-keljab').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Riwayat Jabatan', html, '', 'large');
            });
            return false;
        });

        $('.btnNonaktifkan').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Non Aktifkan PN', html, '', 'standart');
            });
            return false;
        });

        $('.btn-delete').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Delete Penyelenggara Negara', html, '');
            });
            return false;
        });

        $('.btn-reset').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Reset Password', html, '', 'standart');
            });
            return false;
        });

        $('.btn-mutasi').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Mutasi Penyelenggara Negara', html, '', 'standart');
            });
            return false;
        });

        $('#btnPrintPDF').click(function() {
            var url = '<?php echo $linkCetak; ?>/pdf';
            ng.exportTo('pdf', url, 'Cetak <?php echo $titleCetak; ?>');
        });

        $('#btnPrintEXCEL').click(function() {
            var url = '<?php echo $linkCetak; ?>/excel';
            ng.exportTo('excel', url);
        });

        $('#btnPrintWORD').click(function() {
            var url = '<?php echo $linkCetak; ?>/word';
            ng.exportTo('word', url);
        });

        $('input[name="CARI[INST]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });

    });

    function cek_email(email) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email/" + encodeURIComponent(email);
        $.post(url, function(data) {
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
            }
            ;
        });
    }
    ;

    function cek_email_pn(email) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email_pn/" + encodeURIComponent(email);
        $.post(url, function(data) {
            loading.hide();
            $('#loader_area').show();
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
            }
            ;
        }).done(function() {
            $('#loader_area').hide();
        });
    }
    ;

    function cek_user(username) {
        // alert(username);
        var url = "index.php/ereg/all_pn/cek_user/" + username;
        // alert(url);
        console.log(url);
        $.post(url, function(data) {
            var msg = JSON.parse(data);
            if (msg.success == '1')
            {
                if (confirm('PN dengan NIK ' + username + ' telah tersedia. Apakah anda ingin merangkap jabatan?')) {
                    $('#NAMA').val(msg.result.NAMA);
                    $('#NAMA').attr('readonly', 'readonly');
                    $('#JNS_KEL [value="' + msg.result.JNS_KEL + '"]').attr('selected', 'selected');
                    $('#JNS_KEL').attr('readonly', 'readonly');
                    $('#TEMPAT_LAHIR').val(msg.result.TEMPAT_LAHIR);
                    $('#TEMPAT_LAHIR').attr('readonly', 'readonly');
                    $('#TGL_LAHIR').val(msg.result.TGL_LAHIR);
                    $('#TGL_LAHIR').attr('readonly', 'readonly');
                    $('#ID_AGAMA [value="' + msg.result.ID_AGAMA + '"]').attr('selected', 'selected');
                    $('#ID_AGAMA').attr('readonly', 'readonly');
                    $('#ID_STATUS_NIKAH [value="' + msg.result.ID_STATUS_NIKAH + '"]').attr('selected', 'selected');
                    $('#ID_STATUS_NIKAH').attr('readonly', 'readonly');
                    $('#ID_PENDIDIKAN [value="' + msg.result.ID_PENDIDIKAN + '"]').attr('selected', 'selected');
                    $('#ID_PENDIDIKAN').attr('readonly', 'readonly');
                    $('#NPWP').val(msg.result.NPWP);
                    $('#NPWP').attr('readonly', 'readonly');
                    $('#ALAMAT_TINGGAL').val(msg.result.ALAMAT_TINGGAL);
                    $('#ALAMAT_TINGGAL').attr('readonly', 'readonly');
                    $('#EMAIL').val(msg.result.EMAIL);
                    $('#EMAIL').attr('readonly', 'readonly');
                    $('#NO_HP').val(msg.result.NO_HP);
                    $('#NO_HP').attr('readonly', 'readonly');
                    $('input [name="BIDANG"]').attr('readonly', 'readonly');
                    $('#FOTO').attr('readonly', 'readonly');
                    $('#TINGKAT').val(msg.result.TINGKAT);
                    $('#TINGKAT').attr('readonly', 'readonly');
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
            }
            ;
        });
    }
    ;
    function cek_user_edit(username, current_username) {
        var div = $('#div-nik');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/ereg/all_pn/cek_user_edit/" + username + "/" + current_username;
        $.post(url, function(data) {
            loading.hide();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#username_ada").show();
                document.getElementById('NIK').value = current_username;
                document.getElementById('check_uname_edit').innerHTML = username;
            }
            else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            }
            ;
        });
    }
    ;

    //DataTables
    $(function() {
        $('#dt_completeNEW').dataTable({
            "bPaginate": false,
            "bLengthChange": true,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
//            "scrollY": '50vh',
//            "scrollCollapse": true,
        });
    });

    function yesnoCheck() {
        if (document.getElementById('satu').checked) {
            $("#btn-add").show();
            $("#btn-add-exc").hide();
            $("#btn-add-webs").hide();
        }
        else {
            $("#btn-add").hide();
            $("#btn-add-exc").show();
            $("#btn-add-webs").show();
        }

    }



</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


