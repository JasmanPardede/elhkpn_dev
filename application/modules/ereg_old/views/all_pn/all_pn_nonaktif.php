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
function aktifkan($idjb, $status) {
    $out = '
        <div class="dropdown pull-right" style="margin-top: 10px;">
        <a class="btn btn-sm btn-success btnAktifkan" href="index.php/ereg/all_pn/aktifkan/' . $idjb . '/' . $status . '">
            Aktifkan
        </a>
    ';

    $out .= '</div>';
    return $out;
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Non Aktif
        <small>daftar PN/WL Non Aktif</small>
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
  <!--                     <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                      <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
                      <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button> -->
                    &nbsp;
                    <div class="box-tools">
                        <form id="ajaxFormCari" method="post" action="index.php/ereg/all_pn/nonaktif">
                            <div class="input-group">
                                <?php
                                if ($IS_KPK == 1) {
                                    ?>
                                    <input type='text' class="input-sm select" name='CARI[INST]' style="border:none;width:300px;" id='CARI_INST' value='<?= @$CARI['INST']; ?>' placeholder="-- Pilih Instansi --">
                                    <?php
                                }
                                ?>
                                &nbsp;
                                <input type="text" class="form-control input-sm pull-right" style="width: 400px;" placeholder="Search by Nama or NIK or Jabatan or Sub Unit Kerja or Unit Kerja" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT"/>
                                <!--<input type='text' class="input-sm select" name='CARI[STAT]' style="border:none;width:300px;" id='CARI_STAT' value='<?= @$CARI['STAT']; ?>' placeholder="-- Pilih Status --">-->

                                <div class="input-group-btn">
                                    <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val('');
                                  $('#CARI_INST').val('');
                                  $('#CARI_STAT').val('');
                                  $('#ajaxFormCari').trigger('submit');">Clear</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="dt_completeNEW" class="table">
                    <!-- <table id="dt_completeNEW" class="table table-striped"> -->
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
                                <th width="50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            $end = 0;
                            foreach ($items as $item) {
                                $tJabatan = '';
                                ?>
                                <tr>
                                    <td align="center"><small><?php echo ++$i; ?>.</small></td>
                                    <td><small><?php echo $item->NIK; ?></small></td>
                                    <?php
                                    if ($item->NAMA_JABATAN) {
                                        $j = explode(':|||:', $item->NAMA_JABATAN);
                                        $tJabatan = '<div class="listjabatan">';
                                        $meninggal = FALSE;
                                        $spanMeninggal = '';
                                        foreach ($j as $ja) {
                                            $jb = explode(':||:', $ja);
                                            $ID = @$jb[0];
                                            $ID_STATUS_AKHIR_JABAT = @$jb[1];
                                            $STATUS = @$jb[2];
                                            $ID_PN_JABATAN = @$jb[3] != 'NULL' ? @$jb[3] : null;
                                            $LEMBAGA = @$jb[4];
                                            $JABATAN = ucwords(strtoupper(@$jb[5]));
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

                                            if ($ID_STATUS_AKHIR_JABAT == '1') {
                                                $mutasi = TRUE;
                                                $promosi = FALSE;
                                                $meninggal = FALSE;
                                                $pensiun = FALSE;
                                                $nonwl = FALSE;
                                            } else if ($ID_STATUS_AKHIR_JABAT == '2') {
                                                $mutasi = FALSE;
                                                $promosi = TRUE;
                                                $meninggal = FALSE;
                                                $pensiun = FALSE;
                                                $nonwl = FALSE;
                                            } else if ($ID_STATUS_AKHIR_JABAT == '3') {
                                                $mutasi = FALSE;
                                                $promosi = FALSE;
                                                $meninggal = TRUE;
                                                $pensiun = FALSE;
                                                $nonwl = FALSE;
                                            } else if ($ID_STATUS_AKHIR_JABAT == '4') {
                                                $mutasi = FALSE;
                                                $promosi = FALSE;
                                                $meninggal = FALSE;
                                                $pensiun = TRUE;
                                                $nonwl = FALSE;
                                            } else {
                                                $mutasi = FALSE;
                                                $promosi = FALSE;
                                                $meninggal = FALSE;
                                                $pensiun = FALSE;
                                                $nonwl = TRUE;
                                            }

                                            switch ($pnposisi) {
                                                case 'calon' :
                                                    $out = ' <span class="label label-warning">Calon</span> ';
                                                    $out .= $JABATAN;
                                                    break;
                                                case 'aktif' :
                                                    break;
                                                case 'mutasi' :
                                                    $out .= ' - <span class="label label-warning">sedang proses mutasi ke ' . $INST_TUJUAN . '</span>';
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
                                                        if ($IS_KPK == 1) {
                                                            if ($nonwl) {
                                                                $out = ' <span class="label label-warning">Calon</span> ';
                                                                $out .= '<span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                                $out .= $JABATAN;
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                                $out .= aktifkan($ID, $ID_STATUS_AKHIR_JABAT);
                                                            } else {
                                                                $out = ' <span class="label label-warning">Calon</span> ';
                                                                $out .= '<span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                                $out .= $JABATAN;
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                            }
                                                        }
                                                    } else {
                                                        if ($IS_KPK == 1) {
                                                            if ($mutasi) {
                                                                '';
                                                            }

                                                            if ($promosi) {
                                                                '';
                                                            }

                                                            if ($meninggal) {
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                                $out .= aktifkan($ID, $ID_STATUS_AKHIR_JABAT);
                                                                $spanMeninggal = ' - <span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                            }

                                                            if ($pensiun) {
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                                $out .= aktifkan($ID, $ID_STATUS_AKHIR_JABAT);
                                                            }

                                                            if ($nonwl) {
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                                $out .= aktifkan($ID, $ID_STATUS_AKHIR_JABAT);
                                                            }

                                                            // if($nonwl){
                                                            //     $out .= ' S/D : '.date('d-m-Y',strtotime($SD));
                                                            //     $out .= aktifkan($ID, $ID_STATUS_AKHIR_JABAT);
                                                            // }

                                                            $out .= ' S/D : ' . date('d-m-Y', strtotime($SD)) . ' - <span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                        } else {
                                                            if ($meninggal) {
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                                                $spanMeninggal = ' - <span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                            } else {
                                                                $out .= ' S/D : ' . date('d-m-Y', strtotime($SD)) . ' - <span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                                            }
                                                        }
                                                    }
                                                    break;
                                            }
                                            $tJabatan .= $out . '<div class="clearfix"></div>';
                                        }
                                        $tJabatan .= '</di>';
                                        if (@$item->PN_MENINGGAL == 1) {
                                            $spanMeninggal = ' - <span class="label label-danger">Meninggal</span> ';
                                        }
                                    }
                                    ?>
                                    <td><small><?php echo @$item->NAMA; ?></small></td>
                                    <td><small><?php
                                //echo @$tJabatan; 
//                                $JABATAN = $item->DESKRIPSI_JABATAN . ' - ' .$item->SUB_UNIT_KERJA . ' - ' . $item->UNIT_KERJA;
                                $JABATAN = $item->N_JAB . ' - ' . $item->N_SUK . ' - ' . $item->N_UK;
                                echo $JABATAN;
                                    ?></small></td>
                                    <!-- <td width="120" nowrap="" class="text-center"> -->
                                        <!-- <a class="btn-keljab" href="index.php/ereg/all_pn/addjabatan/<?php echo $item->ID_PN ?>/1/<?php echo $STATUS ?>" title="Riwayat Jabatan">[ Lihat ]</a> -->
                                    <!-- </td> -->

                                    <td width="40" nowrap="" style="text-align: pull-left;"><small>
                                            <button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/<?php echo $item->ID_PN . '/' . $item->ID; ?>" title="Preview"><i class="fa fa-search-plus"></i></button>

                                            <button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/mts/<?php echo $item->ID_JAB; ?>/1" title="Aktifkan Kembali"><i class="fa fa-user-plus"></i></button>
                                            <?php if ($this->makses->is_write) { ?>
                                                        <!--<button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/ereg/all_pn/editpn/<?php echo $item->ID_PN; ?>" title="Edit"><i class="fa fa-pencil"></i></button>-->
                                                <?php if ($havelhkpn[$item->ID_PN] !== true) { ?>
                                                            <!--<button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/all_pn/deletepn/<?php echo $item->ID_PN; ?>" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button>-->
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
</style>

<script language="javascript">
    $(document).ready(function() {
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

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#INST').select2('val', '-99');
            $('#ajaxFormCari').trigger('submit');
        });

        $('.btn-reset').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Reset Password', html, '', 'standart');
            });
            return false;
        });

        $(".btn-detail").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Detail PN/WL', html, '', 'large');
            });
            return false;
        });
        $(".btn-detail2").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Aktivasi Status PN/WL', html, '', 'large');
            });
            return false;
        });

        $('.btn-edit').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Pembaharuan PN/WL', html, '', 'large');
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

        $('.btn-keljab').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Riwayat Jabatan', html, '', 'large');
            });
            return false;
        });

        $('.btnAktifkan').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Mengaktifkan Data', html, '', 'standart');
            });
            return false;
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

        $('input[name="CARI[STAT]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getAkhirJabat') ?>",
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
                    $.ajax("<?= base_url('index.php/share/reff/getAkhirJabat') ?>/" + id, {
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
    $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2({
        minimumInputLength: 0,
        ajax: {
            url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
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
                $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
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
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


