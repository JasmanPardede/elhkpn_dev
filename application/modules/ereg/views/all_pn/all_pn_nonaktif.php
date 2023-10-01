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
$min_tahun = isset($min_tahun) && $min_tahun ? $min_tahun : date('Y');
$default_cari_tahun = isset($default_cari_tahun) && $default_cari_tahun ? $default_cari_tahun : date('Y');

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

$role_yang_diijinkan_instansi = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_OFFLINE')['filter_instansi']);
$role_yang_diijinkan_unit_kerja = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_OFFLINE')['filter_unit_kerja']);

?>

<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>PN/WL OFFLINE</strong></div>
    </div>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-body" >
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">

                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">WL Tahun :</label>
                                <div class="col-sm-6">
                                    <select id='CARI_TAHUN_WL' name="CARI[TAHUN_WL]">
                                        <option value="">All</option>
                                        <?php while ($min_tahun <= date('Y') + 1): ?>
                                            <option value="<?php echo $min_tahun; ?>" <?php echo $default_cari_tahun == $min_tahun ? "selected=selected" : ""; ?>><?php echo $min_tahun; ?></option>
                                            <?php $min_tahun ++; ?>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Status Lapor:</label>
                                <div  class="col-sm-6">
                                    <input type='text' class="input-sm select2 form-control" name='CARI[IS_WL]' style="border:none;padding:6px 0px;" id='CARI_IS_WL' value='<?php echo @$CARI['IS_WL']; ?>' placeholder="-- Pilih Status --">
                                </div>
                            </div>

                            <?php //if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 7 || $this->session->userdata('ID_ROLE') == 10 || $this->session->userdata('ID_ROLE') == 13 || $this->session->userdata('ID_ROLE') == 14 || $this->session->userdata('ID_ROLE') == 18 || $this->session->userdata('ID_ROLE') == 31 || $this->session->userdata('ID_ROLE') == 108):
                                if ($role_yang_diijinkan_instansi) : ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Instansi:</label>
                                    <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='<?php echo '1081'; ?>' placeholder="-- Pilih Instasi --">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">

                            <?php //if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 7 || $this->session->userdata('ID_ROLE') == 10 || $this->session->userdata('ID_ROLE') == 13 || $this->session->userdata('ID_ROLE') == 14 || $this->session->userdata('ID_ROLE') == 18 || $this->session->userdata('ID_ROLE') == 31 || $this->session->userdata('ID_ROLE') == 108):
                                 if ($role_yang_diijinkan_unit_kerja) : ?>
                                <div class="form-group" style="margin-bottom:12px">
                                    <label class="col-sm-4 control-label">Unit Kerja:</label>
                                    <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='<?php echo '590'; ?>' placeholder="-- Pilih Unit Kerja --">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeNEW_cari"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 col-sm-offset-4">
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <!-- <table id="dt_completeNEW" class="table table-striped"> -->
                        <thead>
                            <tr>
                                <th width="30">No.</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th width="50">WL Tahun</th>
                                <th width="50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->


<script type="text/javascript">
    // var gtblAllPnNonAktif;

    var tblAllPnNonAktif = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblAllPnNonAktif'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_page_offline/1'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari").val()});
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                passData.push({"name": "CARI[TAHUN_WL]", "value": $("#CARI_TAHUN_WL").val()});
                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'N_JAB') && !isEmpty(source.N_JAB)) {
                            arr_showed_string.push(source.N_JAB);
                        }
                        if (isObjectAttributeExists(source, 'N_SUK') && !isEmpty(source.N_SUK)) {
                            arr_showed_string.push(source.N_SUK);
                        }
                        if (isObjectAttributeExists(source, 'N_UK') && !isEmpty(source.N_UK)) {
                            arr_showed_string.push(source.N_UK);
                        }
                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: false
                },
                {"mDataProp": "TAHUN_WL", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var get_full_date = new Date();
                        var getYear = get_full_date.getFullYear()-2;
                        var wl_tahun = source.TAHUN_WL;

                        var stl = false;

                        var btnPreview = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID_JAB + '/' + source.TAHUN_WL + '" title="Preview" onclick="onButton.click(this);"><i class="fa fa-search-plus"></i></button>';
                        var btnDetail3 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/editpn_daftarnonaktif/2/daftarindividu/' + source.ID_PN + '/' + source.ID_JAB + '/1" title="Online" onclick="onButton.click(this);"><i class="fa fa-user-plus"></i></button>';
                        var btnAksi = '';

                        if (!stl) {
                            if (wl_tahun <= getYear){
                                btnAksi += '<small>' + btnPreview + ' ' + '</small>';
                            }else{
                                btnAksi += '<small>' + btnPreview + ' ' + btnDetail3 + '</small>';
                            }
                        }

                        return (btnAksi).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ]
        }
    };

    var onButton = {
        go: function(obj, size) {

            if (!isDefined(size)) {
                size = 'large';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Wajib Lapor', html, '', size);
            });
            return false;
        },
        click: function(self) {
            onButton.go(self);
        }
    };

    var gtblAllPnNonAktif;
    $(function() {

        $('.btn-edit').click(function(e) {
            onButton.edit.click(this);
        });

        $('.btn-delete').click(function(e) {
            onButton.delete.click(this);
        });

        gtblAllPnNonAktif = initDtTbl(tblAllPnNonAktif);
        if ($("#CARI_UNIT_KERJA").val() === '') {
            reloadTableDoubleTime(gtblAllPnNonAktif);
        }
    });

    var clearPencarian = function() {
//        $('#CARI_IS_WL').val('');
//        $('#CARI_TAHUN_WL').val('');
//        $('#CARI_INSTANSI').val('');
//        initiateCariInstansi();
        $('#inp_dt_completeNEW_cari').val('');
        reloadTableDoubleTime(gtblAllPnNonAktif);
    };

    var submitCari = function() {
        reloadTableDoubleTime(gtblAllPnNonAktif);
    };

    var initiateCariInstansi = function() {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();

        var LEMBAGA = '1081';
<?php //if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31" || $this->session->userdata('ID_ROLE') == 108):        
        $role = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_OFFLINE')['default_instansi_is_kpk']);
        if ($role) : ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
            //            initiateSelect2CariUnitKerja('1081');
<?php else: ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
            LEMBAGA = '<?php echo $default_instansi; ?>';
<?php endif; ?>

        initiateSelect2CariUnitKerja(LEMBAGA);

        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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
                var id = $('#CARI_INSTANSI').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga'); ?>/" + id, {
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
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");
//                    initiateSelect2CariUnitKerja(iins);
                }
            }
        });

    }/* End initiate Cari Instansi */

    var initiateSelect2CariUnitKerja = function(LEMBAGA) {
        var set_default_to_null = '';
        if (LEMBAGA !== '1081') {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
        }
        else {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_to_null = 'pencegahan';
        }

        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null=" + set_default_to_null,
                dataType: 'json',
                quietMillis: 1000,
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
                var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
//                LEMBAGA = isDefined($('#CARI_INSTANSI').val()) ? $('#CARI_INSTANSI').val() : '<?php echo $default_instansi; ?>';
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null=" + set_default_to_null, {
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
        };

        var dsuk = null;

        if (isDefined(LEMBAGA)) {
            var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
//            LEMBAGA = isDefined($('#CARI_INSTANSI').val()) ? $('#CARI_INSTANSI').val() : '<?php echo $default_instansi; ?>';

            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=" + set_default_to_null, {
                dataType: "json"
            }).success(function(data) {
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function(e) {
                        reloadTableDoubleTime(gtblAllPnNonAktif);
                    });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
                    }
                }

            });
            set_default_to_null = '';
        }
    };

    $(document).ready(function() {
        //initiateCariInstansi();
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
        $('#CARI_INSTANSI').change(function() {
            $("#ajaxFormCari").trigger('submit');
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
                OpenModalBox('Aktifkan Status PN/WL', html, '', 'large');
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

        var data_is_wl = [{id: 0, text: 'Belum'}, {id: 1, text: 'Sudah'}];

        $('#CARI_IS_WL').select2({
            data: data_is_wl
        });

        $("#CARI_TAHUN_WL").select2();
        initiateCariInstansi();

        $('#CARI_INSTANSI').change(function(event) {
            initiateSelect2CariUnitKerja($(this).val());
        });

<?php // if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"):    ?>
//            initiateSelect2CariUnitKerja(1081);
<?php // else:    ?>
//            initiateSelect2CariUnitKerja(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
<?php // endif;    ?>
    });

</script>

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

<?php
//$js_page = isset($js_page) ? $js_page : '';
//if (is_array($js_page)) {
// foreach ($js_page as $page_js) {
//     echo $page_js;
//  }
//} else {
//  echo $js_page;
//}
?>

<style>
    td .btn {
        margin: 0px;
    }
</style>
