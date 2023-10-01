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
 * @package Views/Agama
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <div id="wrapperFormPenugasan">
            <ol class="breadcrumb">
            <li>Form Penugasan</li>
        </ol>
    <form method="post" id="ajaxFormAddPenugasan" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">Jenis Tugas <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <div class="radio">
                        <label><input type="radio" name="JENIS_TUGAS" value="create"> Create LHKPN</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="JENIS_TUGAS" value="entry"> Entry Harta</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="JENIS_TUGAS" value="verifikasi"> Verifikasi</label>
                    </div>
                </div>
            </div>
            <div id="doc1" style="display:none;">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Penerimaan Dokumen <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control" name='ID_PENERIMAAN' id='ID_PENERIMAAN' value=''>
                        <button type="button" class="btn btn-sm btn-default" id="btnCariPenerimaan">...</button>
                    </div>
                </div>
            </div>
            <div id="doc2" style="display:none;">
                <div class="form-group">
                    <label class="col-sm-4 control-label">LHKPN <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control" name='ID_LHKPN' id='ID_LHKPN' value=''>
                        <button type="button" class="btn btn-sm btn-default" id="btnCariLHKPN">...</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Assign to <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='USERNAME' id='USERNAME' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Penugasan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control date-picker" name='TANGGAL_PENUGASAN' id='TANGGAL_PENUGASAN' placeholder='DD/MM/YYYY' value='<?php echo date('d/m/Y');?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Due Date <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control date-picker" name='DUE_DATE' id='DUE_DATE' placeholder='DD/MM/YYYY' value='<?php echo date('d/m/Y', strtotime('+7 days'));?>'  required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Keterangan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <textarea name="KETERANGAN"></textarea>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
    </div>
    <div id="wrapperCariLHKPN" style="display: none;">
        <ol class="breadcrumb">
            <li>Form Penugasan</li>
            <li>Cari LHKPN</li>
        </ol>
        <div class="pull-right">
            <form method="post" id="ajaxFormCariLHKPN" action="index.php/efill/lhkpnoffline/hasilcarilhkpn/">
            <div class="input-group col-sm-push-5">
                <div class="col-sm-3">
                    <input type="text" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT_LHKPN"/>
                </div>
                <div class="input-group-btn col-sm-3">
                  <button type="submit" class="btn btn-sm btn-default" id="btn-cari"><i class="fa fa-search"></i></button>
                  <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_LHKPN').val(''); $('#CARI_TEXT_LHKPN').focus(); $('#ajaxFormCariLHKPN').trigger('submit');">Clear</button>
                </div>
            </div>
            </form>
        </div>
        <br>
        <div class="clearfix"></div>
        <div id="wrapperHasilCariLHKPN">
            <!-- draw here -->
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-default btnKembaliKePenugasan">Kembali Ke form</button>
        </div>                
    </div>
    <div id="wrapperCariPenerimaan" style="display: none;">
        <ol class="breadcrumb">
            <li>Form Penugasan</li>
            <li>Cari Penerimaan</li>
        </ol>
        <div class="pull-right">
            <form method="post" id="ajaxFormCariPenerimaan" action="index.php/efill/lhkpnoffline/hasilcaripenerimaan/">
            <div class="input-group col-sm-push-5">
                <div class="col-sm-3">
                    <input type="text" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT_PENERIMAAN"/>
                </div>
                <div class="input-group-btn col-sm-3">
                  <button type="submit" class="btn btn-sm btn-default" id="btn-cari"><i class="fa fa-search"></i></button>
                  <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PENERIMAAN').val(''); $('#CARI_TEXT_PENERIMAAN').focus(); $('#ajaxFormCariPenerimaan').trigger('submit');">Clear</button>
                </div>
            </div>
            </form>
        </div>
        <br>
        <div class="clearfix"></div>
        <div id="wrapperHasilCariPenerimaan">
            <!-- draw here -->
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-default btnKembaliKePenugasan">Kembali Ke form</button>
        </div>
    </div>
</div>
<script type="text/javascript">
penugasan = {
    init: function() {
        _this = penugasan;
        ng.formProcess($("#ajaxFormAddPenugasan"), 'insert', location.href.split('#')[1]);

        $('#btnCariPenerimaan').click(function(){
            _this.showCariPenerimaan();
            _this.hideFormPenugasan();
            _this.hideCariLHKPN();
        });
        $('#btnCariLHKPN').click(function(){
            _this.showCariLHKPN();
            _this.hideFormPenugasan();
            _this.hideCariPenerimaan();
        });
        $('.btnKembaliKePenugasan').click(function() {
            _this.showFormPenugasan();
            _this.hideCariPenerimaan();
            _this.hideCariLHKPN();
        });

        $('input[name="JENIS_TUGAS"]').each(function(index, el) {
            $(this).click(function(){
                if($(this).val()=='create'){
                    $('#doc1').show();
                    $('#doc2').hide();
                }else if($(this).val()=='entry' || $(this).val()=='verifikasi'){
                    $('#doc2').show();
                    $('#doc1').hide();
                }
            });
        });

    },
    showFormPenugasan : function(){
        $('#wrapperFormPenugasan').slideDown('fast', function() {});
    },
    hideFormPenugasan : function(){
        $('#wrapperFormPenugasan').slideUp('fast');
    },
    showCariPenerimaan : function(){
        $('#wrapperCariPenerimaan').slideDown('fast', function() {
            $('#wrapperCariPenerimaan').find('#CARI_TEXT_PENERIMAAN').focus();
        });
        $("#ajaxFormCariPenerimaan").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPenerimaan', _this.eventShowHasilCariPenerimaan);
            return false;
        });        
    },
    hideCariPenerimaan : function(){
       $('#wrapperCariPenerimaan').slideUp('fast');
    },
    eventShowHasilCariPenerimaan: function() {
        $(".paginationPN").find("a").click(function() {
            var url = $(this).attr('href');
            // window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCariPenerimaan'), '#wrapperHasilCariPenerimaan', _this.eventShowHasilCariPenerimaan);
            return false;
        });
        $('.btnSelectPenerimaan').click(function() {
            DATAPENERIMAAN = $(this).attr('data-penerimaan');
            $('#wrapperFormPenugasan').find('#ID_PENERIMAAN').val(DATAPENERIMAAN);
            _this.showFormPenugasan();
            _this.hideCariPenerimaan();
        });
    },    
    showCariLHKPN : function(){
        $('#wrapperCariLHKPN').slideDown('fast', function() {
            $('#wrapperCariLHKPN').find('#CARI_TEXT_LHKPN').focus();
        });
        $("#ajaxFormCariLHKPN").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariLHKPN', _this.eventShowHasilCariLHKPN);
            return false;
        });        
    },
    hideCariLHKPN : function(){
       $('#wrapperCariLHKPN').slideUp('fast');
    },
    eventShowHasilCariLHKPN: function() {
        $(".paginationLHKPN").find("a").click(function() {
            var url = $(this).attr('href');
            // window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCariLHKPN'), '#wrapperHasilCariLHKPN', _this.eventShowHasilCariLHKPN);
            return false;
        });
        $('.btnSelectLHKPN').click(function() {
            DATALHKPN = $(this).attr('data-lhkpn');
            $('#wrapperFormPenugasan').find('#ID_LHKPN').val(DATALHKPN);
            _this.showFormPenugasan();
            _this.hideCariLHKPN();
        });
    },     
}


    $(document).ready(function() {
        $('.date-picker').datepicker({
                orientation: "left",
                format: 'dd/mm/yyyy',
                autoclose: true
        });

        penugasan.init();
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit" class="form-horizontal">
    <form method="post" id="ajaxFormEdit" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group" style="display:none;">
                <label class="col-sm-4 control-label">LHKPN <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='ID_LHKPN' id='ID_LHKPN' value='<?php echo $item->ID_LHKPN;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Assign to <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="" style="width:100%;" name='USERNAME' id='USERNAME' value='<?php echo $item->USERNAME;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Penugasan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control date-picker" name='TANGGAL_PENUGASAN' id='TANGGAL_PENUGASAN' placeholder='DD/MM/YYYY' value='<?php echo date('d/m/Y',strtotime($item->TANGGAL_PENUGASAN));?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Due Date <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control date-picker" name='DUE_DATE' id='DUE_DATE' placeholder='DD/MM/YYYY' value='<?php echo date('d/m/Y',strtotime($item->DUE_DATE));?>'  required>
                </div>
            </div> 
        </div>      
        <div class="pull-right">
            <input type="hidden" name="ID_TUGAS" value="<?php echo $item->ID_TUGAS;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);     
		$('.date-picker').datepicker({
                orientation: "left",
                format: 'dd/mm/yyyy',
                autoclose: true
        });
        $('#USERNAME').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUserKPKAktif')?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getUserKPKAktif')?>/"+id, {
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
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Penugasan dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
	        <div class="form-group" style="display:none;">
	            <label class="col-sm-4" style="text-align:right !important;">LHKPN :</label>
	            <label class="col-sm-8">
	                <?php echo $item->ID_LHKPN;?>
	            </label>
	        </div>
	        <div class="form-group">
	            <label class="col-sm-4" style="text-align:right !important;">Assign to  :</label>
	            <label class="col-sm-8">
	                <?php echo $item->USERNAME;?>
	            </label>
	        </div>
	        <div class="form-group">
	            <label class="col-sm-4" style="text-align:right !important;">Tanggal Penugasan :</label>
	            <label class="col-sm-8">
	                <?php echo date('d/m/Y',strtotime($item->TANGGAL_PENUGASAN));?>
	            </label>
	        </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Due Date :</label>
                <label class="col-sm-8">
                    <?php echo date('d/m/Y',strtotime($item->DUE_DATE));?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_TUGAS" value="<?php echo $item->ID_TUGAS;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
			<button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Assign to  :</label>
            <label class="col-sm-8">
                <?php echo $item->USERNAME;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Tanggal Penugasan :</label>
            <label class="col-sm-8">
                <?php echo date('d/m/Y',strtotime($item->TANGGAL_PENUGASAN));?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Due Date :</label>
            <label class="col-sm-8">
                <?php echo date('d/m/Y',strtotime($item->DUE_DATE));?>
            </label>
        </div>
    </div>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>

