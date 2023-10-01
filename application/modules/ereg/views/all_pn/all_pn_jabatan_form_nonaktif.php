<?php
if($form=='kllJabatan'){
?>
<div id="wrapperKllJabatan">
    <i><b>" <?= @$PN->NIK.' - '.$PN->NAMA; ?> "</b></i>
    <br><br>
    <div class="testttttt">
        <?php if($STATUSPN == 'Meninggal'){ echo '';}else{ ?>
            <button onclick="showaddkll();" value="<?= @$id_pn; ?>" type="button" href="javascript:void(0);" class="btn btn-sm btn-addJab btn-primary"><i class="fa fa-plus"></i> Tambah</button><br><br>
        <?php } ?>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Jabatan / Eselon</th>
                    <th>Lembaga</th>
                    <th>Unit Kerja</th>
                    <!-- <th>TMT / SD</th> -->
                    <th>TMT</th>
                    <th>File SK</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody> 
                <?php $i = 1; foreach ($pnJabatan as $jab): 
                    // $eselon = '';
                    if ($jab->ESELON == 0) {
                        $esel = 'Non Eselon';
                    }
                    else
                    {
                        $esel = 'Eselon '.$jab->ESELON;
                    }
                    $calon = '';
                    if (@$jab->IS_CALON == 1) {
                        $calon = '<span class="label label-primary">Calon</span>';
                    }
                ?>
                    <tr>
                        <td><?= @$i++; ?></td>
                        <td>
                            <?php
                                $ID = @$jab->ID_JABATAN;
                                $ID_STATUS_AKHIR_JABAT = @$jab->id_jabat;
                                $STATUS = @$jab->STATUS;
                                $ID_PN_JABATAN = @$jab->ID_PN_JABATAN!='NULL' ? @$jab->ID_PN_JABATAN : null;
                                $LEMBAGA = @$jab->LEMBAGA;
                                $JABATAN = ucwords(strtolower(@$jab->NAMA_JABATAN.' /  '.$esel));
                                $TMT = @$jab->TMT;
                                $SD = @$jab->SD;
                                $IS_CALON = @$jab->IS_CALON;
                                $INST_TUJUAN = @$jab->INST_TUJUAN;

                                $out = '';
                                $out .= $JABATAN;
                                if($IS_CALON==1 && (($ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN==null)){// calon pn
                                    $pnposisi = 'calon';
                                }else if(( $ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN==null){
                                    // jabatan masih aktif & tidak dimutasikan
                                    $pnposisi = 'aktif';
                                }else{
                                    if($ID_PN_JABATAN!=null){// sedang mutasi
                                        $pnposisi = 'mutasi';
                                    }else{// jabatan sudah berakhir
                                        $pnposisi = 'berakhir';
                                    }
                                }

                                if($ID_STATUS_AKHIR_JABAT == '3'){
                                    $meninggal = TRUE;
                                }

                                switch($pnposisi){
                                    case 'calon' : 
                                        $out = ' <span class="label label-warning">Calon</span> ';
                                        $out .= $JABATAN;
                                    break;
                                    case 'aktif' : 
                                    break;
                                    case 'mutasi' : 
                                        $out .= ' - <span class="label label-warning">sedang proses mutasi ke '.$INST_TUJUAN.'</span>';
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

                                        if($IS_CALON==1){
                                            $out = ' <span class="label label-warning">Calon</span> ';
                                            $out .= '<span class="label '.$labelstyle.'">'.$STATUS.'</span> ';
                                            $out .= $JABATAN;
                                        }else{
                                            $out .= ' - <span class="label '.$labelstyle.'">'.$STATUS.'</span> ';
                                        }
                                    break;
                                }
                            ?>
                            <?=$out?></td>
                        <td><?= @$jab->INST_NAMA; ?></td>
                        <td><?= @$jab->UK_NAMA; ?></td>
                        <!-- <td><?= @(@$jab->TMT != '' ? (@$jab->TMT != '1970-01-01' ? date('d-m-Y',strtotime(@$jab->TMT)) : '-') : '-').' / '.(@$jab->SD != '' ? (@$jab->SD != '1970-01-01' ? @date('d-m-Y',strtotime(@$jab->SD)) : 'Sekarang') : 'Sekarang'); ?></td> -->
                        <td align="center"><?= @(@$jab->TMT != '' ? (@$jab->TMT != '1970-01-01' ? date('d-m-Y',strtotime(@$jab->TMT)) : '-') : '-'); ?></td>
                        <td><?php if($jab->FILE_SK != ''){ ?><a href="<?php echo base_url();?>uploads/data_jabatan/<?php echo $PN->NIK;?>/<?php echo $jab->FILE_SK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.@$PN->NIK.'/'.$jab->FILE_SK); ?></a><?php }else{ echo '-'; } ?></td>
                        <td>
                            <?php 
                                $out1 = '';
                                switch($pnposisi){
                                    case 'calon' : 
                                        $out1 = ' <span class="label label-warning">Calon</span> - '.date('d/m/Y', strtotime(@$jab->SD));
                                    break;
                                    case 'aktif' : 
                                    break;
                                    case 'mutasi' : 
                                        $out1 .= ' - <span class="label label-warning">sedang proses mutasi ke '.$INST_TUJUAN.'</span>';
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

                                        if($IS_CALON==1){
                                            $out1  = '<span class="label label-warning">Calon</span> ';
                                            $out1 .= '<span class="label '.$labelstyle.'">'.$STATUS.'</span> ';
                                            $out1 .= $JABATAN;
                                        }else{
                                            $out1 .= '<span class="label '.$labelstyle.'">'.$STATUS.'</span> - '.date('d/m/Y', strtotime(@$jab->SD));
                                        }
                                    break;
                                }

                                echo $out1;
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default btn-batalAddJabatan" value="Tutup" onclick="CloseModalBox();">
        </div>
    </div>
    <div class="pnJabatan" data-id="1" style="display: none;">
        <form class="form-horizontal" id="saveklljabatan" action="javascript:;" class="ahaha" enctype="multipart/form-data">

            <div class="box-body form-horizontal">
                <div class="col-sm-12">
                    <input type="hidden" class="" name="PNID" id="PNID" style="" value="<?= @$id_pn; ?>" placeholder="">

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Calon <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <label><input type="radio" name="IS_CALON" value="1" class="ubahCalon" required> Calon</label>
                            <label><input type="radio" name="IS_CALON" checked value="0" class="ubahCalon" required> Menjabat</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Lembaga <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input required <?php echo ($is_instansi != false ? 'value="'.$is_instansi.'" readonly="readonly"' : '') ?> type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' placeholder="Lembaga">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Unit Kerja <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
<!--                             <select required name='UNIT_KERJA' id='UNIT_KERJA' style="border:0px;display:none;" class="form-control form-select2" placeholder="Unit Kerja">
                                <option value=""></option>
                            </select> -->
                            <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Jabatan <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                            <!-- <input required type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan"> -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Deskripsi Jabatan :</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="DESK_JABATAN" id="" style="" value="" placeholder="Deskripsi Jabatan">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Eselon <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <select required name="ESELON" id="" class="form-control" >
                                <option>-- Pilih Eselon --</option>
                                <?php foreach ($eselon as $esl): ?>
                                    <option value="<?= @$esl->ID_ESELON; ?>"><?= @$esl->ESELON; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Alamat Kantor <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <textarea required name="ALAMAT_KANTOR" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Email Kantor :</label>
                        <div class="col-sm-5">
                            <input type='email' class='form-control' value="" name="EMAIL_KANTOR" placeholder="Email Kantor">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">SK:</label>
                        <div class="col-sm-5">
                            <input type="file" name="FILE_SK" id='FILE_SK' class="FILE_SK">
                            <span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
                        </div>
                        
                    </div> 

                    <div class="form-group">
                        <label class="col-sm-4 control-label">TMT :</label>
                        <div class="col-sm-5">
                            <div class="col-md-5" style="margin-left: -14px;">
                                <input type="text" class="form-control datepicker TMT" name="TMT" value="<?php echo date('d-m-Y') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="pull-right">
                <button id="btnsaveKJ" type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="f_close(this);">
                <input type="hidden" class="" name="act" id="" style="" value="doinsert">
            </div>
        </form>
    </div>
    <div class="pnJabatan" data-id="2" style="display: none;">
        <div id="ctnEditKJ"></div>
    </div>
    <div class="pnJabatan" data-id="3" style="display: none;">
        <div id="ctnDltKJ"></div>
    </div>
<script type="text/javascript">
    $(document).ready(function() {

        $('.FILE_SK').change(function(){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_SK').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_SK').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        $('.ubahCalon').change(function(){
            var nil = $(this).val();
            // 1 = calon, 2 = menjabat
            if(nil == 1){
                $('.TMT').attr('required', false);
                $('.req').hide();
                $('.sd').attr('style','');
                $('.FILE_SK').attr('required', false);
            }else{
                $('.TMT').attr('required', true);
                $('.req').show();
                $('.FILE_SK').attr('required', true);
            }
        });

        var ID = $('#ID_PN').val();
        //ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);
        // ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/ereg/all_pn/showTable/18/'+ID, block:'#block', container:$('#jabatan').find('.contentTab')});

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });

        $('.btn-addJab').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Kelola Jabatan', html, '', 'standart');
            });            
            return false;
        });

        var val = $('#LEMBAGA').val();
        if(val != ''){
            unit_kerjae();
        }
    });

    // function unit_kerjae() {
    //     var IDLEM = $('#LEMBAGA').val();
    //     $("#UNIT_KERJA").empty();
    //     $.post("index.php/efill/lhkpn/daftar_UK/" + IDLEM, function(html) {
    //         $.each(html, function(index, value) {
    //             $("#UNIT_KERJA").append("<option value='" + index + "'>" + value + "</option>");
    //         });
    //         $("#UNIT_KERJA").show();
    //         $("#UNIT_KERJA").select2();
    //     }, 'json');
    // }

    function showaddkll () {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });

        var tex = $('#PNID').val();

        $("form#saveklljabatan").submit(function(event) {
            var urll = 'index.php/ereg/all_pn/saveklljabatan';
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function (html) {
                    msg = {
                       success : 'Data Berhasil Disimpan!',
                       error  : 'Data Gagal Disimpan!'
                    };
                    if (html == 0) {
                       alertify.error(msg.error);
                    } else if(html == 2){
                        msgg = {
                           error  : 'Tidak boleh lebih dari 5 data'
                        };
                       alertify.error(msgg.error);
                    }else{
                        alertify.success(msg.success);
                    }
                    if(html == 1){
                        var iii = "index.php/ereg/all_pn/nonaktif";
                        ng.LoadAjaxContent(iii);
                        CloseModalBox();
                    }else{
                        console.log('error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        $('.testttttt').slideUp('slow', function(){
            $('div[data-id="1"]').slideDown('slow');
//            $('#PNID').val(tex);
        });

        $('input[name="LEMBAGA"]').select2({
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

        $('#LEMBAGA').change(function(event) {
            $('input[name="UNIT_KERJA"]').prop('disabled', false);
            $('input[name="JABATAN"]').prop('disabled', false);

            $('input[name="UNIT_KERJA"]').select2('val', '');
            $('input[name="JABATAN"]').select2('val', '');
            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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
            
            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>/"+LEMBAGA+"/"+id, {
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
    }

    function showDeleteKllJab(param,pn){
        $('.testttttt').slideUp('slow', function(){
            $('div[data-id="3"]').slideDown('slow');
            var target = "index.php/ereg/all_pn/showDeleteKllJab/"+param+"/"+pn;
            $('div[data-id="3"]').load(target);
        });
    }

    function f_close()
    {
        $('.pnJabatan').slideUp('slow', function () {
            $('.testttttt').slideDown('slow');
        })
    }

</script>
</div>
<?php
}
?>