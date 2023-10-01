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
// $INSTANSI = array();
// foreach ($instansis as $instansi) {
//     $INSTANSI[$instansi->INST_SATKERKD]['NAMA'] = $instansi->INST_NAMA;
// }

function dropdownMutasi($status_akhir, $idjb) {
    $out = '
    <div class="dropdown pull-right">
        <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    foreach ($status_akhir as $status) {
        $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/' . $status->ID_STATUS_AKHIR_JABAT . '" class="btn-mutasi">' . $status->STATUS . '</a></li>';
    }
    $out .= '    </ul>
    </div>';
    return $out;
}

function dropdownHasilPemilihan($status_akhir, $idjb) {
    $out = '
    <div class="dropdown pull-right">
        <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/58" class="btn-mutasi">Penetapan PN/WL</a></li>';
    foreach ($status_akhir as $status) {
        if ($status->IS_AKHIR == 0 && $status->IS_PINDAH == 0 && $status->IS_AKTIF == 0 && $status->IS_MENINGGAL == 0) {
            $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/' . $status->ID_STATUS_AKHIR_JABAT . '" class="btn-mutasi">' . $status->STATUS . '</a></li>';
        }
    }
    // $out .= '<li><a href="#">Non WL</a></li>';

    $out .= '    </ul>
    </div>';
    return $out;
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        PN/WL Aktif
    </h1>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?php // echo $this->session->userdata('INST_SATKERKD'); ?>
                    <!--                    <div>
                                            <label class="radio-inline">
                                                <input type="radio" class="btn" checked onclick="yesnoCheck()" name="pilih" id="satu">Pendaftaran Individual
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="btn" onclick="yesnoCheck()" name="pilih" id="banyak">Pendaftaran Kolektif
                                            </label>
                                        </div>-->


                    <!--<a  href="index.php/ereg/all_pn/UpExcelload" class="ajax-link"><i class="fa fa fa-file-excel-o"></i> Upload Excel</a>-->
                    <!--                    <br/>
                                        <button class="btn btn-sm btn-primary"  id="btn-add" href="index.php/ereg/all_pn/addpn"><i class="fa fa-plus"></i> Tambah Data</button>
                                        <button class="btn btn-sm btn-primary" style="display: none" id="btn-add-exc" href="index.php/ereg/all_pn/DownUpExcels"><i class="fa fa fa-file-excel-o"></i> Excel</button>
                                        <button class="btn btn-sm btn-primary" style="display: none" id="btn-add-webs" href="index.php/ereg/all_pn/UpWebService"><i class="fa fa fa-link"></i> Web Services</button>-->

<!--                     <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
  <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
  <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button> -->
                    <div class="box-tools">

                        <?php /*
                          <form id="ajaxFormCari" method="post" action="index.php/ereg/all_pn/index">
                          <div class="input-group">
                          <!--                                 <select id="CARI_IS_CALON" name="CARI[IS_CALON]" class="select2" style="width: 150px;">
                          <option value="99"> -- All STATUS PN -- </option>
                          <option value="0" <?php echo @$CARI['IS_CALON'] == 0 ? 'selected' : ''; ?>>PN/WL</option>
                          <option value="1" <?php echo @$CARI['IS_CALON'] == 1 ? 'selected' : ''; ?>>Calon PN</option>
                          </select> -->
                          <?php
                          if ($IS_KPK == 1) {
                          ?>
                          <input type='text' class="input-sm select" name='CARI[INST]' style="border:none;width:300px;" id='CARI_INST' value='<?= @$CARI['INST']; ?>' placeholder="-- Pilih Instansi --">
                          <?php
                          }
                          ?>
                          &nbsp;
                          <input type="text" class="form-control input-sm pull-right" style="width: 400px;" placeholder="Search by Nama or NIK or Jabatan or Sub Unit Kerja or Unit Kerja" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT"/>
                          <div class="input-group-btn">
                          <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                          <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val('');
                          $('#CARI_IS_CALON').val('99');
                          $('#CARI_INST').val('');
                          $('#ajaxFormCari').trigger('submit');">Clear</button>
                          </div>
                          </div>
                          </form>
                         */ ?>						
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body"> <br/>
                    <table id="dt_completeNEW" class="table">
                    <!-- <table id="dt_completeNEW" class="table table-striped"> -->
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th width="150px">Nama</th>
                                <th>Jabatan</th>
                        <!--        <th width="80">Riwayat Jabatan</th>
                                <?php
                                if ($this->makses->is_write) {
                                    ?>
                                                                <th>Password</th>
                                <?php } ?>
                         <th>LHKPN</th> -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php
                    if ($total_rows) {
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
                OpenModalBox('Detail PN/WL', html, '', 'large');
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
                OpenModalBox('Tambah PN/WL', html, '', 'large');
            });
            return false;
        });
        $("#btn-add-exc").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL', html, '', 'large');
            });
            return false;
        });
        $("#btn-add-webs").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL', html, '', 'large');
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
                OpenModalBox('Non Aktifkan PN/WL', html, '', 'standart');
            });
            return false;
        });

        $('.btn-reset').click(function (e) {
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

    function cek_nomor_hp(nohp, id) {
        alert(nohp);
        var div = $('#div-hp');
        var loading = $('#loading_hp', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_pn/cek_nomor_hp/" + nohp + '/' + id;
        $.post(url, function(data) {
            loading.hide();
            if (data == '1') {
                $('#hp_ada').hide();
            } else if (data == '2') {
                $('#hp_ada').hide();
            } else if (data == '3') {
                $('#hp_ada').show();
            }
            // else{
            //     $('#success_hp', div).show();
            //     $('#hp_ada').hide();
            // }
        });


    }
    ;


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


    var tblDaftarIndividual = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividual'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_page_index'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_string = [];

                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUK_NAMA') && !isEmpty(source.SUK_NAMA)) {
                            arr_showed_string.push(source.SUK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {

                        var stl = false;
                        if (source.STS_J == 10 || source.STS_J == 11 || source.STS_J == 15) {
                            stl = true;
                        }

                        var btnHapus = '<button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/ereg/all_pn/deletepn/' + source.ID_PN + '/' + source.ID + '" title="Non Aktifkan PN/WL" onclick="onButton.delete.click(this);"><i class="fa fa-user-times"style="color:white;"></i></button>';


                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';
                        var btnDetail2 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/mts/' + source.ID + '/1" title="Perubahan Jabatan" onclick="onButton.detail2.click(this);"><i class="fa fa-archive"></i></button>';

                        var btnDetail3 = '<button type="button" class="btn btn-sm btn-primary btn-detail" href="index.php/ereg/all_pn/reset_password/' + source.id_user_md5 + '" title="Reset Password" onclick="onButton.detail3.click(this);"><i class="fa fa-reddit-square"style="color:white;"></i></button>';

                        var disable = '', btnApprove = '', btnAksi = '';

                        if (!stl) {
                            btnAksi += btnDetail + ' ' + btnDetail2;
<?php if ($this->makses->is_write): ?>
                                btnAksi += ' ' + btnHapus;
<?php endif; ?>
                            if (source.ID_USER) {
                                btnAksi += ' ' + btnDetail3;
                            }
                        }

                        return (btnAksi).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                var stl = false;
                if (aData.STS_J == 10 || aData.STS_J == 11 || aData.STS_J == 15) {
                    stl = true;
                }

                if (stl) {
                    $('td:eq(1)', nRow).parent().css('color', 'red');
                }
                return nRow;
            }
        }
    };

    var onButton = {
        go: function (obj, size) {

            if (!isDefined(size)) {
                size = '';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Penonaktifan PN/WL', html, '', size);
            });
            return false;
        },
        detail: {
            click: function (self) {
                onButton.go(self);
            }},
        detail2: {
            click: function (self) {
                onButton.go(self);
            }},
        detail3: {
            click: function (self) {
                onButton.go(self);
            }
        },
        edit: {
            click: function (self) {
                onButton.go(self, 'large');
            }
        },
        delete: {
            click: function (self) {
                onButton.go(self);
            }
        }
    };

    $(function () {

        $('.btn-edit').click(function (e) {
            onButton.edit.click(this);
        });

        $('.btn-delete').click(function (e) {
            onButton.delete.click(this);
        });

        var gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);
    });

</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


