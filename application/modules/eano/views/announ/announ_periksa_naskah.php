<div class="row" style="margin-bottom: 10px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <button type="button" class="btn btn-sm btn-success btnCatatanSesuai" data-href="index.php/eano/announ/catatanPerbaikan">Naskah Sesuai</button>
        <button type="button" class="btn btn-sm btn-danger btnCatatan">Catatan Perbaikan</button>
    </div>
</div>
<div class="row" id="con_catatan" style="display: none;">
    <form method="post" class='form-horizontal' id="ajaxFormAdd" action="index.php/eano/announ/catatanPerbaikan">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <div class="col-md-12">
            <textarea id="MSG_VERIFIKASI" name="msg" rows="5" style="width: 100%;" class="ckeditor">
                <?php echo @$data->CATATAN_PERBAIKAN_NASKAH; ?>
            </textarea>
        </div>
        <div class="col-md-2" style="margin-top: 10px;">
            <input required type="checkbox" name="status" value="2" <?php echo $data->STATUS_PERBAIKAN_NASKAH == '2' ? 'checked="checked"' : '' ?> /> Naskah ini perlu diperbaiki
        </div>
        <div class="col-md-10 text-right" style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary">Simpan Catatan Perbaikan</button>
            <button type="button" class="btn btn-danger btnCatatan">Batal</button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>plugins/ckeditor/contents.css?v=<?=$this->config->item('cke_version');?>" type="text/css"/>
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url();?>plugins/ckeditor/adapters/jquery.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url();?>plugins/jQueryUI/jquery-ui-1.10.3.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<style>
    .ui-resizable-helper { border: 2px dotted #00F; }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('.pdf-iframe').resizable({
            handles: 'e',
            minWidth: 200,
            maxWidth: 1000,
            resize: function( event, ui ) {
                var width = parseInt($('.pdf-iframe').width()) - 570;
                $('.pdf-iframe2').width(600-width)
            }
        });

        $('.ckeditor').ckeditor();

        $('.btnCatatan').click(function () {
            $('#con_catatan').toggle('slow');
        })

        $('#ajaxFormAdd').submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.post(url, data, function (res) {
                if(res == '1'){
                    alertify.success('Catatan Berhasil Disimpan!');
                    $('#con_catatan').toggle('slow');
                    ng.LoadAjaxContent('index.php/eano/announ/index/terverifikasi/');
                } else {
                    alertify.error('Catatan Berhasil Disimpan!');
                }
                CloseModalBox();
            });
//
            return false;
        })

        $('.btnCatatanSesuai').click(function () {
            var url = $(this).attr('data-href');

            $.post(url, {status: '1', id: '<?php echo $id; ?>'}, function (res) {
                if(res == '1'){
                    alertify.success('Naskah Berhasil Disimpan!');
                    ng.LoadAjaxContent('index.php/eano/announ/index/terverifikasi/');
                } else {
                    alertify.error('Naskah Berhasil Disimpan!');
                }
                CloseModalBox();
            });
//
            return false;
        })
    });
</script>