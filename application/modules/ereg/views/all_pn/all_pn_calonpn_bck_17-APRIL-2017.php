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
    // $out = '
    // <div class="dropdown pull-right">
    //     <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
    //     <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    // foreach ( $status_akhir as $status ) {
    //     $out .= '<li><a href="index.php/ereg/
    //     /mts/'.$idjb.'/'.$status->ID_STATUS_AKHIR_JABAT.'" class="btn-mutasi">'.$status->STATUS.'</a></li>';
    // }
    // $out .= '    </ul>
    // </div>';
    // return $out;
}

function dropdownHasilPemilihan($status_akhir, $idjb, $pnposisi) {
    // $out = '
    // <div class="dropdown pull-right">
    //     <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
    //     <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    // $out .= '<li><a href="index.php/ereg/all_pn/mts/'.$idjb.'/58" class="btn-mutasi">Terpilih</a></li>';
    // foreach ( $status_akhir as $status ) {
    //     if($status->IS_AKHIR == 0 && $status->IS_PINDAH == 0 && $status->IS_AKTIF == 0 && $status->IS_MENINGGAL == 0){
    //         $out .= '<li><a href="index.php/ereg/all_pn/mts/'.$idjb.'/'.$status->ID_STATUS_AKHIR_JABAT.'" class="btn-mutasi">'.(($pnposisi == 'calon') ? 'Tidak Terpilih' : $status->STATUS).'</a></li>';
    //     }
    // }
    // // $out .= '<li><a href="#">Non WL</a></li>';
    // $out .= '    </ul>
    // </div>';
    // return $out;
}

//function dropdownHasilPemilihan($status_akhir, $idjb){
//    $out = '
//    <div class="dropdown pull-right">
//        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Hasil Pemilihan <span class="caret"></span></button>
//        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
//    foreach ( $status_akhir as $status ) {
//        //$out .= '<li><a href="index.php/ereg/all_pn/mts/'.$idjb.'/'.$status->ID_STATUS_AKHIR_JABAT.'" class="btn-mutasi">'.$status->STATUS.'</a></li>';
//    }
//    $out .= '<li><a href="#">Terpilih</a></li>';
//    $out .= '<li><a href="#">Kalah Pemilihan</a></li>';
//
//    $out .= '    </ul>
//    </div>';
//    return $out;
//}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Calon PN/WL
    </h1>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!-- <h3 class="box-title">Bordered Table</h3> -->
                    <button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/all_pn/addpn/1/calonpn"><i class="fa fa-plus"></i> Tambah Data</button>
                      <!-- <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                      <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
                      <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button> -->
                    <div class="box-tools">
                        <form id="ajaxFormCari" method="post" action="index.php/ereg/all_pn/calonpn">
                            <div class="input-group">
<!--                                 <select id="CARI_STATUS_CALON" name="CARI[STATUS_CALON]" class="select2" style="width: 150px;">
                                    <option value="99"> -- All STATUS Calon PN -- </option>
                                    <option value="1" <?php echo @$CARI['STATUS_CALON'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="0" <?php echo @$CARI['STATUS_CALON'] == 2 ? 'selected' : ''; ?>>Non Aktif</option>
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
                                    <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#CARI_STATUS_CALON').val('99'); $('#CARI_INST').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
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
                                <th width="30">No.</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <!-- <th width="110">Riwayat Jabatan</th> -->
                                <?php
                                if ($this->makses->is_write) {
                                    ?>
                                    <!-- <th>Password</th> -->
                                <?php } ?>
                                <!-- <th>LHKPN</th> -->
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
                                    <td><small><?php echo ++$i; ?>.</small></td>
                                    <td><small><?php echo $item->NIK; ?></small></td>
                                    <td><small><?php echo $item->NAMA; ?></small></td>
                                    <td><small>
                                            <?php
                                            if ($item->NAMA_JABATAN) {
                                                $j = explode(':|||:', $item->NAMA_JABATAN);
                                                // echo '<div class="listjabatan">';
                                                foreach ($j as $ja) {
                                                    $jb = explode(':||:', $ja);
                                                    $ID = @$jb[0];
                                                    $ID_STATUS_AKHIR_JABAT = @$jb[1];
                                                    $STATUS = @$jb[2];
                                                    $ID_PN_JABATAN = @$jb[3] != 'NULL' ? @$jb[3] : null;
                                                    $LEMBAGA = @$jb[4];
                                                    $JABATAN = ucwords(strtolower(@$jb[5]));
                                                    $TMT = @$jb[6];
                                                    $SD = @$jb[7];
                                                    $IS_CALON = @$jb[8];
                                                    $INST_TUJUAN = @$jb[9];

                                                    $out = '';
                                                    $out .= $JABATAN;
                                                    // $out .= ' TMT : '.date('d-m-Y',strtotime($TMT));

                                                    if ($IS_CALON == 1 && (($ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN == null)) {// calon pn
                                                        $pnposisi = 'calon';
                                                    } else if (( $ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN == null) {
                                                        // jabatan masih aktif & tidak dimutasikan
                                                        $pnposisi = 'aktif';
                                                    } else {
                                                        if ($ID_PN_JABATAN != null) {// sedang mutasi
                                                            $pnposisi = 'mutasi';
                                                        } else {// jabatan sudah berakhir
                                                            $pnposisi = 'berakhir';
                                                        }
                                                    }

                                                    switch ($pnposisi) {
                                                        case 'calon' :
                                                            $out = ' <span class="label label-warning">Calon</span> ';
                                                            $out .= $JABATAN;
                                                            $out .= dropdownHasilPemilihan($status_akhir, $ID, $pnposisi);
                                                            break;
                                                        case 'aktif' :
                                                            $out .= dropdownMutasi($status_akhir, $ID);
                                                            break;
                                                        case 'mutasi' :
                                                            $out .= ' - <span class="label label-warning">sedang proses mutasi ke ' . $INST_TUJUAN . '</span> ';
                                                            break;
                                                        case 'berakhir' :
                                                            switch (strtolower($STATUS)) {
                                                                case 'mutasi':
                                                                    $labelstyle = 'label-primary';
                                                                    break;
                                                                case 'promosi':
                                                                    $labelstyle = 'label-success';
                                                                    break;
                                                                case 'non wl':
                                                                    $labelstyle = 'label-danger';
                                                                    break;
                                                                default:
                                                                    $labelstyle = 'label-danger';
                                                                    break;
                                                            }

                                                            if ($IS_CALON == 1) {
                                                                $out = ' <span class="label label-warning">Calon</span> ';
                                                                $out .= '<span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                                $out .= $JABATAN;
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                            } else {
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD)) . ' - <span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                            }
                                                            break;
                                                    }
                                                    // echo $out.'<div class="clearfix"></div>';
                                                }
                                                // echo '</di>';

                                                $JABATAN = $item->DESKRIPSI_JABATAN . ' - ' . $item->SUB_UNIT_KERJA . ' - ' . $item->UNIT_KERJA;
                                                echo $JABATAN;
                                            }
                                            ?>
                                        </small></td>                            
                                        <!-- <td class="text-center"> -->
                                            <!-- <a class="btn-keljab" href="index.php/ereg/all_pn/addjabatan/<?php echo $item->ID_PN; ?>/2/<?= $this->uri->segment(3) ?>" title="Riwayat Jabatan">[lihat]</a> -->
                                    <!-- <br> -->
                                    <!-- <a class="btnNonaktifkan" href="index.php/ereg/all_pn/nonaktifkan/<?php echo $item->ID_PN; ?>" title="Non Aktifkan">[Non Aktifkan]</a> -->
                                    <!-- </td> -->
                                    <?php
                                    if ($this->makses->is_write) {
                                        ?>
                                            <!-- <td style="text-align: center;"> -->
                                        <?php
                                        if ($item->ID_USER) {
                                            ?>
                                                        <!-- <button type="button" class="btn btn-sm btn-primary btn-reset" href="index.php/ereg/all_pn/reset_password/<?php echo substr(md5($item->ID_USER), 5, 8); ?>" title="reset password">Reset Password</button> -->
                                                        <!-- <a href="index.php/ereg/all_pn/reset_password/<?php //echo  substr(md5($item->ID_USER),5,8); ?>" class="btn-reset" id="reset_password">Reset Password</a>  -->
                                            <?php
                                        } else {
                                            echo 'Belum Punya User Akses';
                                        }
                                        ?>
                                        <!-- </td> -->
                                        <?php
                                    }
                                    ?> 
                                <!-- <td>7x LHKPN Verified, LHKPN TERAKHIR, CREATE Laporan</td> -->
                                    <td width="120" nowrap="" style="text-align: left;"><small>
                                            <button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/<?php echo $item->ID_PN; ?>/2/<?= $this->uri->segment(3) ?>" title="Preview"><i class="fa fa-search-plus"></i></button>
                                            <button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/mts_calon/<?php echo $ID; ?>/1" title="Perubahan Jabatan"><i class="fa fa-archive"></i></button>
                                            <?php if ($this->makses->is_write) { ?>
                                                    <!-- <button type="button" class="btn btn-sm btn-primary btn-edit" href="index.php/ereg/all_pn/editpn/<?php echo $item->ID_PN; ?>" title="Edit"><i class="fa fa-pencil"></i></button> -->
                                                <?php if ($havelhkpn[$item->ID_PN] !== true) { ?>
                                                        <!-- <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/all_pn/reset_password/<?php echo substr(md5($item->ID_USER), 5, 8); ?>" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button> -->
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/ereg/all_pn/delete_calon_pn/<?php echo $item->ID_PN; ?>" title="Delete"><i class="fa fa-trash" style="color:white;"></i></button>
            <?php
        }
    }
    ?>&nbsp;&nbsp;&nbsp;
                                            <?php
                                                if ($item->ID_USER) {
                                                    ?>
                                                <button type="button" class="btn btn-sm btn-primary btn-detail" href="index.php/ereg/all_pn/reset_password/<?php echo substr(md5($item->ID_USER), 5, 8); ?>" title="Reset Password"><i class="fa fa-reddit-square"></i></button>
                                                <!--<a href="index.php/ereg/all_pn/reset_password/<?php echo substr(md5($item->ID_USER), 5, 8); ?>" class="btn-reset" id="reset_password">Reset Password</a>--> 
        <?php
    } else {
        echo '';
    }
    ?>
                                            <?php if ($this->makses->is_write) { ?>
                                                <!-- <button type="button" class="btn btn-sm btn-primary btn-edit" href="index.php/ereg/all_pn/editpn/<?php echo $item->ID_PN; ?>" title="Edit"><i class="fa fa-pencil"></i></button> -->
                                                <?php if ($havelhkpn[$item->ID_PN] !== true) { ?>
                                                    <!-- <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/all_pn/reset_password/<?php echo substr(md5($item->ID_USER), 5, 8); ?>" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button> -->
                                                    <button type="button" class="btn btn-sm btn-success btn-aktif" href="index.php/ereg/all_pn/update_calon_pn/<?php echo $item->ID_PN; ?>" title="Aktifasi"><i class="fa fa-adjust" ></i></button>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </small></td>
                                </tr>
                                            <?php
                                            $end = $i;
                                        }
                                        ?>
                            <?php
                            // echo (count($items) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                            ?>
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

        $('#btn-clear').click(function (event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#INST').select2('val', '99');
            $('#ajaxFormCari').trigger('submit');
        });

        $(".btn-detail").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Detail Calon Penyelenggara Negara', html, '', 'large');
            });
            return false;
        })

        $(".btn-detail2").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Perubahan Jabatan', html, '', 'standart');
            });
            return false;
        })

        $("#btn-add").click(function () {
            //alert(html);
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tambah Calon Penyelenggara Negara', html, '', 'large');
            });
            return false;
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Edit Calon Penyelenggara Negara', html, '', 'standart');
            });
            return false;
        });

        $('.btn-keljab').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Riwayat Jabatan', html, '', 'large');
            });
            return false;
        });

        $('.btnNonaktifkan').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Non Aktifkan PN', html, '', 'standart');
            });
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Hapus Calon PN/WL', html, '');
            });
            return false;
        });
        $('.btn-aktif').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Active Penyelenggara Negara', html, '');
            });
            return false;
        });

        $('.btn-reset').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Reset Password Calon PN/WL', html, '', 'standart');
            });
            return false;
        });

        $('.btn-mutasi').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Mutasi Calon Penyelenggara Negara', html, '', 'standart');
            });
            return false;
        });

        $('#btnPrintPDF').click(function () {
            var url = '<?php echo $linkCetak; ?>/pdf';
            ng.exportTo('pdf', url, 'Cetak <?php echo $titleCetak; ?>');
        });

        $('#btnPrintEXCEL').click(function () {
            var url = '<?php echo $linkCetak; ?>/excel';
            ng.exportTo('excel', url);
        });

        $('#btnPrintWORD').click(function () {
            var url = '<?php echo $linkCetak; ?>/word';
            ng.exportTo('word', url);
        });

        $('input[name="CARI[INST]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });
        $('#CARI_INST').change(function () {
            $("#ajaxFormCari").trigger('submit');
        });
    });

    function cek_email(email, id) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_user_instansi/cek_email/" + encodeURIComponent(email) + '/' + id;
        $.post(url, function (data) {
            loading.hide();
            if (data == '0') {
                $('#fail', div).show();
                $('#email_ada').hide();
                $('#email_salah').show();
            } else if (data == '1') {
                $('#fail', div).show();
                $('#email_ada').show();
                $('#email_salah').hide();
            } else {
                $('#success', div).show();
                $('#email_ada').hide();
                $('#email_salah').hide();
            }
        });
    }
    ;

    function cek_email_pn(email, beforeEmail) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email_pn/" + encodeURIComponent(email);
        $.post(url, function (data) {
            loading.hide();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#email_ada").show();
                document.getElementById('EMAIL').value = '';
            } else
            {
                $('#success', div).show();
                $("#email_ada").hide();
            }
            ;
        });
    }
    ;

    function cek_user(username) {
        var url = "index.php/ereg/all_pn/cek_user/" + username;
        $.post(url, function (data) {
            var msg = JSON.parse(data);
            if (msg.success == '1')
            {
                confirm('PN dengan NIK ' + username + ' telah tersedia. Apakah anda ingin merangkap jabatan?', function () {
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
                });
            } else
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
        var url = "index.php/ereg/all_pn/cek_user_edit/" + username + "/" + current_username;
        $.post(url, function (data) {
            if (data == '1')
            {
                $("#username_ada").show();
                document.getElementById('NIK').value = current_username;
                document.getElementById('check_uname_edit').innerHTML = username;
            } else
            {
                $("#username_ada").hide();
            }
            ;
        });
    }
    ;


    $(function () {
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


