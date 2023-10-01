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
 * @package Views/all_pn
*/
?>
<style type="text/css">
    .form-select2 {
        padding: 6px 0px !important;
        margin: 0px !important;
    }
</style>
<?php
if($form=='kllJabatan'){
?>
<div id="wrapperKllJabatan">
    <form class="form-horizontal ahaha" id="saveklljabatan" action="javascript:;" enctype="multipart/form-data">
        <input type="hidden" name="PNID" value="<?php echo $id_pn; ?>" />
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Status Jabatan <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <?php if($status) { ?>
                            <label><input type="radio" <?php echo (($status) ? 'checked' : '')?> name="IS_CALON" value="1" class="ubahCalon" required> Calon</label>
                        <?php } ?>
                        <label><input type="radio" <?php echo ((!$status) ? 'checked' : '')?> name="IS_CALON" value="0" class="ubahCalon" required> Menjabat</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Lembaga <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input required <?php echo ($is_instansi != false ? 'value="'.$is_instansi.'" readonly="readonly"' : '') ?> type='text' class="form-control form-select2" name="LEMBAGA" style="border:none;" id="LEMBAGA" placeholder="lembaga">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Unit Kerja <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input required type="text" class="form-control form-select2" name="UNIT_KERJA" style="border:none;" id='UNIT_KERJA' value="" placeholder="Unit Kerja">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Jabatan <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input required type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Deskripsi Jabatan <span class="red-label">*</span> :</label>
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
                    <label class="col-sm-4 control-label">SK<font color='red' class="req">*</font> :</label>
                    <div class="col-sm-5">
                        <input type="file" name="FILE_SK" id='FILE_SK' class="FILE_SK">
                        <span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">TMT/SD :</label>
                    <div class="col-sm-5">
                        <div class="col-md-5" style="margin-left: -14px;">
                            <input type="text" class="form-control datepicker TMT" name="TMT" value="<?php echo date('d-m-Y') ?>">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control datepicker" name="SD" value="" >
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <button id="btnsaveKJ" type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="backForm()">
            <input type="hidden" class="" name="act" id="" style="" value="doinsert">
        </div>
    </form>

    <script type="text/javascript">
        var idForm = $('#saveklljabatan');

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
            }).on("change", function(e) {
                $('input[name="UNIT_KERJA"]').prop('disabled', false);
                $('input[name="JABATAN"]').prop('disabled', false);

                $('#UNIT_KERJA').show();
                $('#JABATAN').show();

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
            }).on("change", function(e) {
                var lembaga = $(this).val();

                $('input[name="JABATAN"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga,
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
                            $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga+'/'+id, {
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

            showaddkll();
        });
        
        if('<?php echo $is_instansi ?>') {
            LEMBAGA = $('#LEMBAGA').val();
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
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+LEMBAGA+'/'+id, {
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
        }

        function showaddkll () {
            $('.datepicker', idForm).datepicker({
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

                        if(html == 1){
                            ng.LoadAjaxContent('<?php echo @$redirect; ?>');

                            $.post('index.php/ereg/all_pn/cekNIK/'+$('#NIK').val(), function (data) {
                                if(data != 0){
                                    $("#wrapperFormPNExist").html(data);
                                    alertify.success('Jabatan berhasil di tambahkan!');
                                }
                            });
                        }else{
                            alertify.error(html);
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });
        }
    </script>
</div>
<?php
}
?>