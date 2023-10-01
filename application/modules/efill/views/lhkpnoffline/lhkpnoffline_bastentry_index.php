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
 * @package Views/efill/penerimaan
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa <?php echo $icon;?>"></i>
        BAST Dokumen Fisik untuk Koordinator Entri
    </h1>
    <?php echo $breadcrumb;?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-md-offset-4 col-xs-8 col-sm-8 col-md-8 col-lg-8">
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                            <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Status :</label>
                                        <div class="col-sm-6">
                                            <select name="CARI[STATUS]" class="form-control input-sm">
                                                <option value="">-- Pilih Status --</option>
                                                <option <?php echo @$CARI['STATUS'] == '0' ? 'selected="selected"' :'' ?> value="0">Belum BAST</option>
                                                <option <?php echo @$CARI['STATUS'] == '1' ? 'selected="selected"' :'' ?> value="1">Sudah BAST</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cari :</label>
                                        <div class="col-sm-2">
                                            <select name="CARI[BY]" class="form-control input-sm">
                                                <option <?php echo (@$CARI['BY'] == 'AGENDA') ? 'selected' : ''; ?> value="AGENDA">NO Agenda</option>
                                                <option <?php echo (@$CARI['BY'] == 'NAMA') ? 'selected' : ''; ?> value="NAMA">Nama</option>
                                                <option <?php echo (@$CARI['BY'] == 'NIK') ? 'selected' : ''; ?> value="NIK">NIK</option>
                                                <!-- <option <?php echo (@$CARI['BY'] == 'PN') ? 'selected' : ''; ?> value="PN">PN</option> -->
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" style="width: 190px;" class="form-control input-sm pull-right" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-3 col-sm-offset-4">
                                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari <!-- <i class="fa fa-search"></i> --></button>
                                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="f_clear();">Clear</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php
                    if($total_rows){
                        ?>
                        <strong><span id="jml">0</span> Dokumen yang dipilih!</strong>
                    <table
                        class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                 <th width="30"><input type="checkbox" onClick="chk_all(this);" /></th>
                                <th width="30">No.</th>
                                <th>Tgl Terima</th>
                                <th>PN / WL</th>
                                <th>Jenis Dokumen</th>
                                <th>BAST</th>
                                <!-- <th>Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            // display($items);
                            $aId = @explode(',', $id);
                            foreach ($items as $item) {
                                ?>
                            <tr>
                                 <td>
                                    <?php if($item->IS_DITERIMA_KOORD_ENTRY == '0'){ ?>
                                        <?php echo (in_array($item->ID_PENERIMAAN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="'.$item->ID_PENERIMAAN.'" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="'.$item->ID_PENERIMAAN.'" onclick="chk(this);" />') ?>
                                    <?php } ?>
                                    </td>
                                <td><?php echo ++$i; ?>.</td>
                                <td>
                                    <?php echo date('d/m/Y',strtotime($item->TANGGAL_PENERIMAAN)); ?> <br>
                                    Oleh : <?php echo $item->USERNAME_CS; ?>
                                </td>
                                <td>
                                    <table style="background: transparent;">
                                        <tbody>
                                            <tr>
                                                <th width="80px" style="vertical-align: top;">NIK</th>
                                                <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                <td class="nik"><?php echo $item->NIK; ?></td>
                                            </tr>
                                            <tr>
                                                <th style="vertical-align: top;">NAMA</th>
                                                <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                <td class="nama"><?php echo $item->NAMA; ?></td>
                                                <td class="agenda" style="display: none;"><?php echo $item->TAHUN_PELAPORAN.'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN ?></td>
                                                <td class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN),5,8); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table style="background: transparent;">
                                        <tbody>
                                            <?php if ($item->JENIS_LAPORAN <> '4') { ?>
                                            <tr>
                                                <th width="120px" style="vertical-align: top;">LHKPN Khusus </th>
                                                <td>
                                                    <span style="margin-right: 10px;">:</span>
                                                    <?php
                                                        if ($item->JENIS_LAPORAN == '1') {
                                                            echo 'Calon Penyelenggara Negara';
                                                        } else if ($item->JENIS_LAPORAN == '2') {
                                                            echo 'Awal Menjabat';
                                                        } else if ($item->JENIS_LAPORAN == '3') {
                                                            echo 'Akhir Menjabat';
                                                        } else {}
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } else { ?>
                                            <tr>
                                                <th width="120px" style="vertical-align: top;">LHKPN Periodik </th>
                                                <td>
                                                    <span style="margin-right: 10px;">:</span>
                                                    <?php
                                                        if ($item->TAHUN_PELAPORAN !== '0') {
                                                            echo $item->TAHUN_PELAPORAN;
                                                         } else {
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <th style="vertical-align: top;">Jenis Dokumen</th>
                                                <td width="110px"><span style="margin-right: 10px;">:</span> <?php echo $item->JENIS_DOKUMEN ?></td>
                                            </tr>
                                            <tr>
                                                <th style="vertical-align: top;">Melalui</th>
                                                <td><span style="margin-right: 10px;">:</span> <?php echo $item->MELALUI ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <?php echo ($item->IS_DITERIMA_KOORD_ENTRY == '1' ? 'Sudah ('.tgl_format($item->TGL_BAST_KOORD_ENTRY).')' : 'Belum') ?>
                                </td>
<!--                                <td>-->
<!--                                    <button type="button" class="btn btn-sm btn-default btn-print" href="--><?php //echo $urlEdit.'/'.$item->ID_PENERIMAAN.'/printitem'; ?><!--" title="Cetak">-->
<!--                                        <i class="fa fa-print"></i>-->
<!--                                    </button>-->
<!--                                </td>-->
                            </tr>
                            <?php
                            $end = $i;
                            }
                            ?>
                        </tbody>
                    </table>
                      <div class="box-footer clearfix">
                    <?php
                    if($total_rows){
                        ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">
                            Showing
                            <?php echo  $start; ?>
                            to
                            <?php echo  $end; ?>
                            of
                            <?php echo  $total_rows; ?>
                            entries
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

                    <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="f_bast();"><i class="fa fa-plus"></i> Daftar BAST</button>

                    <table id="con-bast" class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-top: 10px;">
                        <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th width="100px">NIK</th>
                            <th>Nama</th>
                            <th width="200px" align="center">No Agenda</th>
                            <th width="100px">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; if(isset($item_selected)){ foreach(@$item_selected as $row): ?>
                            <tr>
                                <td align="center"><?php echo $i; ?></td>
                                <td><?php echo $row->NIK?></td>
                                <td><?php echo $row->NAMA?></td>
                                <td><a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($row->ID_LHKPN),5,8);?>" onclick="return tracking(this)"><?php echo $item->TAHUN_PELAPORAN.'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN ?></a></td>
                                <td align="center"><button type="button" class="btn btn-default" onClick="f_batal(this);" data-id="<?php echo $row->ID_PENERIMAAN ?>" >Hapus</button> </td>
                            </tr>
                            <?php $i++; endforeach; } ?>
                        <?php if(@$id == ''){ ?>
                            <tr id="not-found">
                                <td colspan="5" align="center"><strong>Data not Found</strong></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <form style="margin-top: 20px;" method="post" class='form-horizontal' id="ajaxFormAdd" action="index.php/efill/lhkpnoffline/save/bastentry">
                        <div id="wrapperFormPenugasan" class="form-horizontal">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tanggal Serah Terima<font color='red'>*</font>:</label>
                                <div class="col-sm-4">
                                    <input type="text" name="TANGGAL" value="<?php echo date('d/m/Y');?>" required placeholder='DD/MM/YYYY' class="date-picker form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Koordinator Entry <font color='red'>*</font>:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="petugas" name="PETUGAS" id="PETUGAS" required placeholder="Petugas" style="width: 337px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"></label>
                                <div class="col-sm-8">
                                    <input type="hidden" name="act" value="doinsert">
                                    <input type="submit" name="" value="Simpan & Cetak BAST" class="btn btn-primary">
                                    <input type="reset" name="" value="Batal" class="btn btn-danger">
                                </div>
                            </div>
                        </div>
                    </form>
                        <?php
                    }else{
                        echo 'Data Not Found...';
                    }
                    ?>
                </div>
                <!-- /.box-body -->
            <!--     <div class="box-footer clearfix">
                    <?php
                    if($total_rows){
                        ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">
                            Showing
                            <?php echo  $start; ?>
                            to
                            <?php echo  $end; ?>
                            of
                            <?php echo  $total_rows; ?>
                            entries
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
                </div> -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<script language="javascript">
    var idChk = [];
    var jml = 0;

    function f_clear(){
        var form = $('#ajaxFormCari');
        $('input', form).val('');
        $('input[name="id"]', form).val('');
        $('select', form).val('');
        $('input[name="CARI[CS]"]', form).val('0');
        form.trigger('submit');
    }

    function chk_all(ele)
    {
        if($(ele).is(':checked')){
            $('.chk').prop('checked', true);
        }else{
            $('.chk').prop('checked', false);
        }

        $('.chk:visible').each(function(){
            chk(this);
        });
    }

    function chk(ele){
        var val = $(ele).val();
        idChk.push(val);
    }

    function f_bast(){
        $('.chk').each(function(){
            var val = $(this).val();
            if($(this).is(':checked') && $(this).is(':visible')){
                jml++;
                $(this).hide();
                var table = $(this).closest('tr');
                var nik = $('.nik', table).text();
                var nama = $('.nama', table).text();
                var agenda = $('.agenda', table).text();
                var lhkpn = $('.lhkpn', table).text();

                $('#con-bast tbody').append('<tr><td align="center">'+jml+'</td><td>'+nik+'</td><td>'+nama+'</td><td><a href="index.php/efill/lhkpnoffline/tracking/show/'+lhkpn+'" onclick="return tracking(this);">'+agenda+'</a></td><td align="center"><button class="btn btn-default" data-id="'+val+'" type="button" onClick="f_batal(this);">Hapus</button></td></tr>');
            }
        })

        count();
    }

    function f_batal(ele) {
        var id = $(ele).attr('data-id');
        var index = idChk.indexOf(id);    // <-- Not supported in <IE9
        if (index !== -1) {
            idChk.splice(index, 1);
        }

        $(ele).closest('tr').remove();
        $('.chk[value="'+id+'"]').show();
        $('.chk[value="'+id+'"]').prop('checked', false);

        count();
    }

    function count(){
        var jml = parseInt($('.chk:checked').length);
        $('#jml').text(jml);
        if(jml > 0){
            $('#con-bast #not-found').hide();
        }else{
            $('#con-bast #not-found').show();
        }

        var tmo = $('#ajaxFormCari input[name="id"]').val(idChk.join());
    }

    function tracking(ele)
    {
        url = $(ele).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
        });
        return false;
    }

    $(document).ready(function() {
        $('.date-picker').datepicker({
                orientation: "left",
                format: 'dd/mm/yyyy',
                autoclose: true
        });
        var tmo = $('#ajaxFormCari input[name="id"]').val();
        if(tmo != '') {
            idChk = tmo.split(',');
            jml = idChk.length;
        }

        count();

        $('.petugas').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUser/'.$role)?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getUser/'.$role)?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                if(state.id == '0'){
                    return '-- Pilih Koord Entry dahulu --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            },
            formatSelection:  function (state) {
                if(state.id == '0'){
                    return '-- Pilih Koord Entry dahulu --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            }
        });

        $(".pagination").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });
        $(".breadcrumb").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $("#ajaxFormAdd").submit(function(e) {
            var url  = $(this).attr('action');
            var urll = 'index.php/efill/lhkpnoffline/index/bastentry';
            $('#ajaxFormAdd input[name="id"]').val(idChk.join());

            if(idChk.length != 0) {
                $.post($(this).attr('action'), $(this).serializeArray(), function (data) {
                    alertify.success('Data berhasil di simpan!');
                    html = '<iframe src="'+data+'" width="100%" height="500px"></iframe>';
                    OpenModalBox('Print BAST', html, '', 'large');
                    ng.LoadAjaxContentPost(urll, $(this));
                    // ng.LoadAjaxContentPost('index.php/efill/lhkpnoffline/index/bastentry');    
                });
                
            }else{
                alertify.error('Tidak ada data yg dipilih!');
            }
            return false;
        });
        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="'+url+'" width="100%" height="500px"></iframe>';
            OpenModalBox('Print Coversheet', html, '', 'large');
            return false;
        });
        // ctrl + a
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.which === 65 || e.which === 97) {
                e.preventDefault();
                $('#btn-add').trigger('click');
                return false;
            }
        });
    });
</script>

<style>
td .btn {
    margin: 0px;
}
</style>
