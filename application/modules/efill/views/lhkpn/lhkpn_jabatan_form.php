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
 * @package Views/lhkpn
*/
?>
<style type="text/css">
    .form-select2 {
        padding: 6px 0px !important;
        margin: 0px !important;
    }
    .formisi{
        text-align: left !important;
    }
</style>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <!--  -->
    <form class="form-horizontal" method="post" id="ajaxFormAddJBTN" action="index.php/efill/lhkpn/savejabatan" enctype="multipart/form-data">
   
    <div class="box-body">
            <div class="col-sm-12">
        <div class="form-group">
            <label>Lembaga<font color='red'>*</font> </label><?= FormHelpPopOver('lembaga_jb'); ?>
       
                    <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' value=''  required>
               
        </div>
        <div class="form-group">
            <label>Unit Kerja<font color='red'>*</font> </label> <?= FormHelpPopOver('unit_kerja_jb'); ?>
          
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value=''  required disabled>
              
        </div>
         <div class="form-group">
            <label>Sub Unit Kerja<font color='red'></font> </label> <?= FormHelpPopOver('sub_unit_kerja_jb'); ?>
          
                    <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' disabled>
             
        </div>
        <div class="form-group">
            <label>Jabatan<font color='red'>*</font> </label> <?= FormHelpPopOver('jabatan_jb'); ?>
          
                    <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value=""  required disabled>
               
        </div>
      <!--   <div class="form-group">
            <label class="col-sm-3 control-label">Deskripsi Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="text" class="form-control" name="DESKRIPSI_JABATAN" id="DESKRIPSI_JABATAN" value="" placeholder="Deskripsi Jabatan" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Eselon<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                        <option value=''>-Pilih Eselon-</option>
                        <option value='1'>I</option>
                        <option value='2'>II</option>
                        <option value='3'>III</option>
                        <option value='4'>IV</option>
                        <option value='5'>Non-Eselon</option>
                    </select>
                </div>
            </div>
        </div> -->
        <div class="form-group">
            <label>Alamat Kantor :</label> <?= FormHelpPopOver('alamat_kantor_jb'); ?>
          
                    <textarea class='form-control' name="ALAMAT_KANTOR" ></textarea>
               
        </div>
     <!--    <div class="form-group">
            <label class="col-sm-3 control-label">Email Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='email' class='form-control' value="<?=@json_decode(@$DATA_PRIBADI->JABATAN)->EMAIL_KANTOR?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                </div>
            </div>
        </div>
        <div class="con-sk">
            <div class="form-group">
                <label class="col-sm-3 control-label">SK<font color='red'>*</font> :</label>
                <div class="col-sm-3">
                    <div class='col-sm-12'>
                        <input required type="file" name="FILE_SK[]" class="FILE_SK" id="FILE_SK">
                    </div>
                    <span class='help-block'>Type File: <span class="type-of">pdf</span> Max File: 500KB</span>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input name="type_file" value="0" checked="" type="radio">
                                Single File [pdf]
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input name="type_file" value="1" type="radio">
                                Multi File [image]
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1" style="display: none;" id="con-btn">
                    <button class="btn btn-success input-xs" type="button" onclick="addForm();"><i class="fa fa-plus"></i> </button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">TMT<!-- /SD -->
       
       </div>
    </div> 
        <div class="pull-right">
            <input type="hidden" name="ID_LHKPN" id="id_lhkpn" value="<?php echo $id_lhkpn;?>">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger btn-batalAddJabatan" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
    <div id="hid-sk" style="display: none;">
        <div class="form-group con-temp">
            <div class="col-sm-offset-3 col-sm-5">
                <div class='col-sm-12'>
                    <input type="file" name="FILE_SK[]" class="FILE_SK">
                </div>
                <span class='help-block'>Type File: <span class="type-of">pdf</span> Max File: 500KB</span>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-danger input-xs" type="button" onclick="removeRow(this);"><i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
    <!--  -->
</div>
<script type="text/javascript">
    var lembaga = '';

    function addForm()
    {
        var form_sk = $('#hid-sk').html();
        $('.con-sk').append(form_sk);
    }

    function removeRow(ele)
    {
        $(ele).closest('.form-group').remove();
    }

    $(document).ready(function() {
        var arr     = ['pdf'];

        $('input[name="type_file"]').change(function (e) {
            var val = $(this).val();
            if(val == '1'){
                $('#con-btn').show();
                $('.type-of').text('jpg, png, jpeg.');
                arr = ['jpg','png','jpeg'];
            }else{
                $('#con-btn').hide();
                $('.con-sk .con-temp').remove();
                $('.type-of').text('pdf.');
                arr = ['pdf'];
            }
        });

        $('.con-sk').on('change', '.FILE_SK', function(e){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
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
	
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        var ID = $('#id_lhkpn').val();
		objRequired = [
                    ['#LEMBAGA', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#DESKRIPSI_JABATAN', 1],
					['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1]
                ];
        ng.setRequired(objRequired);
        // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/18/'+ID, block:'#block', container:$('#jabatan').find('.contentTab')});

        $("form#ajaxFormAddJBTN").submit(function(event) {
            var urll = $(this).attr('action');
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
                    }else{
                        alertify.success(msg.success);
                    }

                    CloseModalBox();
                    
                    var ID = $('#id_lhkpn').val();
                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/18/' + ID + '/edit',
                        block: '#block',
                        container: $('#jabatan').find('.contentTab')
                    });

                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/17/' + ID + '/edit',
                        block: '#block',
                        container: $('#dokumenpendukung').find('.contentTab')
                    });

                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
         $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
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
            $('input[name="SUB_UNIT_KERJA"]').prop('disabled', false);
            $('input[name="JABATAN"]').prop('disabled', false);
            $('input[name="UNIT_KERJA"]').select2('val', '');
            $('input[name="SUB_UNIT_KERJA"]').select2('val', '');
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
            })
        });
          $('#UNIT_KERJA').change(function(event) {
            UNIT_KERJA = $(this).val();
  

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+UNIT_KERJA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+UNIT_KERJA+'/'+id, {
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
 $('#UNIT_KERJA').change(function(event) {
            UNIT_KERJA = $(this).val();

            $('input[name="SUB_UNIT_KERJA"]').select2({
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

        });
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <!--  -->
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/efill/lhkpn/savejabatan" enctype="multipart/form-data">
         <div class="con-body">
        <div class="box-body">
        <div class="form-group">
            <label>Lembaga<font color='red'>*</font> </label><?= FormHelpPopOver('lembaga_jb'); ?>
           
                    <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' value='<?php echo $item->LEMBAGA;?>' placeholder="lembaga" required>
              
        </div>
        <div class="form-group">
            <label>Unit Kerja<font color='red'>*</font> </label> <?= FormHelpPopOver('unit_kerja_jb'); ?>
           
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='<?php echo $item->UNIT_KERJA;?>' placeholder="Unit Kerja" required >
               
        </div>
         <div class="form-group">
            <label>Sub Unit Kerja<font color='red'></font> </label><?= FormHelpPopOver('sub_unit_kerja_jb'); ?>
           
                    <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='<?php echo $item->SUB_UNIT_KERJA;?>' placeholder="Unit Sub Unit Kerja" >
              
        </div>
        <div class="form-group">
            <label>Jabatan<font color='red'>*</font> </label><?= FormHelpPopOver('jabatan_jb'); ?>
           
                    <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" value="<?php echo $item->ID_JABATAN;?>" required placeholder="Jabatan">
               
        </div>
       <!--  <div class="form-group">
            <label class="col-sm-3 control-label">Deskripsi Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="<?php echo $item->DESKRIPSI_JABATAN;?>" required>
                </div>
            </div>
        </div> -->
     <!--    <div class="form-group">
            <label class="col-sm-3 control-label">Eselon<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                        <option value='1' <?php if($item->ESELON == '1'){ echo 'selected'; }?>>I</option>
                        <option value='2' <?php if($item->ESELON == '2'){ echo 'selected'; }?>>II</option>
                        <option value='3' <?php if($item->ESELON == '3'){ echo 'selected'; }?>>III</option>
                        <option value='4' <?php if($item->ESELON == '4'){ echo 'selected'; }?>>IV</option>
                        <option value='5' <?php if($item->ESELON == '5'){ echo 'selected'; }?>>Non-Eselon</option>
                    </select>
                </div>
            </div>
        </div> -->
      <!--   <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <textarea class='form-control' name="ALAMAT_KANTOR" placeholder="Alamat Kantor"><?php echo $item->ALAMAT_KANTOR;?></textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='email' class='form-control' value="<?php echo $item->EMAIL_KANTOR;?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                </div>
            </div>
        </div> -->
     <!--    <div class="con-sk">
            <div class="form-group">
                <label class="col-sm-3 control-label">SK :</label>
                <div class="col-sm-3">
                    <div class='col-sm-12'>
                        <input type="file" name="FILE_SK[]">
                        <input type='hidden' name="FILE_SK_OLD" value="<?php echo @$item->FILE_SK;?>" readonly>
                        <?php
                        if($item->FILE_SK){
                        ?>
                            <a href="<?php echo base_url('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$item->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$item->FILE_SK); ?></a>
                        <?php
                        }
                        ?>
                    </div>
                    <span class='help-block'>Type File: <span class="type-of">jpg, png, jpeg.</span> Max File: 500KB</span>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input name="type_file" value="0" checked="" type="radio">
                                Single File [pdf]
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input name="type_file" value="1" type="radio">
                                Multi File [image]
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1" style="display: none;" id="con-btn">
                    <button class="btn btn-success input-xs" type="button" onclick="addForm();"><i class="fa fa-plus"></i> </button>
                </div>
            </div>
        </div> -->
      
         </div>
        </div>             
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="ID_LHKPN" id="id_lhkpn" value="<?php echo $item->ID_LHKPN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger btn-batalAddJabatan" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
    <div id="hid-sk" style="display: none;">
        <div class="form-group con-temp">
            <div class="col-sm-offset-3 col-sm-5">
                <div class='col-sm-12'>
                    <input type="file" name="FILE_SK[]" class="FILE_SK">
                </div>
                <span class='help-block'>Type File: <span class="type-of">jpg, png, jpeg.</span> Max File: 500KB</span>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-danger input-xs" type="button" onclick="removeRow(this);"><i class="fa fa-times"></i> </button>
            </div>
        </div>
    </div>
    <!--  -->
</div>
<script type="text/javascript">
    var lembaga = '';

    function addForm()
    {
        var form_sk = $('#hid-sk').html();
        $('.con-sk').append(form_sk);
    }

    function removeRow(ele)
    {
        $(ele).closest('.form-group').remove();
    }
     $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });

    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/18/'+ID, block:'#block', container:$('#jabatan').find('.contentTab')});

        var arr     = ['pdf'];

        $('input[name="type_file"]').change(function (e) {
            var val = $(this).val();
            if(val == '1'){
                $('#con-btn').show();
                $('.type-of').text('jpg, png, jpeg.');
                arr = ['jpg','png','jpeg'];
            }else{
                $('#con-btn').hide();
                $('.con-sk .con-temp').remove();
                $('.type-of').text('pdf.');
                arr = ['pdf'];
            }
        });

        $('.con-sk').on('change', '.FILE_SK', function(e){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
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

        $("form#ajaxFormEdit").submit(function(event) {
            var urll = $(this).attr('action');
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
                    }else{
                        alertify.success(msg.success);
                    }

                    CloseModalBox();

                    <?php if($src == 'verif'){ ?>
                    ng.LoadAjaxTabContent({
                        url: 'index.php/ever/verification/vertable/jabatan/<?php echo substr(md5($item->ID_LHKPN), 5, 8);?>',
                        block: '#block',
                        container: $('#jabatan').find('.contentTab')
                    });
                    <?php }else{ ?>
                    var ID = $('#id_lhkpn').val();
                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/18/' + ID + '/edit',
                        block: '#block',
                        container: $('#jabatan').find('.contentTab')
                    });

                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/17/' + ID + '/edit',
                        block: '#block',
                        container: $('#dokumenpendukung').find('.contentTab')
                    });
                    <?php } ?>
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        var lembaga = $('input[name="LEMBAGA"]').val();

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
                    $.ajax("<?=base_url('index.php/share/reff/getLembaga')?>"+'/'+id, {
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

        $('input[name="LEMBAGA"]').on("change", function (e) {
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
            var UNIT_KERJA = $(this).val();

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+UNIT_KERJA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+UNIT_KERJA+'/'+id, {
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

 $('#UNIT_KERJA').change(function(event) {
            UNIT_KERJA = $(this).val();

            $('input[name="SUB_UNIT_KERJA"]').select2({
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

        });
        var lembaga = '<?= @$item->LEMBAGA; ?>';

        // alert(lembaga);

        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+UNIT_KERJA,
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
                    $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+UNIT_KERJA+'/'+id, {
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

        $('input[name="UNIT_KERJA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUnitKerja')?>"+'/'+lembaga,
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
                    $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>"+'/'+lembaga+'/'+id, {
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
  
            $('input[name="SUB_UNIT_KERJA"]').select2({
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

     

    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
Benarkah Akan Menghapus Harta Tidak Bergerak dibawah ini ?
<form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savejabatan" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-3 control-label">Lembaga :</label>
        <div class="col-sm-9 control-label formisi">
            <?php echo $item->INST_NAMA;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Unit Kerja :</label>
        <div class="col-sm-9 control-label formisi">
                <?php echo $item->UK_NAMA;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Jabatan :</label>
        <div class="col-sm-9 control-label formisi">
            <?php echo $item->NAMA_JABATAN;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Deskripsi Jabatan :</label>
        <div class="col-sm-9 control-label formisi">
            <?php echo $item->DESKRIPSI_JABATAN;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Eselon :</label>
        <div class="col-sm-9 control-label formisi">
            <?php if($item->ESELON == '1'){ echo 'I'; }?>
            <?php if($item->ESELON == '2'){ echo 'II'; }?>
            <?php if($item->ESELON == '3'){ echo 'III'; }?>
            <?php if($item->ESELON == '4'){ echo 'IV'; }?>
            <?php if($item->ESELON == '5'){ echo 'Non-Eselon'; }?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Alamat Kantor :</label>
        <div class="col-sm-9 control-label formisi">
            <?php echo $item->ALAMAT_KANTOR;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Email Kantor :</label>
        <div class="col-sm-9 control-label formisi">
            <?php echo $item->EMAIL_KANTOR;?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">SK :</label>
        <div class="col-sm-9 control-label formisi">
            <a href="<?php echo base_url('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$item->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$item->FILE_SK); ?></a>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">TMT/SD :</label>
        <div class="col-sm-9 control-label formisi">
            <?php echo date('d/m/Y', strtotime($item->TMT));?> s/d <?php echo @$item->SD=='0000-00-00' || @$item->SD=='' || @$item->SD==NULL?'':@date('d/m/Y', strtotime(@$item->SD));?>
        </div>
    </div>          
    <div class="pull-right">
        <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
        <input type="hidden" name="OLD_FILE" value="<?php echo $item->FILE_SK;?>">
        <input type="hidden" name="ID_LHKPN" id="id_lhkpn" value="<?php echo $item->ID_LHKPN;?>">
        <input type="hidden" name="act" value="dodelete">
        <button type="submit" class="btn btn-md btn-danger">Hapus</button>
        <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</form>
<script type="text/javascript">
    var lembaga = '';
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        var ID = $('#id_lhkpn').val();
        <?php if($src == 'verif'){ ?>
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/ever/verification/vertable/jabatan/<?php echo substr(md5($item->ID_LHKPN), 5, 8);?>', block:'#block', container:$('#jabatan').find('.contentTab')});
        <?php }else{ ?>
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/18/'+ID + '/edit', block:'#block', container:$('#jabatan').find('.contentTab')});
        <?php } ?>

    });
</script>
<?php
}
?>
<?php
if($form=='editprimary'){
?>
Apakah Anda Yakin akan Memilih Jabatan ini Sebagai Jabatan Utama ? 
<form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/SetPrimary/<?php echo $item->ID.'/'.$item->ID_LHKPN; ?>"enctype="multipart/form-data">
  
    <div class="pull-right">
        <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
        <input type="hidden" name="ID_LHKPN" id="id_lhkpn" value="<?php echo $item->ID_LHKPN;?>">
        <input type="hidden" name="act" value="doupdate">
        <button type="submit" class="btn btn-md btn-primary">Simpan</button>
        <input type="reset" class="btn btn-md btn-danger" value="Batal" onclick="CloseModalBox();">
    </div>
</form>
<script type="text/javascript">
    var lembaga = '';
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        var ID = $('#id_lhkpn').val();
        <?php if($src == 'verif'){ ?>
        ng.formProcess($("#ajaxFormDelete"), '', '', ng.LoadAjaxTabContent, {url:'index.php/ever/verification/vertable/jabatan/<?php echo substr(md5($item->ID_LHKPN), 5, 8);?>', block:'#block', container:$('#jabatan').find('.contentTab')});
        <?php }else{ ?>
        ng.formProcess($("#ajaxFormDelete"), '', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/18/'+ID + '/edit', block:'#block', container:$('#jabatan').find('.contentTab')});
        <?php } ?>

    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>

<?php
}
?>